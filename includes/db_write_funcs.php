<?php


function change_user_status($uid, $aid, $new_status, $action, $comment)
{
    return tquery("	update users
                    set status = ?
                    where user_id = ?;
                    
                    insert into user_control_admin_log(user_id, admin_id, action, comment)
                    values(?, ?, ?, ?);",
                    [
                        $new_status,
                        $uid
                    ],
                    [
                        $uid, 
                        $aid, 
                        $action,
                        $comment
                    ]
                    );
}
    
function make_user($uname, $pwd)
{
    $retval = tquery("INSERT INTO users (username, password) VALUES (?, ?)", 
                    [
                        $uname, 
                        password_hash($pwd, PASSWORD_DEFAULT)
                    ]
                );

    if ($retval)
    {
        $san = get_society('announcements');
        if ($user = query("SELECT * FROM users WHERE (username = ?)", $uname))
        {
            return tquery("INSERT INTO soc_subs (soc_id, user_id) VALUES (?, ?)",
                [
                    $san["soc_id"],
                    $user[0]["user_id"]
                ]);
        }
    }

    return false;
}

function report_user($uname, $reason)
{
    if (!($u = get_user($uname)))	apologize("Invalid username.");
    if (is_deleted($u))				apologize("User does not exist.");
    
    return tquery(" INSERT INTO reports(user_id, reason)
                    VALUES(?, ?);
                    
                    SET @last_id = LAST_INSERT_ID();

                    INSERT INTO user_reports(report_id, user_id)
                    VALUES(@last_id, ?);",
                    [
                        $_SESSION["user"]["user_id"],
                        $reason
                    ],
                    [
                    ],
                    [
                        $u["user_id"]
                    ]
                    );
}

function del_self()
{
    return tquery("	update users
                    set status = 'DELETED'
                    where user_id = ?",
                    [	
                        $_SESSION["user"]["user_id"]
                    ]
                    );
}

function del_user($u, $comment = null)
{
    if (!($u = get_user($uname)))		apologize("Invalid username.");
    if (!am_admin() || is_admin($u))	apologize("Access Denied.");
    if (is_deleted($u))					apologize("User does not exist.");

    return change_user_status($u["user_id"], $_SESSION["user"]["user_id"], "DELETED", "DELETE", $comment);	
}

function ban_user($uname, $comment = null)
{
    if (!($u = get_user($uname)))		apologize("Invalid username.");
    if (!am_admin() || is_admin($u))	apologize("Access Denied.");
    if (is_banned($u))					apologize("User is already banned.");
    if (is_deleted($u))					apologize("User does not exist.");

    return change_user_status($u["user_id"], $_SESSION["user"]["user_id"], "BANNED", "BAN", $comment);		
}

function unban_user($uname, $comment = null)
{
    if (!($u = get_user($uname)))		apologize("Invalid username.");
    if (!am_admin())					apologize("Access Denied.");
    if (!is_banned($u))					apologize("User is not currently banned.");
    if (is_deleted($u))					apologize("User does not exist.");

    return change_user_status($u["user_id"], $_SESSION["user"]["user_id"], "NORMAL", "UNBAN", $comment);		
}
    
function make_admin($uname, $comment = null)
{
    if (!($u = get_user($uname)))	apologize("Invalid username.");
    if (!am_admin())				apologize("Access Denied.");
    if (is_admin($u))				apologize("User is already an admin.");
    
    return change_user_status($u["user_id"], $_SESSION["user"]["user_id"], "ADMIN", "ADMIN", $comment);	
}

function del_admin($uname, $comment = null)
{
    if (!($u = get_user($uname)))				apologize("Invalid username.");
    if (!am_admin() || $u["user_id"] == 1)		apologize("Access Denied.");
    if (!is_admin($u))							apologize("User is not currently an admin.");
    
    return change_user_status($u["user_id"], $_SESSION["user"]["user_id"], "NORMAL", "DEADMIN", $comment);
}
    
function change_soc_status($sid, $aid, $new_status, $action, $comment)
{
    return tquery("	update societies
                    set status = ?
                    where soc_id = ?;
                    
                    insert into soc_control_admin_log(soc_id, admin_id, action, comment)
                    values(?, ?, ?, ?);",
                    [
                        $new_status,
                        $sid
                    ],
                    [
                        $sid, 
                        $aid, 
                        $action,
                        $comment
                    ]
                    );
}

