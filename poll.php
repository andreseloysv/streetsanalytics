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
<div class="container">

<h4 class="header">Streets Analytics Umfrage</h4>
<br>
<form action="#" id="poll">
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
<br>
<button class="btn waves-effect waves-light blue lighten-2" type="submit" name="action">Speichern
    <i class="material-icons right">send</i>
  </button>
  </form>
  </div>
  </body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
<script>
function postData(url, data) {
  // Default options are marked with *
  return fetch(url, {
    body: JSON.stringify(data), // must match 'Content-Type' header
    cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
    credentials: 'same-origin', // include, same-origin, *omit
    headers: {
      'user-agent': 'Mozilla/4.0 MDN Example',
      'content-type': 'application/json'
    },
    method: 'POST', // *GET, POST, PUT, DELETE, etc.
    mode: 'cors', // no-cors, cors, *same-origin
    redirect: 'follow', // manual, *follow, error
    referrer: 'no-referrer', // *client, no-referrer
  })
  .then(response => response.json()) // parses response to JSON
}
window.addEventListener("load", function () {
  const form = document.getElementById("poll");
  let questionList = [];
  
  function sendData() {
    
    const inputCheckboxs = document.querySelectorAll("input[type='checkbox']:checked");

    for (let i = 0, len = inputCheckboxs.length; i < len; i++) {
        questionList.push(inputCheckboxs[i].value;
    }
    
    postData("save_poll.php", {questionList:JSON.stringify(questionList)}).then( (response) => { 
        console.log(response);
    }).catch(error => console.error(error));
  }
  
  form.addEventListener("submit", function (event) {
    event.preventDefault();
    console.log("sending data...");
    sendData();
  });
});

</script>
</html>