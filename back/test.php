<?php

$servername = "db";
$username = "proj_docker";
$password = "password";
$dbName = "lamp_docker";

$mysqli = new mysqli($servername, $username, $password, $dbName);

$query = 'SELECT * FROM blog';
$stmt = $mysqli->prepare($query);
$stmt->execute();

echo '<h1>MySQL Content: </h1>';
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    echo '<h2>'.$row['title'].'</h2>';
    echo '<p>'.$row['content'].'</p>';
    echo 'Posted: '.$row['date'];
    echo '<hr>';
}