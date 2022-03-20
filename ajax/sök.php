<?php declare(strict_types = 1);

isset($_REQUEST['term']) or die();
$term = htmlspecialchars($_REQUEST['term']);
$sökvärden = [];

require_once dirname($_SERVER['SCRIPT_FILENAME']) . '/../klasser/Preludium.php';
$db = new Databas;
$sats = $db->livs->prepare('SELECT `id`, `namn` FROM `livs` WHERE `namn` LIKE :term LIMIT 50');
$sats->bindValue(':term', "%$term%", PDO::PARAM_STR);
$sats->execute();
while ($r = $sats->fetch(PDO::FETCH_ASSOC)) {
	$r['namn'] = htmlspecialchars($r['namn']);
	$sökvärden[] = ['value' => $r['id'], 'label' => $r['namn']];
}

echo json_encode($sökvärden);
