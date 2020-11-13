<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>

<?php
$db = getDB();
$stmt = $db->prepare("SELECT id,title from Survey");
$r = $stmt->execute();
$surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <h3>Create Question</h3>
    <form method="POST">
        <label>Question</label>
        <input name="question" placeholder="Question"/>
        <select name="survey_id" value="<?php echo $result["survey_id"];?>">
            <option value="-1">None</option>
            <?php foreach ($surveys as $survey): ?>
                <option value="<?php safer_echo($survey["id"]); ?>"
                ><?php safer_echo($survey["title"]); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" name="save" value="Create"/>
    </form>
    
<?php
if (isset($_POST["save"])) {
    //TODO add proper validation/checks
    $question = $_POST["question"];
    $survey_id = $_POST["survey_id"];
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Questions (question, survey_id) VALUES(:question, :survey_id)");
    $r = $stmt->execute([
        ":question" => $question,
        ":survey_id" => $survey_id
    ]);
    if ($r) {
        flash("Created successfully with id: " . $db->lastInsertId());
    }
    else {
        $e = $stmt->errorInfo();
        flash("Error creating: " . var_export($e, true));
    }
}
?>
<?php require(__DIR__ . "/partials/flash.php");
