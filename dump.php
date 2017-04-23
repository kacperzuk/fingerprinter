<?php

header("Content-Type: text/plain");
header('Content-Disposition: attachment; filename="fingerprinter_data.csv"');

$dbopts = parse_url(getenv('DATABASE_URL'));
$dbConn = new PDO('pgsql:dbname='.ltrim($dbopts["path"],'/').';host='.$dbopts["host"] . ';port=' . $dbopts["port"], $dbopts["user"], $dbopts["pass"]);
$dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$out = fopen('php://output', 'w');
$rs = $dbConn->query('SELECT * FROM my_table LIMIT 0');
for ($i = 0; $i < $rs->columnCount(); $i++) {
    $col = $rs->getColumnMeta($i);
    $columns[] = $col['name'];
}
fputcsv($out, $columns);

$q = $dbConn->query("select * from data;");
foreach($q as $row) {
    fputcsv($out, $row);
}
fclose($out);
