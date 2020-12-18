<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//we'll put this at the top so both php block have access to it
if (isset($_GET["id"])) {
    $id = $_GET["id"];
}
?>
<?php
//fetching
$result = [];
if (isset($id)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT u.id,u.username,u.pubchoice,u.email FROM Users u where u.id = :id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
       // $e = $stmt->errorInfo();
       // flash($e[2]);
    }
}
?>
<?php if (isset($result) && !empty($result)): ?>
    <div class="card">
        <div class="card-title">
        <div>Username:    <?php safer_echo($result["username"]); ?>
        </div>
        <div class="card-body">
	    <?php if ($result["pubchoice"] != '0'): ?>
            <div>
                <p>Information</p>
                <div>Email: <?php safer_echo($result["email"]); ?></div>
            </div>
	    <?php else: ?>
	    <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <p>Error looking up id...</p>
<?php endif; ?>
<?php
$page = 1;
$per_page = 10;
if(isset($_GET["page"])){
    try {
        $page = (int)$_GET["page"];
    }
    catch(Exception $e){

    }
}
$db = getDB();
$stmt = $db->prepare("SELECT count(*) as total from Survey s where s.user_id = :id");
$stmt->execute([":id"=>get_user_id()]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$total = 0;
if($result){
    $total = (int)$result["total"];
}
$total_pages = ceil($total / $per_page);
$offset = ($page-1) * $per_page;
$stmt = $db->prepare("SELECT s.* from Survey s where s.visibility = 2 and s.user_id = :id ORDER BY id DESC LIMIT :offset, :count");
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
$stmt->bindValue(":id", $id);
$stmt->execute();
$e = $stmt->errorInfo();
if($e[0] != "00000"){
    flash(var_export($e, true), "alert");
}
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <div class="container-fluid">
    <h3>Surveys They Made</h3>
    <div class="row">
    <div class="card-group">
<?php if($results && count($results) > 0):?>
    <?php foreach($results as $r):?>
        <div class="col-auto mb-3">
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <div class="card-title">
                        <div>Title: <?php safer_echo($r["title"]);?></div>
                    </div>
                    <div class="card-text">
                        <div>Description: <?php safer_echo($r["description"]); ?></div>
                    </div>
                    <div class="card-footer">
                        <div>Times Taken: <?php safer_echo($r["total"]); ?></div>
                    </div>
	      	    <div>
                        <a type="button" href="test_edit_survey.php?id=<?php safer_echo($r['id']); ?>">Edit Survey Info</a>
                        <a type="button" href="test_view_survey.php?id=<?php safer_echo($r['id']); ?>">View Survey Info</a>
			<a type="button" href="test_create_questions.php?id=<?php safer_echo($r['id']); ?>">Add Question</a>
			<a type="button" href="test_list_questions.php?id=<?php safer_echo($r['id']); ?>">View/Edit Questions</a>
			<a type="button" href="test_results.php?id=<?php safer_echo($r['id']); ?>">Results Page </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach;?>

<?php else:?>
<div class="col-auto">
    <div class="card">
       You don't have any taken surveys.
    </div>
</div>
<?php endif;?>
    </div>
    </div>
        <nav aria-label="Taken Surveys">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($page-1) < 1?"disabled":"";?>">
                    <a class="page-link" href="?page=<?php echo $page-1;?>" tabindex="-1">Previous</a>
                </li>
                <?php for($i = 0; $i < $total_pages; $i++):?>
                <li class="page-item <?php echo ($page-1) == $i?"active":"";?>"><a class="page-link" href="?page=<?php echo ($i+1);?>"><?php echo ($i+1);?></a></li>
                <?php endfor; ?>
                <li class="page-item <?php echo ($page) >= $total_pages?"disabled":"";?>">
                    <a class="page-link" href="?page=<?php echo $page+1;?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
<?php
$page = 1;
$per_page = 10;
if(isset($_GET["page"])){
    try {
        $page = (int)$_GET["page"];
    }
    catch(Exception $e){

    }
}
$db = getDB();
$stmt = $db->prepare("SELECT count(*) as total from Survey s join Responses r on s.id = r.survey_id where r.user_id = :id");
$stmt->execute([":id"=>get_user_id()]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$total = 0;
if($result){
    $total = (int)$result["total"];
}
$total_pages = ceil($total / $per_page);
$offset = ($page-1) * $per_page;
$stmt = $db->prepare("SELECT DISTINCT s.* from Survey s join Responses r on s.id = r.survey_id where r.user_id = :id and s.visibility = 2 ORDER BY id DESC LIMIT :offset, :count");
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
$stmt->bindValue(":id", $id);
$stmt->execute();
$e = $stmt->errorInfo();
if($e[0] != "00000"){
    flash(var_export($e, true), "alert");
}
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <div class="container-fluid">
    <h3>Surveys They Took</h3>
    <div class="row">
    <div class="card-group">
<?php if($results && count($results) > 0):?>
    <?php foreach($results as $r):?>
        <div class="col-auto mb-3">
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <div class="card-title">
                        <div>Title: <?php safer_echo($r["title"]);?></div>
                    </div>
                    <div class="card-text">
                        <div>Description: <?php safer_echo($r["description"]); ?></div>
                    </div>
                    <div class="card-footer">
                        <div>Times Taken: <?php safer_echo($r["total"]); ?></div>
                    </div>
	      	    <div>
                        <a type="button" href="test_edit_survey.php?id=<?php safer_echo($r['id']); ?>">Edit Survey Info</a>
                        <a type="button" href="test_view_survey.php?id=<?php safer_echo($r['id']); ?>">View Survey Info</a>
			<?php if (has_role("Admin")): ?>
			<a type="button" href="test_create_questions.php?id=<?php safer_echo($r['id']); ?>">Add Question</a>
			<a type="button" href="test_list_questions.php?id=<?php safer_echo($r['id']); ?>">View/Edit Questions</a>
			<?php else: ?>
			<?php endif; ?>
			<a type="button" href="test_results.php?id=<?php safer_echo($r['id']); ?>">Results Page </a>
			<a type="button" href="test_view_profile.php?id=<?php safer_echo($r['user_id']); ?>"> View Profile </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach;?>

<?php else:?>
<div class="col-auto">
    <div class="card">
       They havent taken any surveys.
    </div>
</div>
<?php endif;?>
    </div>
    </div>
        <nav aria-label="Taken Surveys">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($page-1) < 1?"disabled":"";?>">
                    <a class="page-link" href="?page=<?php echo $page-1;?>" tabindex="-1">Previous</a>
                </li>
                <?php for($i = 0; $i < $total_pages; $i++):?>
                <li class="page-item <?php echo ($page-1) == $i?"active":"";?>"><a class="page-link" href="?page=<?php echo ($i+1);?>"><?php echo ($i+1);?></a></li>
                <?php endfor; ?>
                <li class="page-item <?php echo ($page) >= $total_pages?"disabled":"";?>">
                    <a class="page-link" href="?page=<?php echo $page+1;?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
<?php require(__DIR__ . "/partials/flash.php");
