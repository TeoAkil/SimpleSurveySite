<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>


<?php
if (isset($_GET["id"])) 
{
    $sid = $_GET["id"];
//
    $db = getDB();
    $stmt = $db->prepare("SELECT q.id as QuestionId,a.id as AnswerId,s.title as SurveyTitle,s.description SurveyDescription,q.question as QuestionText,a.answer as AnswerText,(SELECT count(distinct user_id) from Responses where answer_id = a.id) as Total FROM Questions q JOIN Survey s on s.id = q.survey_id JOIN Answers a on q.id = a.question_id WHERE q.survey_id = :id group by q.id,a.id");
    $r = $stmt->execute([":id" => $sid]);
    $questions = [];
    if ($r) 
    {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
	else 
    {
        flash("There was a problem fetching the results");
    }
}
?>
<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $r): ?>
                <div class="list-group-item">
		    <div>
			<div>Survey Title: </div>
			<div><?php safer_echo($r["SurveyTitle"]); ?></div>
		    </div>
		    <div>
			<div>Survey Description: </div>
			<div><?php safer_echo($r["SurveyDescription"]); ?></div>
		    </div>
                    <div>
                        <div>Question: </div>
                        <div><?php safer_echo($r["QuestionText"]); ?></div>
                    </div>
                    <div>
                        <div>Answer: </div>
                        <div><?php safer_echo($r["AnswerText"]); ?></div>
                    </div>
                    <div>
                        <div>Total Times This Answer Was Picked: </div>
                        <div><?php safer_echo($r["Total"]); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>
