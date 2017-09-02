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

if (isset($_POST['name']) && (isset($_POST['email'])) && (isset($_POST['attendees']))) {
	$email = sanitize_sql_string($_POST['email']);
	$name = sanitize_sql_string($_POST['name']);
	$attendees = $_POST['attendees'];
	$errors = '';
	if (is_numeric($attendees)) {
		$attendees = sanitize_int($attendees);
	} else {
		$errors .= 'Number of attendees is not numeric. ';
	}
	if (strlen($name) > 60) {
		$errors .= 'Name is too long. ';
	}
	if (strlen($name) < 2) {
		$errors .= 'Name is too short. ';
	}
	if (strlen($email) > 100) {
		$errors .= 'Email is too long. ';
	}
	if (strlen($email) < 5) {
		$errors .= 'Email is too short. ';
	}
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$errors .= 'Email is not valid. ';
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
		echo '<p><strong> Your Addition to the RSVP list could not be processed at this time. '
			. 'Please try again in a few minutes.</strong></p>';
		exit;
	}

	fwrite($fp, 'rsvp {"name": "'.$name.'", "email": "'.$email.'", "attendees": '.$attendees.'}');
	flock($fp, 3);
	fclose($fp);

	$sql1 = "INSERT INTO iandk_rsvp(name, email, attendees, created, updated) VALUES ('" . $name . "', '" . $email .
		"', ". $attendees . ", NOW(), NOW()) ON DUPLICATE KEY UPDATE updated=NOW(), attendees=". $attendees .
		", name='" . $name . "'";

	$result1 = mysqli_query($conn, $sql1) or die ("Unable to save RSVP values of " . $email . ", " . $name . " and "
		. $attendees);
}
?>
