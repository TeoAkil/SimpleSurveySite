<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
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
$results = [];
$db = getDB();
$stmt = $db->prepare("SELECT id,question,survey_id from Questions WHERE survey_id like :sid LIMIT 10");
$r = $stmt->execute([":sid" => "$id"]);
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
                        <div>Question:</div>
                        <div><?php safer_echo($r["question"]); ?></div>
                    </div>
                    <div>
                        <div>Survey Id:</div>
                        <div><?php safer_echo($r["survey_id"]); ?></div>
                    </div>
                    <div>
			<a type="button" href="test_create_answers.php?id=<?php safer_echo($r['id']); ?>">Add Answer</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>
