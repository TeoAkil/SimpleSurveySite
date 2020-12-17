<link rel="stylesheet" href="static/css/styles.css">
<?php
//we'll be including this on most/all pages so it's a good place to include anything else we want on those pages
require_once(__DIR__ . "/../lib/helpers.php");
?>
<?php
	$userid = get_user_id();
?>
<nav>
<ul class="nav">
    <li><a href="home.php">Home</a></li>
    <?php if (!is_logged_in()): ?>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
    <?php endif; ?>
    <?php if (is_logged_in()): ?>
        <li><a href="test_create_survey.php">Create Survey</a></li>
        <li><a href="test_list_survey.php">Public Surveys</a></li>
	<li><a href="test_list_your_survey2.php">Your Surveys</a></li>
	<li><a href="test_view_taken_surveys2.php"> Taken Surveys</a></li>
        <li><a href="profile.php">Edit Profile</a></li>
	<li><a href="test_view_profile.php?id=<?php safer_echo($userid); ?>"> Public Profile<a>
        <li><a href="logout.php">Logout</a></li>
    <?php endif; ?>
</ul>
</nav>
