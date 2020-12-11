<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
//
?>
<?php
$sessionid = get_user_id();
$results = [];
$db = getDB();
$stmt = $db->prepare("SELECT id,title,description,visibility,created,modified,total,user_id from Survey WHERE user_id like :sid LIMIT 10");
$r = $stmt->execute([":sid" => "$sessionid"]);
if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
else {
        flash("There was a problem fetching the results");
    }
?>
<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $r): ?>
                <div class="list-group-item">
                    <div>
                        <div>Title:</div>
                        <div><?php safer_echo($r["title"]); ?></div>
                    </div>
                    <div>
                        <div>Description:</div>
                        <div><?php safer_echo($r["description"]); ?></div>
                    </div>
                    <div>
                        <div>Visibility:</div>
                        <div><?php getState($r["visibility"]); ?></div>
                    </div>
                    <div>
                        <div>Created:</div>
                        <div><?php safer_echo($r["created"]); ?></div>   
                    </div>
                    <div>
                        <div>Modified:</div>
                        <div><?php safer_echo($r["modified"]); ?></div>   
                    </div>
		    <div>
			<div>Times Taken: </div>
			<div><?php safer_echo($r["total"]); ?></div>
                    <div>
                        <div>Owner Id:</div>
                        <div><?php safer_echo($r["user_id"]); ?></div>
                    </div>
                    <div>
                        <a type="button" href="test_edit_survey.php?id=<?php safer_echo($r['id']); ?>">Edit Survey Info</a>
                        <a type="button" href="test_view_survey.php?id=<?php safer_echo($r['id']); ?>">View Survey Info</a>
			<a type="button" href="test_create_questions.php?id=<?php safer_echo($r['id']); ?>">Add Question</a>
			<a type="button" href="test_list_questions.php?id=<?php safer_echo($r['id']); ?>">View/Edit Questions</a>
			<a type="button" href="test_results.php?id=<?php safer_echo($r['id']); ?>">Results Page </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>
