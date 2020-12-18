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
//fetching
$result = [];
if (isset($id)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT Surv.id,title,description,visibility,Surv.created,modified,total,user_id, Users.username FROM Survey as Surv JOIN Users on Surv.user_id = Users.id where Surv.id = :id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }
}
?>
<?php
$userid = get_user_id();
if (($userid != $result["user_id"]) && ($result["visibility"] == 0) && (!has_role("Admin"))) {
    //this will redirect to home and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page, it is a different users survey draft");
    die(header("Location: home.php"));
}
?>
<?php if (isset($result) && !empty($result)): ?>
    <div class="card">
        <div class="card-title">
            <?php safer_echo($result["title"]); ?>
        </div>
        <div class="card-body">
            <div>
                <p>Stats</p>
                <div>Description: <?php safer_echo($result["description"]); ?></div>
                <div>Visibility: <?php getState($result["visibility"]); ?></div>
                <div>Created: <?php safer_echo($result["created"]); ?></div>
                <div>Modified: <?php safer_echo($result["modified"]); ?> </div>
		<div>Times Taken: <?php safer_echo($result["total"]); ?> </div>
                <div>Owned by: <?php safer_echo($result["username"]); ?></div>
            </div>
        </div>
    </div>
<?php else: ?>
    <p>Error looking up id...</p>
<?php endif; ?>
<?php require(__DIR__ . "/partials/flash.php");
