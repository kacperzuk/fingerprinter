<?php

header("Access-Control-Allow-Origin: *");

if ($_SERVER["REQUEST_METHOD"] != "POST") die();

ini_set("display_errors", true);
error_reporting(-1);

$dbopts = parse_url(getenv('DATABASE_URL'));
$dbConn = new PDO('pgsql:dbname='.ltrim($dbopts["path"],'/').';host='.$dbopts["host"] . ';port=' . $dbopts["port"], $dbopts["user"], $dbopts["pass"]);
$dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$q = $dbConn->query("select * from data;");

echo "======\n";
$out = fopen('php://output', 'w');
foreach($q as $row) {
    fputcsv($out, $row);
}
fclose($out);
echo "=======\n";
