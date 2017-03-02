<?php

$db = new PDO("mysql:host=localhost;dbname=rss_news;charset=utf8", "root", "123");
$sql = "SELECT * FROM news ORDER BY pub_date DESC";
$stmt = $db->prepare($sql);
$stmt->execute();

echo '<pre>';
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
echo '</pre>';
