<?php declare(strict_types = 1);

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
    ['GET', '/soc[/{name}]', 
        function ($args) 
        {
            require (__DIR__ . '/../src/includes/global.php');
            if (!isset($args['name'])) 
            {
                print("<h1>REDIRECT</h1>"); die;
                redirect('/');
            }

            if (isset($_GET["view"]) && strcasecmp($_GET["view"], "info") == 0)
            {
                require("soc_info.php");
                exit;
            }
        
            // fetch posts
            $posts = get_posts($soc, isset($_GET["page"]) ? $_GET["page"]:0, 10);
        
            render_mult([	
                            "soc_common.php", 
                            "soc_front.php"
                        ], 
                        [
                            "title"  => $soc["soc_name"],
                            "posts"  => $posts,
                            "soc"    => $soc,
                            "status" => $status
                        ]
                        );
        }
    ],
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