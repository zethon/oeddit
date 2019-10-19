<?php

    require_once("global.php");

	/**
     * Apologizes to user with message.
     */
	function apologize($message)
    {
        render("apology.php", ["title" => "Sorry!", "message" => $message]);
        exit;
    }
		
	/**
     * Logs out current user, if any
     */
    function logout()
    {
		session_start();
		
        // unset any session variables
        $_SESSION = array();

        // expire cookie
        if (!empty($_COOKIE[session_name()]))
        {
            setcookie(session_name(), "", time() - 42000);
        }

        // destroy session
        session_destroy();
    }
	
    /**
     * Facilitates debugging by dumping contents of variable
     * to browser.
     */
    function dump($variable)
    {
        require("../templates/dump.php");
        exit;
    }

    /**
     * Executes multiple SQL statements in a single transaction, each with its own parameters
     */
	function tquery()
	{
		// sql statements in transaction
		$sqls = explode(";", rtrim(func_get_arg(0), ";"));
		
        // parameters, if any
        $params_list = array_slice(func_get_args(), 1);

		if (count($sqls) != count($params_list))
			apologize("sql error");
		
		static $handle;
        if (!isset($handle))
        {
            try
            {
                // connect to database
                $handle = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);

                // ensure that PDO::prepare returns false when passed invalid SQL
                $handle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
				$handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch (Exception $e)
            {
                // trigger (big, orange) error
                trigger_error($e->getMessage(), E_USER_ERROR);
                exit;
            }
        }

		foreach($sqls as $sql)
		{		
			// prepare SQL statement
			if (($statements[] = $handle->prepare($sql)) === false)
			{
				trigger_error($handle->errorInfo()[2], E_USER_ERROR);
				exit;
			}
		}
		
		try
		{
			$handle->beginTransaction();
			for ($i = 0; $i < count($statements); $i++)
			{
				$statements[$i]->execute($params_list[$i]);
			}
			$handle->commit();
		}
		catch(Exception $e)
		{
			$handle->rollback();
			trigger_error($e->getMessage(), E_USER_ERROR);
			return false;
        }
        
        return true;
	}
	
    /**
     * Executes SQL statement, possibly with parameters, returning
     * an array of all rows in result-set or false on error.
     */
    function query()
    {
    	log_activity();

        // SQL statement
        $sql = func_get_arg(0);
		
        // parameters, if any
        $parameters = array_slice(func_get_args(), 1);

        // try to connect to database
        static $handle;
        if (!isset($handle))
        {
            try
            {
                // connect to database
                $handle = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);

                // ensure that PDO::prepare returns false when passed invalid SQL
                $handle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
				$handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch (Exception $e)
            {
                // trigger (big, orange) error
                trigger_error($e->getMessage(), E_USER_ERROR);
                exit;
            }
        }

        // prepare SQL statement
        $statement = $handle->prepare($sql);
        if ($statement === false)
        {
            trigger_error($handle->errorInfo()[2], E_USER_ERROR); exit;
        }
		
		try
		{
			// execute SQL statement
			$results = $statement->execute($parameters);

			// return result set's rows, if any
			if ($results !== false)
				return $statement->fetchAll(PDO::FETCH_ASSOC);
			else
				return false;
		}
		catch(Exception $e)
		{	
			trigger_error($e->getMessage(), E_USER_ERROR); exit;
		}
    }

    function log_activity()
    {
    	if (isset($_SESSION["user"]))
    	{
    		tquery("INSERT INTO user_activity(user_id)
	    			VALUES(?)",
	    			[
	    				$_SESSION["user"]["user_id"]
	    			]);
    	}
    }

    function log_soc_activity($sid)
    {
    	if (isset($_SESSION["user"]))
    	{
    		tquery("INSERT INTO soc_views(user_id, soc_id)
	    			VALUES(?, ?)",
	    			[
	    				$_SESSION["user"]["user_id"],
	    				$sid
	    			]);
    	}
    }

    /**
     * Redirects user to destination, which can be
     * a URL or a relative path on the local host.
     *
     * Because this function outputs an HTTP header, it
     * must be called before caller outputs any HTML.
     */
    function redirect($destination)
    {
        // handle URL
        if (preg_match("/^https?:\/\//", $destination))
        {
            header("Location: " . $destination);
        }        
        else
		{
			$protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
			$host = $_SERVER["HTTP_HOST"];
			
			if (preg_match("/^\//", $destination))  // handle absolute path
			{
				header("Location: $protocol://$host$destination");
			}
			else // handle relative path
			{
				$path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
				header("Location: $protocol://$host$path/$destination");
			}
		}
        exit;
    }

    /**
     * Renders template, passing in values.
     */
    function render($template, $values = [])
    {
        // if template exists, render it
        if (file_exists("../templates/$template"))
        {
            // extract variables into local scope
            extract($values);

            require("../templates/header.php");
			
            // render template
            require("../templates/$template");
            
            require("../templates/footer.php");
        }
        else
        {
            trigger_error("Invalid template: $template", E_USER_ERROR);
        }
    }
    function render_mult($templates, $values = [])
    {
        // extract variables into local scope
        extract($values);

        require("../templates/header.php");

    	foreach ($templates as $template)
        {
	        // if template exists, render it
	        if (!file_exists("../templates/$template"))
	            trigger_error("Invalid template: $template", E_USER_ERROR);

            // render template
            require("../templates/$template");
    	}        
        require("../templates/footer.php");
    }

	/**
	 *	Check if user is logged in
	 */
	function verify_access()
	{
		session_start();
		if (!isset($_SESSION["user"]))
		{
			redirect("login.php");
		}
	}
	
	/**
	 *	Log login attempt
	 */
	function log_attempt($u, $ip, $succ)
	{
		tquery("INSERT INTO login_log(USER_ID, IP, RESULT) 
				VALUES(?, ?, ?);
		
				UPDATE users
				SET failed_logins = ?*(failed_logins+1)
				WHERE user_id = ?;", 
				[	$u["user_id"], 
					$ip, 
					$succ ? "SUCCESS":"FAILURE"
				],
				[	$succ ? 0:1,
					$u["user_id"]
				]
			);
		
	}	
	
	function refresh_self()
	{
		isset($_SESSION) || session_start();
		return ($_SESSION["user"] = get_user_by_id($_SESSION["user"]["user_id"])) 
				?
				$_SESSION["user"]:false;
	}
	
	function am_admin()
	{
		return refresh_self()["status"] == 'ADMIN';
	}

	function am_banned()
	{
		return refresh_self()["status"] == 'BANNED';
	}
	
	function is_admin($u)
	{
		return $u["status"] == 'ADMIN';
	}
	
	function is_banned($u)
	{
		return $u["status"] == 'BANNED';
	}

	function is_deleted($u)
	{
		return $u["status"] == 'DELETED';
	}

	function am_mod($s)
	{
		$rel = soc_rel($s);
		return $rel["mod"] || $rel["admin"];
	}

	function am_banned_soc($s)
	{
		$rel = soc_rel($s);
		return $rel["banned"];
	}

?>