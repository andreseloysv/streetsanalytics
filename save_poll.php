<?php
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);


var_dump($_POST);
echo("hola");
exit();
$questionList = preg_replace('/[^a-zA-Z0-9]/', '', $_POST["questionList"]);

$answer = preg_replace('/[^a-zA-Z0-9]/', '', $_POST["answer"]);
$question_id = preg_replace('/[^a-zA-Z0-9]/', '', $_POST["question_id"]);
$id_respondent = preg_replace('/[^a-zA-Z0-9]/', '', $_POST["id_respondent"]);

$conn = new mysqli($server, $username, $password, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


$sql = "INSERT INTO answer
(answer,
id_question,
id_respondent,)
VALUES
($answer,
$question_id,
$id_respondent);";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>