function make_soc($sname)
{
    if (am_banned())	
    {
        apologize("Access Denied.");
    }

    $retval = tquery("INSERT INTO societies (soc_name, created_by) VALUES (?, ?)", 
        [
            $sname,
            $_SESSION["user"]["user_id"]
        ]);

    if ($retval === true) 
    {   
        $s = get_society_id($sname);
        $retval = tquery("INSERT INTO soc_details (soc_id, revised_by, info) VALUES (?, ?, ?)", 
        [
            $s["soc_id"],
            $_SESSION["user"]["user_id"],
            $sname
        ]);

        if ($retval === true)
        {
            sub_soc($s);
            make_post($s, "$sname has been created", "Ok, great!");
        }
    }

    return $retval;
}

function lock_soc($sname, $comment = null)
{
    if (!($s = get_society($sname)))	apologize("No such society.");
    if (!am_admin())					apologize("Access Denied.");
    
    return change_soc_status($s["soc_id"], $_SESSION["user"]["user_id"], "LOCKED", "LOCK", $comment);
}

function del_soc($sname, $comment = null)
{
    if (!($s = get_society($sname)))	apologize("No such society.");
    if (!am_admin())					apologize("Access Denied.");
    
    return change_soc_status($s["soc_id"], $_SESSION["user"]["user_id"], "DELETED", "DELETE", $comment);
}

function unlock_soc($sname, $comment = null)
{
    if (!($s = get_society($sname)))	apologize("No such society.");
    if ($s["status"] != "LOCKED")		apologize("Society not currently locked.");
    if (!am_admin())					apologize("Access Denied.");
    
    return change_soc_status($s["soc_id"], $_SESSION["user"]["user_id"], "NORMAL", "UNLOCK", $comment);
}

function undel_soc($sname, $comment = null)
{
    if (!($s = get_society($sname, true)))	apologize("No such society.");
    if ($s["status"] != "DELETED")			apologize("Society not currently deleted.");
    if (!am_admin())						apologize("Access Denied.");
    
    return change_soc_status($s["soc_id"], $_SESSION["user"]["user_id"], "NORMAL", "UNDELETE", $comment);
}
        
function sub_soc($soc)
{
    return tquery("	insert into soc_subs(soc_id, user_id) 
                    values(?, ?)",
                    [
                        $soc["soc_id"],
                        $_SESSION["user"]["user_id"]
                    ]
                );
}

function unsub_soc($soc)
{
    return tquery("	delete from soc_subs
                    where soc_id = ? 
                    and   user_id = ?",
                    [
                        $soc["soc_id"],
                        $_SESSION["user"]["user_id"]
                    ]
                );
}
    
function ban_from_soc($uname, $s, $comment = null)
{
    if (!($u = get_user($uname)))			apologize("Invalid username.");
    $ustatus = soc_rel($s, $u["user_id"]);
    if ($ustatus["banned"])					apologize("User is already banned from ".$s["soc_name"].".");
    if ($ustatus["mod"])					apologize("Access Denied: Cannot ban a moderator.");
    if (!am_mod($s))						apologize("Access Denied.");

    return tquery("	
                    insert into soc_bans(user_id, soc_id)
                    values(?, ?);
                    
                    insert into user_control_mod_log(user_id, mod_id, soc_id, action, comment)
                    values(?, ?, ?, ?, ?);",
                    [
                        $u["user_id"],
                        $s["soc_id"]
                    ],
                    [
                        $u["user_id"],
                        $_SESSION["user"]["user_id"],
                        $s["soc_id"],
                        "BAN",
                        $comment
                    ]
                    );
}

function unban_from_soc($uname, $s, $comment = null)
{
    if (!($u = get_user($uname)))			apologize("Invalid username.");
    $ustatus = soc_rel($s, $u["user_id"]);
    if (!$ustatus["banned"])				apologize("User not currently banned from ".$s["soc_name"].".");
    if (!am_mod($s))						apologize("Access Denied.");

    return tquery("	
                    delete from soc_bans
                    where user_id = ?
                    and   soc_id  = ?;
                    
                    insert into user_control_mod_log(user_id, mod_id, soc_id, action, comment)
                    values(?, ?, ?, ?, ?);",
                    [
                        $u["user_id"],
                        $s["soc_id"]
                    ],
                    [
                        $u["user_id"],
                        $_SESSION["user"]["user_id"],
                        $s["soc_id"],
                        "UNBAN",
                        $comment
                    ]
                    );
}

