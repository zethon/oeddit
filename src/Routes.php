<?php declare(strict_types = 1);

function index_page($args)
{
    require (__DIR__ . '/includes/global.php');
    verify_access();
    $subs = get_subbed_socs();
    $feed = get_news_feed();
    render(__DIR__ . '/templates/home.php', ["title" => "Home", "subs" => $subs, "posts" => $feed]);
}

function soc_page_redirect($args)
{
    require (__DIR__ . '/../src/includes/global.php');
    redirect('/');
}

function soc_page($args)
{
    require (__DIR__ . '/../src/includes/global.php');
    verify_access();

    if (!isset($args['name'])) 
    {
        print("<h1>REDIRECT</h1>"); die;
        redirect('/');
    }

    if (get_society_id($args['name']) === false)
    {
        apologize("that mofo does not exist!");
    }

    $soc = get_society($args['name']);
    if (!$soc)
    {
		apologize("that mofo is empty");
    }
    
    log_soc_activity($soc["soc_id"]);

    $title = $soc["soc_name"];
	$status = soc_rel($soc);
    
    // if (isset($_GET["saction"]))
    // {
    //     if (strcasecmp($_GET["saction"], "sub") == 0 && !$status["sub"])
    //     {
    //         sub_soc($soc);
    //     }
    //     elseif (strcasecmp($_GET["saction"], "unsub") == 0 && $status["sub"])
    //     {
    //         unsub_soc($soc);
    //     }
    //     $status = soc_rel($soc);
    // }	
    
	// if (isset($_GET["view"]) && strcasecmp($_GET["view"], "info") == 0)
	// {
	// 	require("soc_info.php");
	// 	exit;
    // }
    
    // fetch posts
	$posts = get_posts($soc, isset($_GET["page"]) ? $_GET["page"]:0, 10);

	render_mult([	
        __DIR__ . "/templates/soc_common.php", 
        __DIR__ . "/templates/soc_front.php"
        ], 
        [
            "title"  => $soc["soc_name"],
            "posts"  => $posts,
            "soc"    => $soc,
            "status" => $status
        ]
    );
}

function soc_post($args)
{
    require (__DIR__ . '/../src/includes/global.php');
    verify_access();

    $soc = get_society($args['name']);
    if (!$soc)
    {
		apologize("that mofo is empty");
    }

    $post = get_post($args['post']);
    if (!$post || $post["status"] == "DELETED")
    {
        apologize("that post wasn't found");
    }

    if ($post["soc_id"] != $soc["soc_id"])
    {
        // redirect("post.php?pid=".$_GET["pid"]."&soc=".get_society_by_id($post["soc_id"])["soc_name"]);
        print("<h1>REDIRECT ME</h1>"); die;
    }

    // register post view
    register_post_view($post["post_id"]);

    // if (isset($_GET["paction"]))
    // {
    //     if (strcasecmp($_GET["paction"], "sub"))
    //         post_sub($post["post_id"]);
    //     elseif (strcasecmp($_GET["paction"], "unsub"))
    //         post_unsub($post["post_id"]);
    // }

    // fetch comments
    $comms = get_comments($post);

    render_mult(
        [
            __DIR__ . "/templates/soc_common.php",
            __DIR__ . "/templates/post.php"
        ],
        [	
            "title" => $post["title"]." - ".$soc["soc_name"],
            "post" => $post,
            "soc" => $soc,
            "status" => soc_rel($soc),
            "psub" => post_rel($post["post_id"]),
            "comms" => $comms
        ]);
}

function soc_about($args)
{
    require (__DIR__ . '/../src/includes/global.php');
    verify_access();

    $soc = get_society($args['name']);
    if (!$soc)
    {
		apologize("that mofo is empty");
    }

    $title = $soc["soc_name"];
    $status = soc_rel($soc);
    
	// if ($_SERVER["REQUEST_METHOD"] == "POST")
    // {
    // 	session_start();

	// 	if (edit_soc_info($_POST["soc_id"], $_POST["info"]) === false)
	// 		apologize("Failed to make edit.");

	// 	redirect("soc.php?soc=".$_POST["soc_name"]);
	// }
	// else
	// {
		$hist = get_soc_edit_history($soc["soc_id"]);

		render_mult([
						__DIR__ . "/templates/soc_common.php",
						__DIR__ . "/templates/soc_info.php"
					], 
					[
						"title"  => "About ".$soc["soc_name"],
						"soc"    => $soc,
						"status" => $status,
						"hist"	 => $hist
					]
					);
	// }    
}

return 
[
    ['GET', '/', 'index_page'],

    ['GET','/o', 'soc_page_redirect'],
    ['GET','/o/{name}', 'soc_page'],
    ['GET','/o/{name}/about', 'soc_about'],
    ['GET','/o/{name}/{post}', 'soc_post'],
    

    ['GET', '/post', 
        function () 
        {
            echo 'View a fucing post!';
        }
    ],
    ['GET', '/another-route', 
        function () 
        {
            echo 'This works too';
        }
    ],
];