<?php
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str);
$questionList = $json_obj->questionList;
$questionIdClear = [];
$sql = "";

$id_respondent = 1;
foreach($questionList as $question){
    $questionIdClear = preg_replace('/[^a-zA-Z0-9]/', '',$question->id);
    $questionAnswer = preg_replace('/[^a-zA-Z0-9]/', '',$question->text);
    $sql = "INSERT INTO answer
    (answer,
    id_question,
    id_respondent)
    VALUES
    ('$questionAnswer',
    $questionIdClear,
    $id_respondent);";
}

$conn = new mysqli($server, $username, $password, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>