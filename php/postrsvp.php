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

	if (!empty($errors)) {
		echo '{"status": "error", "errors": "' . $errors . '"}';
		exit;
	}

	// open file for appending
	@ $fp = fopen('list.txt', 'a');

	flock($fp, 2);

	if (!$fp)
	{
		echo '{"status": "error", "errors": "Your Addition to the RSVP list could not be processed at this time. '
			. 'Please try again in a few minutes."}';
		exit;
	}

	fwrite($fp, 'rsvp {"name": "'.$name.'", "email": "'.$email.'", "attendees": '.$attendees.'}'. PHP_EOL);
	flock($fp, 3);
	fclose($fp);

	$sql1 = "INSERT INTO iandk_rsvp(name, email, attendees, created, updated) VALUES ('" . $name . "', '" . $email .
		"', ". $attendees . ", NOW(), NOW()) ON DUPLICATE KEY UPDATE updated=NOW(), attendees=". $attendees .
		", name='" . $name . "'";

	$result1 = mysqli_query($conn, $sql1) or die ("Unable to save RSVP values of " . $email . ", " . $name . " and "
		. $attendees);

	$headers = 'MIME-Version: 1.0'."\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
	$headers .= 'From: kevin@ivanaandkevin.com'."\r\n";
	$headers .= 'Reply-To: kevin@ivanaandkevin.com';
	$body = '<html><body>Hello ' . $name . '.<br/><br/>Thank you for your RSVP of 1 person. ';
	if ($attendees > 1) {
		$body = 'Hello ' . $name . '.<br/><br/>Thank you for your RSVP of ' . $attendees . ' persons. ';
	}
	$body .= '<br/><br/>You can resubmit another RSVP if you need to change the number of people.<br/><br/>Thank you '
		. 'for your interest and we appreciate your attendance on our special day. '
		. '<br/><br/>-Ivana and Kevin<br/><br/>kevin@ivanaandkevin.com</body></html>';
	$success = mail($email, 'Your RSVP to the Ivana Massud and Kevin Boutin wedding', $body, $headers);
	echo '{"status": "success", "message": ' . $success . '}';
}
?>
