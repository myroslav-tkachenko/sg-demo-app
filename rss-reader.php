<?php

require_once 'vendor/autoload.php';

date_default_timezone_set('Europe/Kiev');

$feed = new SimplePie();
$feed->enable_cache(false);
$feed->enable_order_by_date(false);
$feed->set_feed_url('https://rss.unian.net/site/news_ukr.rss');
$feed->init();

$db = new PDO("mysql:host=localhost;dbname=rss_news;charset=utf8", "root", "123");
$sql = "INSERT INTO news (title, link, description, source, pub_date) VALUES (?, ?, ?, ?, ?)";
$stmt = $db->prepare($sql);

$items = $feed->get_items();

foreach ($items as $item) {
    $stmt->execute([
        $item->get_title(),
        $item->get_link(),
        $item->get_description(),
        $feed->get_link(),
        $item->get_date("Y-m-d H:i:s"),
    ]);
}