function make_mod($uname, $s, $comment = null)
{
    if (!($u = get_user($uname)))			apologize("Invalid username.");
    if (is_banned($u))						apologize("User is banned from the site.");
    $ustatus = soc_rel($s, $u["user_id"]);
    if ($ustatus["banned"])					apologize("User is banned from ".$s["soc_name"].".");
    if ($ustatus["mod"])					apologize("User is already a moderator of ".$s["soc_name"].".");
    if (!am_mod($s))						apologize("Access Denied.");

    return tquery("	
                    insert into soc_mods(user_id, soc_id)
                    values(?, ?);
                    
                    insert into user_control_mod_log(user_id, mod_id, soc_id, action, comment)
                    values(?, ?, ?, ?, ?);",
                    [
                        $u["user_id"],
                        $s["soc_id"]
                    ],
                    [
                        $u["user_id"],
                        $_SESSION["user"]["user_id"],
                        $s["soc_id"],
                        "MOD",
                        $comment
                    ]
                    );
}

function del_mod($uname, $s, $comment = null)
{
    if (!($u = get_user($uname)))						apologize("Invalid username.");
    if (is_banned($u))									apologize("User is banned from the site.");
    $ustatus = soc_rel($s, $u["user_id"]);
    if (!$ustatus["mod"])								apologize("User is not a moderator of ".$s["soc_name"].".");
    if (!am_mod($s) 
        || ($ustatus["creator"]
            && $u["user_id"] == $_SESSION["user_id"]))	apologize("Access Denied.");

    return tquery("	
                    delete from soc_mods
                    where user_id = ?
                    and   soc_id  = ?;
                    
                    insert into user_control_mod_log(user_id, mod_id, soc_id, action, comment)
                    values(?, ?, ?, ?, ?);",
                    [
                        $u["user_id"],
                        $s["soc_id"]
                    ],
                    [
                        $u["user_id"],
                        $_SESSION["user"]["user_id"],
                        $s["soc_id"],
                        "DEMOD",
                        $comment
                    ]
                    );
}

function del_post($pid, $sname, $comment = null)
{
    if (!($p = get_post($pid)))			apologize("Post does not exist.");
    if ($p["status"] == 'DELETED')		apologize("Post already deleted.");
    if (!am_mod(get_society($sname)))	apologize("Access Denied.");
    
    if ($p["status"] == 'STICKIED')		
        unsticky_post($p, "Prepare for deletion.");

    return tquery("	UPDATE posts
                        SET status = ?
                        WHERE post_id = ?;

                    INSERT INTO post_control_log(post_id, mod_id, action, comment)
                    VALUES(?, ?, ?, ?);",
                    [
                        "DELETED",
                        $p["post_id"],
                    ],
                    [
                        $p["post_id"],
                        $_SESSION["user"]["user_id"],
                        "DELETE",
                        $comment
                    ]
                );
}

function undel_post($p, $s, $comment = null)
{
    if ($p["status"] != 'DELETED')		apologize("Post not currently deleted.");
    if (!am_mod($s))					apologize("Access Denied.");

    return tquery("
                    update posts
                    set status = ?
                    where post_id = ?;

                    insert into post_control_log(post_id, mod_id, action, comment)
                    values(?, ?, ?, ?);",
                    [
                        "NORMAL",
                        $p["post_id"],
                    ],
                    [
                        $p["post_id"],
                        $_SESSION["user"]["user_id"],
                        "UNDELETE",
                        $comment
                    ]
                );
}

