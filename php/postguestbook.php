<?php
/**
 * User: kevinboutin
 * Date: 8/12/17
 * Time: 1:54 PM
 */
include '../includes/dbconfig.php';
include '../includes/sanitize.php';

// Create connection
$conn = mysqli_connect($IK_DB['host'], $IK_DB['user'], $IK_DB['pass'], $IK_DB['dbName']);

// Check connection
if (!$conn) {
	die('Connection failed: ' . mysqli_connect_error());
}

if (isset($_POST['name']) && (isset($_POST['message']))) {
	$message = sanitize_sql_string($_POST['message']);
	$name = sanitize_sql_string($_POST['name']);
	$errors = '';
	if (strlen($name) > 60) {
		$errors .= 'Name is too long. ';
	}
	if (strlen($name) < 2) {
		$errors .= 'Name is too short. ';
	}
	if (strlen($message) > 2024) {
		$errors .= 'Message is too long. ';
	}

	if (empty($errors)) {
		echo '{"status": "success"}';
	} else {
		echo '{"status": "error", "errors": "' . $errors . '"}';
		exit;
	}

	// open file for appending
	@ $fp = fopen('list.txt', 'a');

	flock($fp, 2);

	if (!$fp)
	{
		echo '<p><strong> Your Addition to the Guestbook could not be processed at this time. '
			. 'Please try again in a few minutes.</strong></p>';
		exit;
	}

	fwrite($fp, 'wishes {"name": "'.$name.'", "message": "'.$message.'"}'. PHP_EOL);
	flock($fp, 3);
	fclose($fp);

	$sql1 = "INSERT INTO iandk_wishes(name, platform, message, created, updated) VALUES ('" . $name . "', 'web', '".
		$message . "', NOW(), NOW())";

	$result1 = mysqli_query($conn, $sql1) or die ("Unable to save wish values of " . $message . " and " . $name);
}
?>
