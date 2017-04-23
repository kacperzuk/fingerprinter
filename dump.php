<?php

header("Content-Type: text/plain");
header("Content-Encoding: gzip");
header('Content-Disposition: attachment; filename="fingerprinter_data.csv"');
ob_start("ob_gzhandler");

$dbopts = parse_url(getenv('DATABASE_URL'));
$dbConn = new PDO('pgsql:dbname='.ltrim($dbopts["path"],'/').';host='.$dbopts["host"] . ';port=' . $dbopts["port"], $dbopts["user"], $dbopts["pass"]);
$dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$out = fopen('php://output', 'w');
$rs = $dbConn->query('select * from data limit 0');
for ($i = 0; $i < $rs->columnCount(); $i++) {
    $col = $rs->getColumnMeta($i);
    $columns[] = $col['name'];
}
fputcsv($out, $columns);

$dbConn->setFetchMode(PDO::FETCH_NUM);
$q = $dbConn->query("select * from data;");
foreach($q as $row) {
    fputcsv($out, $row);
}
fclose($out);
