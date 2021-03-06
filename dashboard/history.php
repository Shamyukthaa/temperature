<?php

include "../config.php";

$sensor = (isset($_REQUEST["sensor"]))?(int)$_REQUEST["sensor"]:$DEFAULT_SENSOR_ID;

$period = (isset($_REQUEST["period"]))?(int)$_REQUEST["period"]:$DEFAULT_HISTORY_PERIOD;
if ($period <= 0) $period = 24;
if ($period > 24*365*2) $period = 24;

$link = mysqli_connect($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASSWORD, $MYSQL_DB);

$q = mysqli_query($link, "SELECT UNIX_TIMESTAMP(`date`) AS `date`, ROUND(`value` / 1000, 1) AS `value` FROM `history` WHERE `date` >= DATE_ADD(NOW(), INTERVAL -".(int)$period." HOUR) AND sensor = ".(int)$sensor." ORDER BY `date` ASC");

$data = array();
while ($a = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
	$data[] = array((int)$a["date"], (float)$a["value"]);
}

mysqli_close($link);

header("content-type: application/json");
echo json_encode(array($data));

