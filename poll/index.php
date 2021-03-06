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
        <h5>Person</h5>
        <p>
            <label>
                <input class="with-gap" name="age" type="radio" checked value="18 - 30" />
                <span>18 - 30</span>
            </label>
            <label>
                <input class="with-gap" name="age" type="radio" value="30 - 45"/>
                <span>30 - 45</span>
            </label>
            <label>
                <input class="with-gap" name="age" type="radio" value="45 - 65"/>
                <span>45 - 65</span>
            </label>
        </p>
        <p>
            <label>
                <input class="with-gap" name="gender" type="radio" value="frau" checked />
                <span>Frau</span>
            </label>
            <label>
                <input class="with-gap" name="gender" type="radio" value="mann" />
                <span>Mann</span>
            </label>
        </p>
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
  .then(response => response).catch(error => console.log(error)) // parses response to JSON
}
window.addEventListener("load", function () {
  const form = document.getElementById("poll");
  
  function sendData() {
    let questionList = [];
    let personDetails = [];
    const inputCheckboxs = document.querySelectorAll("input[type='checkbox']");
    const inputRadio = document.querySelectorAll("input[type='radio']:checked");

    for (let i = 0, len = inputRadio.length; i < len; i++) {
        personDetails.push(inputRadio[i].value);
    }

    for (let i = 0, len = inputCheckboxs.length; i < len; i++) {
        let answer={};
        answer.id = inputCheckboxs[i].value;
        if(inputCheckboxs[i].checked){
            answer.text = "true";
            questionList.push(answer);
        }else{
            answer.text = "false";
            questionList.push(answer);
        }
    }
    
    console.log(questionList);
    postData("save_poll.php", {questionList:questionList,personDetails:personDetails}).then( (response) => {
        console.log(response);
        location.reload();
    });
  }

  form.addEventListener("submit", function (event) {
    event.preventDefault();
    console.log("sending data...");
    sendData();
  });
});

</script>
</html>