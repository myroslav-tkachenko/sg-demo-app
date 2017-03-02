<?php

require_once 'vendor/autoload.php';

$feed = new SimplePie();
$feed->enable_cache(false);
$feed->set_feed_url('https://rss.unian.net/site/news_ukr.rss');
$feed->init();


$db = new PDO("mysql:host=localhost;dbname=rss_news;charset=utf8", "root", "123");
$sql = "INSERT INTO news (title, link, description, source) VALUES (?, ?, ?, ?)";

$items = $feed->get_items();

foreach ($items as $item) {
    $stmt = $db->prepare($sql);
    $stmt->execute([
        $item->get_title(),
        $item->get_link(),
        $item->get_description(),
        $feed->get_link(),
    ]);
}
