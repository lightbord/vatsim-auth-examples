<?php

// VATSIM Authentication Demo by Matt Binkowitz (liteborder/litebord/lightbord)
// VATSIM Authentication callback page
// Currently using VATSIM auth-dev servers. To change this, change all occurences of "auth-dev.vatsim.net" to "auth.vatsim.net"

require_once('secrets.inc');

session_start();

if (isset($_GET['code'])) {
	$url = 'https://auth-dev.vatsim.net/oauth/token';
	$data = array('grant_type' => 'authorization_code', 'client_id' => $client_id, 'client_secret' => $secret, 'redirect_uri' => $callback_url, 'code' => $_GET['code']);

	// use key 'http' even if you send the request to https://...
	$options = array(
		'http' => array(
			// 'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	if ($result === FALSE) { /* Handle error */ }

	$decoded = json_decode($result);

	$_SESSION['vatsim_access_token'] = $decoded->access_token;

	header("Location: /");

}else{
	die("No code was recieved from VATSIM servers");
}


?>