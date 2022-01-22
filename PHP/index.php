<?php

// VATSIM Authentication Demo by Matt Binkowitz (liteborder/litebord/lightbord)
// VATSIM Authentication main page
// Currently using VATSIM auth-dev servers. To change this, change all occurences of "auth-dev.vatsim.net" to "auth.vatsim.net"

require_once('secrets.inc');

session_start();

if (isset($_GET['action'])) {
	if ($_GET['action'] == "logout") {
		unset($_SESSION['vatsim_access_token']);
	}
}

if (!isset($_SESSION['vatsim_access_token'])) {
?>
	<html><head><title>VATSIM Auth Test</title></head><body>
		<p>You are not currently logged in. You may login using the button below.</p>
		<button id="loginbtn">Login with VATSIM</button>

		<script>
			document.getElementById('loginbtn').addEventListener('click', function(e){
				window.location.href="https://auth-dev.vatsim.net/oauth/authorize?client_id=299&redirect_uri=" + encodeURIComponent('<?php echo $callback_url; ?>') + "&response_type=code&scope=full_name+vatsim_details+email+country";
			});
		</script>
	</body></html>
<?php
}else{
	$url = 'https://auth-dev.vatsim.net/api/user';

	// use key 'http' even if you send the request to https://...
	$options = array(
		'http' => array(
			'header' => 'Accept: application/json',
			'header' => 'Authorization: Bearer ' . $_SESSION['vatsim_access_token'],
			'method'  => 'GET'
		)
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	if ($result === FALSE) { /* Handle error */ }
	
	$parsed_result = json_decode($result);

	echo '<pre>';
	var_dump($parsed_result);
	echo '</pre>';

	?>
	<a href="/index.php?action=logout">Logout</a>
<?php	
}
?>

