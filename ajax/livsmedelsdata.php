<?php declare(strict_types = 1);

isset($_REQUEST['id'], $_REQUEST['sista'], $_REQUEST['mängd']) or exit();

$målid = (string) filter_var($_REQUEST['målid'], FILTER_SANITIZE_SPECIAL_CHARS);
ctype_alnum($målid) or exit();

$id = (int) filter_var($_REQUEST['id'], FILTER_VALIDATE_INT);
$sista = filter_var($_REQUEST['sista'], FILTER_VALIDATE_INT);
$mängd = filter_var((string) $_REQUEST['mängd'], FILTER_VALIDATE_FLOAT);
if ($mängd === FALSE || $mängd == 0) $mängd = 100.0;

require_once dirname($_SERVER['SCRIPT_FILENAME']) . '/../klasser/Preludium.php';
$livs = new Livs;

$html = $livs->hämta_ingrediens($id, $målid, $mängd, ' ny');
$sista !== 0 and $html .= $livs->ingrediensmall($målid);

echo json_encode([$id, $html]);
