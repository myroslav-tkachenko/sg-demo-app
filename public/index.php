<?php

$db = new PDO("mysql:host=localhost;dbname=rss_news;charset=utf8", "root", "123");
$sql = "SELECT * FROM news ORDER BY pub_date DESC";
$stmt = $db->prepare($sql);
$stmt->execute();

$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once '../templates/index.tpl.php';
