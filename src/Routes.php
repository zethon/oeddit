<?php declare(strict_types = 1);

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

    print_r($args);
}

return 
[
    ['GET', '/', 
        function () 
        {	
            require (__DIR__ . '/../src/includes/global.php');
            verify_access();
            $subs = get_subbed_socs();
            $feed = get_news_feed();
            render(__DIR__ . '/templates/home.php', ["title" => "Home", "subs" => $subs, "posts" => $feed]);
        }
    ],
    ['GET', '/o[/{name}]', 'soc_page'],
    ['GET', '/soc[/{name}]', 'soc_page'],
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