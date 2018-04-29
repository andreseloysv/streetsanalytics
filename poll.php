<?php
$question_list = [];
class Question {
    var $id;
    var $text;
    function __construct($id, $text="")
   {
       $this->id = $id;
       $this->text = $text;
   }
}
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

$conn = new mysqli($server, $username, $password, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * FROM encuesta as e, question as q where e.id=q.encuesta_id;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        array_push($question_list,new Question($row["id_question"], $row["question"]));
    }
} else {
    echo "0 results";
}
$conn->close();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Compiled and minified CSS -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
    <title>Streets analytics</title>
</head>

<body>
<form action="#">
<?php
    foreach ($question_list as $question) {
?> 
    <p>
      <label>
        <input type="checkbox" value="<?php echo($question->id); ?>"/>
        <span><?php echo($question->text); ?></span>
      </label>
    </p>
<?php
    }
?>
<button class="btn waves-effect waves-light" type="submit" name="action">Submit
    <i class="material-icons right">Speichern</i>
  </button>
  </form>
  </body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>

</html>