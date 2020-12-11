<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
   // flash("You don't have permission to access this page");
    //die(header("Location: login.php"));
//}
//
?>
<?php
$sessionid = get_user_id();
$results = [];
$db = getDB();
$stmt = $db->prepare("SELECT DISTINCT s.title,s.description,s.id,s.total FROM Survey s JOIN Responses r on s.id = r.survey_id where r.user_id = :sid ORDER BY id DESC LIMIT 10 ");
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
			<div>Times Taken (Total) : </div>
			<div><?php safer_echo($r["total"]); ?></div>
                    <div>
                        <div>Survey Id:</div>
                        <div><?php safer_echo($r["id"]); ?></div>
                    </div>
		    <div>
			<a type="button" href="test_results.php?id=<?php safer_echo($r['id']); ?>">Results</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>
