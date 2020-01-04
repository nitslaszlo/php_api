<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

// A PHP backend ezzel a "trükkel" tudja a JSON típusú tartalmat fogadni:
$headers = getallheaders();
if (array_key_exists('Content-Type', $headers)) {
	$ctheader = $headers["Content-Type"];
	if (strpos($ctheader, 'json') !== false) {
	   $_POST = json_decode(file_get_contents("php://input"), true) ?: [];
	}
}
// trükk vége

$res = array('error' => false);

$action = ''; 

if (isset($_GET['action'])) { 
	$action = $_GET['action']; // get action value
}

if (empty($_POST) && $action == 'insert') {
	die();
}

// getValami kérés feldolgozása:
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $action == 'getValami') {
	$res['valami'] = "valami adat";
	$res['message'] = "Get successfully!";
	echo(json_encode($res, JSON_UNESCAPED_UNICODE));
 	die();
}
// feldolgozás vége


// Mentés adatbázisba (POST típusú kérés feldolgozása)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = NULL;

try {
	$conn = new mysqli("localhost", "root", "", "mintaAdatbazis");
} catch (mysqli_sql_exception $e) {
	$res['error'] = true;
	$msg = $e->getMessage();
	$res['message'] = utf8_encode("Database connection established failed! {$msg}");
	echo(json_encode($res, JSON_UNESCAPED_UNICODE));
	die();
}

// Change character set to utf8: (don't throw exception)
if ($conn->set_charset("utf8")) {
	$res['charset'] = $conn->character_set_name();
} else {
	$res['error'] = true;
	$msg = $conn->error;
	$res['message'] = "Change character set to utf8 failed! {$msg}";
	echo(json_encode($res, JSON_UNESCAPED_UNICODE));
	die();
}

$conn->query("SET GLOBAL sql_mode='STRICT_ALL_TABLES', SESSION sql_mode='STRICT_ALL_TABLES'");

// Minta tábla összes adatának kiolvasása (csak példa, api.js nem hívja, teszteléshez pl.: Postman jól jön)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $action == 'read') {
	try {
		$result = $conn->query("SELECT * FROM mintaTabla");
		$seged = array();
		$seged = mysqli_fetch_all($result, MYSQLI_ASSOC);
		$res['result'] = $seged;
		$res['message'] = "mintaTabla read successfully!";
	} catch (Exception $e) {
		$res['error'] = true;
		$msg = $e->getMessage();
		$res['message'] = "mintaTabla read failed! {$msg}";
	}
}

// insert feldolgozása:
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $action == 'insert') {
	$mezo1 = $_POST['mezo1'];
	$mezo2 = $_POST['mezo2'];
	$mezo3= $_POST['mezo3'];

	$prepared = $conn -> prepare("INSERT INTO mintaTabla (`mezo1`, `mezo2`, `mezo3`) VALUES ( ?, ?, ?)");
	if ($prepared == false) die("Error in create (prepare)");
	$result = $prepared->bind_param('sss', $mezo1, $mezo2, $mezo3);
	if ($result == false) die("Error in create (bind)");
	
	try {
		$prepared->execute();
		$res['message'] = "Record added successfully!";
	} catch (Exception $e) {
		$res['error'] = true;
		$msg = $e->getMessage();
		$res['message'] = "Record added failed! {$msg}";
	}
	$prepared -> close();
}

echo(json_encode($res, JSON_UNESCAPED_UNICODE));

$conn -> close();

die();

?>