function sticky_post($pid, $sname, $comment = null)
{
    if (!($p = get_post($pid)))			apologize("Post does not exist.");
    if ($p["status"] == 'STICKIED')		apologize("Post already stickied.");
    if ($p["status"] == 'DELETED')		apologize("Post does not exist.");
    if (!am_mod(get_society($sname)))	apologize("Access Denied.");

    return tquery("
                    update posts
                    set status = ?
                    where post_id = ?;

                    insert into post_control_log(post_id, mod_id, action, comment)
                    values(?, ?, ?, ?);",
                    [
                        "STICKIED",
                        $p["post_id"],
                    ],
                    [
                        $p["post_id"],
                        $_SESSION["user"]["user_id"],
                        "STICKY",
                        $comment
                    ]
                );
}

function unsticky_post($pid, $s, $comment = null)
{
    if (!($p = get_post($pid)))			apologize("Post does not exist.");
    if ($p["status"] == 'NORMAL')		apologize("Post not currently stickied.");
    if ($p["status"] == 'DELETED')		apologize("Post does not exist.");
    if (!am_mod(get_society($sname)))	apologize("Access Denied.");

    return tquery("
                    update posts
                    set status = ?
                    where post_id = ?;

                    insert into post_control_log(post_id, mod_id, action, comment)
                    values(?, ?, ?, ?);",
                    [
                        "NORMAL",
                        $p["post_id"],
                    ],
                    [
                        $p["post_id"],
                        $_SESSION["user"]["user_id"],
                        "UNSTICKY",
                        $comment
                    ]
                );
}

function del_comment($cid, $s, $comment = null)
{
    if (!($c = get_comment($cid)))		apologize("Comment does not exist.");
    if ($c["status"] == 'DELETED')		apologize("Comment already deleted.");
    if (!am_mod(get_society($s)))		apologize("Access Denied.");

    return tquery("
                    update comments
                    set status = ?
                    where comm_id = ?;

                    insert into comm_control_log(comm_id, mod_id, action, comment)
                    values(?, ?, ?, ?);",
                    [
                        "DELETED",
                        $c["comm_id"],
                    ],
                    [
                        $c["comm_id"],
                        $_SESSION["user"]["user_id"],
                        "DELETE",
                        $comment
                    ]
                );
}

function undel_comment($c, $s, $comment = null)
{
    if ($c["status"] != 'DELETED')		apologize("Comment not currently deleted.");
    if (!am_mod($s))					apologize("Access Denied.");

    return tquery("
                    update comments
                    set status = ?
                    where comm_id = ?;

                    insert into comm_control_log(comm_id, mod_id, action, comment)
                    values(?, ?, ?, ?);",
                    [
                        "NORMAL",
                        $c["comm_id"],
                    ],
                    [
                        $c["comm_id"],
                        $_SESSION["user"]["user_id"],
                        "UNDELETE",
                        $comment
                    ]
                );
}

function make_post($s, $title, $text = null)
{
    // if (am_banned_soc($s) || am_banned())		apologize("Access Denied.");
    if(!$title)									apologize("Post must have a title");

    return tquery(" insert into posts(user_id, soc_id, title, text)
                    values(?, ?, ?, ?)",
                    [
                        $_SESSION["user"]["user_id"],
                        $s["soc_id"],
                        $title,
                        $text
                    ]
                    );
}

function sub_post($pid)
{
    return tquery(" insert into post_subs(user_id, post_id)
                    values(?, ?)",
                    [
                        $_SESSION["user"]["user_id"],
                        $pid
                    ]
                    );
}

function unsub_post($pid)
{
    return tquery(" delete from post_subs
                    where user_id = ?
                    and   post_id = ?",
                    [
                        $_SESSION["user"]["user_id"],
                        $pid
                    ]
                    );
}

function register_post_view($pid)
{
    return tquery(" INSERT INTO post_views(user_id, post_id)
                    VALUES(?, ?)",
                    [
                        $_SESSION["user"]["user_id"],
                        $pid
                    ]
                    );
}

function report_society($sid, $reason)
{
    if (!$reason) 	apologize("Must specify a reason for report.");

    return tquery(" INSERT INTO reports(user_id, reason)
                    VALUES(?, ?);

                    SET @last_id = LAST_INSERT_ID();

                    INSERT INTO soc_reports(report_id, soc_id)
                    VALUES(@last_id, ?);",
                    [
                        $_SESSION["user"]["user_id"],
                        $reason
                    ],
                    [
                    ],
                    [
                        $sid
                    ]
                    );
}

