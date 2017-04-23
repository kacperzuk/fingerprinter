<?php

header("Content-Type: text/plain");

ini_set("display_errors", true);
error_reporting(-1);

$dbopts = parse_url(getenv('DATABASE_URL'));
$dbConn = new PDO('pgsql:dbname='.ltrim($dbopts["path"],'/').';host='.$dbopts["host"] . ';port=' . $dbopts["port"], $dbopts["user"], $dbopts["pass"]);
$dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$out = fopen('php://output', 'w');
$q = $dbConn->query("select * from data;");
foreach($q as $row) {
    fputcsv($out, $row);
}
fclose($out);
