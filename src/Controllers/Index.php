<?php declare(strict_types = 1);

namespace Oeddit\Controllers;

class Index
{
    public function show()
    {
        require (__DIR__ . '/../includes/global.php');
        verify_access();
        $subs = get_subbed_socs();
        $feed = get_news_feed();
        render(__DIR__ . '/../templates/home.php', ["title" => "Home", "subs" => $subs, "posts" => $feed]);
    }
}