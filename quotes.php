<?php

header("Access-Control-Allow-Origin: http://localhost:3000");

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quotes";

$conn = new mysqli($servername, $username, $password, $dbname);

if (!$conn) {
    // die("Database not connected" . mysqli_connect_error);
}


$content_type = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($content_type === "application/json-add-quote") {
    $content = trim(file_get_contents("php://input"));
    $decode = json_decode($content, true);
    $author = mysqli_escape_string($conn, $decode["author"]);
    $text = mysqli_escape_string($conn, $decode["text"]);

    $sql = "INSERT INTO quote (`author`, `text`) VALUES ('" . $author . "', '" . $text . "')";
    $result = mysqli_query($conn, $sql);
} else if ($content_type === "application/json-getAllQuotes") {
    $quotes = array();
    $sql = "SELECT * FROM quote";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($quotes, $row);
    }

    echo json_encode($quotes);
} else if ($content_type === "application/json-single-quote") {
    $content = trim(file_get_contents("php://input"));
    $decode = json_decode($content, true);

    $id = $decode;
    $sql = "SELECT * FROM quote WHERE id = " . $id;
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    echo json_encode($row);
} else if ($content_type === "application/json-add-comment") {
    $content = trim(file_get_contents("php://input"));
    $decode = json_decode($content, true);

    $text = mysqli_escape_string($conn, $decode["text"]);
    $quoteId = $decode["quoteId"];
    $sql = "INSERT INTO comments (`quote_id`, `text`) VALUES (" . $quoteId . ", '" . $text . "')";
    $result = mysqli_query($conn, $sql);
} else if ($content_type = "application/json-show-comments") {
    $content = trim(file_get_contents("php://input"));
    $decode = json_decode($content, true);

    $sql = "SELECT * FROM comments WHERE quote_id = " . $decode;
    $result = mysqli_query($conn, $sql);
    $comments = array();
    while ($row = mysqli_fetch_array($result)) {
        array_push($comments, $row);
    }
    echo json_encode($comments);
}
