<?php

header("Access-Control-Allow-Origin: *");

if ($_SERVER["REQUEST_METHOD"] != "POST") die();

ini_set("display_errors", true);
error_reporting(-1);

$dbopts = parse_url(getenv('DATABASE_URL'));
$dbConn = new PDO('pgsql:dbname='.ltrim($dbopts["path"],'/').';host='.$dbopts["host"] . ';port=' . $dbopts["port"], $dbopts["user"], $dbopts["pass"]);
$dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$dcols = array(
    "env_name",
    "ext_name",
    "hash",
    "ua",
    "language",
    "screen_fingerprint",
    "plugins",
    "fonts",
    "timezone",
    "canvas_fingerprint",
    "webgl_fingerprint",
    "hardware_concurrency",
    "f2js_fingerprint",
    "f2js_user_agent",
    "f2js_language",
    "f2js_color_depth",
    "f2js_pixel_ratio",
    "f2js_hardware_concurrency",
    "f2js_resolution",
    "f2js_available_resolution",
    "f2js_timezone_offset",
    "f2js_session_storage",
    "f2js_local_storage",
    "f2js_indexed_db",
    "f2js_open_database",
    "f2js_cpu_class",
    "f2js_do_not_track",
    "f2js_regular_plugins",
    "f2js_canvas",
    "f2js_webgl",
    "f2js_adblock",
    "f2js_has_lied_languages",
    "f2js_has_lied_resolution",
    "f2js_has_lied_os",
    "f2js_has_lied_browser",
    "f2js_touch_support",
    "f2js_js_fonts",
    "clientjs_fingerprint",
    "clientjs_ua",
    "clientjs_screen_info",
    "clientjs_plugins",
    "clientjs_mime_types",
    "clientjs_fonts",
    "clientjs_timezone",
    "clientjs_language",
    "clientjs_canvas_fingerprint"
);

$data = json_decode(file_get_contents('php://input'), true);
$prepared_data = array(
    "env_name" => $data["env_name"],
    "ext_name" => $data["ext_name"]
);
foreach($data["best"] as $k => $v) {
    $k = strtolower(str_replace(" ", "_", $k));
    $prepared_data[$k] = $v;
}
foreach($data["clientjs"] as $k => $v) {
    $k = "clientjs_".strtolower(str_replace(" ", "_", $k));
    $prepared_data[$k] = $v;
}
foreach($data["fingerprint2"] as $k => $v) {
    $k = "f2js_".strtolower(str_replace(" ", "_", $k));
    $prepared_data[$k] = $v;
}

$cols = implode(",", $dcols);
$vals = implode(",", array_fill(0, count($cols), '?'));
$q = "INSERT INTO data (ip, $cols) VALUES (?, $vals)";
var_dump($q); die();
$q = $dbConn->prepare($q);
$values = array();
foreach($dcols as $col) {
    $values[] = $prepared_data[$col];
}
$q->execute($values);
echo "OK";