function report_comment($cid, $reason)
{
    if (!$reason) 	apologize("Must specify a reason for report.");

    return tquery(" INSERT INTO reports(user_id, reason)
                    VALUES(?, ?);

                    SET @last_id = LAST_INSERT_ID();

                    INSERT INTO comm_reports(report_id, comm_id)
                    VALUES(@last_id, ?);",
                    [
                        $_SESSION["user"]["user_id"],
                        $reason
                    ],
                    [
                    ],
                    [
                        $cid
                    ]
                    );
}

function report_post($pid, $reason)
{
    if (!$reason) 	apologize("Must specify a reason for report.");

    return tquery(" INSERT INTO reports(user_id, reason)
                    VALUES(?, ?);
                    SET @last_id = LAST_INSERT_ID();
                    INSERT INTO post_reports(report_id, post_id)
                    VALUES(@last_id, ?);",
                    [
                        $_SESSION["user"]["user_id"],
                        $reason
                    ],
                    [
                    ],
                    [
                        $pid
                    ]
                    );
}

function upvote_post($pid)
{
    return tquery("	INSERT INTO post_votes(user_id, post_id, vote) 
                    VALUES(?, ?, 'UP')
                    ON DUPLICATE KEY 
                    UPDATE vote = 'UP'", 
                    [
                        $_SESSION["user"]["user_id"],
                        $pid
                    ]
                );
}

function downvote_post($pid)
{
    return tquery("	INSERT INTO post_votes(user_id, post_id, vote) 
                    VALUES(?, ?, 'DOWN')
                    ON DUPLICATE KEY 
                    UPDATE vote = 'DOWN'", 
                    [
                        $_SESSION["user"]["user_id"],
                        $pid
                    ]
                );		
}

function unvote_post($pid)
{
    return tquery(" DELETE 
                        FROM post_votes
                        WHERE user_id = ?
                        and post_id = ?",
                    [
                        $_SESSION["user"]["user_id"],
                        $pid
                    ]
                    );
}


function upvote_comment($cid)
{
    return tquery("	INSERT INTO comm_votes(user_id, comm_id, vote) 
                    VALUES(?, ?, 'UP')
                    ON DUPLICATE KEY 
                    UPDATE vote = 'UP'", 
                    [
                        $_SESSION["user"]["user_id"],
                        $cid
                    ]
                );	
}

function downvote_comment($cid)
{
    return tquery("	INSERT INTO comm_votes(user_id, comm_id, vote) 
                    VALUES(?, ?, 'DOWN')
                    ON DUPLICATE KEY 
                    UPDATE vote = 'DOWN'", 
                    [
                        $_SESSION["user"]["user_id"],
                        $cid
                    ]
                );	
}

function unvote_comment($cid)	
{
    return tquery(" DELETE 
                    FROM  comm_votes
                    WHERE user_id = ?
                    and   comm_id = ?",
                    [
                        $_SESSION["user"]["user_id"],
                        $cid
                    ]
                    );
}

function make_comment($p, $text, $par_id = null)
{
    return tquery("	insert into comments(user_id, post_id, parent_id, text)
                    values(?, ?, ?, ?)",
                    [
                        $_SESSION["user"]["user_id"],
                        $p["post_id"],
                        $par_id,
                        $text
                    ]
                    );
}

function make_pm($to, $subj = null, $msg)
{
    return tquery("	INSERT INTO pms(sender, receiver, subject, msg) 
                    VALUES(?, ?, ?, ?)", 
                    [
                        $_SESSION["user"]["user_id"],
                        $to["user_id"],
                        $subj,
                        $msg
                    ]
                );	
}

function edit_soc_info($sid, $info)
{
    if (!am_mod(get_society_by_id($sid)))	apologize("Access Denied.");

    return tquery("INSERT INTO soc_details(soc_id, revised_by, info) 
                    VALUES(?, ?, ?);

                    SET @last_id = LAST_INSERT_ID();

                    UPDATE societies
                        SET rev_id = @last_id
                        WHERE soc_id = ?;",
                    [
                        $sid,
                        $_SESSION["user"]["user_id"],
                        $info
                    ],
                    [
                    ],
                    [
                        $sid
                    ]
                );
}

?>