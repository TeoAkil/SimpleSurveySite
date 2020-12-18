<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
//we'll put this at the top so both php block have access to it
if (isset($_GET["id"])) {
    $id = $_GET["id"];
}
?>
<?php
$db = getDB();
$stmt = $db->prepare("SELECT id,question from Questions");
$r = $stmt->execute();
$surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <h3>Create Answers</h3>
    <form method="POST">
        <label>Answers</label>
        <input name="answer1" placeholder="First Answer"/>
        <input name="answer2" placeholder="Second Answer"/>        
        <input type="submit" name="save" value="Create"/>
    </form>
    
<?php
if (isset($_POST["save"])) {
    //TODO add proper validation/checks
    $answer1 = $_POST["answer1"];
    $answer2 = $_POST["answer2"];
   // $survey_id = $_POST["survey_id"];
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Answers (answer, question_id) VALUES(:answer1, :question_id),(:answer2, :question_id)");
    $r = $stmt->execute([
        ":answer1" => $answer1,
        ":answer2" => $answer2,
        ":question_id" => $id
    ]);
    if ($r) {
        flash("Created successfully with id: " . $db->lastInsertId());
	die(header("Location: test_list_your_survey2.php"));
    }
    else {
        $e = $stmt->errorInfo();
        flash("Error creating: " . var_export($e, true));
    }
}
?>
<?php require(__DIR__ . "/partials/flash.php");
