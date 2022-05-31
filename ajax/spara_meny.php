<?php declare(strict_types = 1);

isset($_REQUEST['tabellid']) or die();
require_once dirname($_SERVER['SCRIPT_FILENAME']) . '/../klasser/Preludium.php';
$db = new Databas;

$menyid = filter_var($_POST['tabellid'], FILTER_VALIDATE_INT);
$menyid === FALSE and die();

$menynamn = rensa((string) filter_var($_POST['meny'], FILTER_SANITIZE_SPECIAL_CHARS));

$mål = (isset($_POST['mål'])) ? array_values((array) filter_var_array($_POST['mål'], FILTER_SANITIZE_SPECIAL_CHARS)) : [];
$data = (isset($_POST['data'])) ? array_values((array) filter_var_array($_POST['data'], FILTER_SANITIZE_SPECIAL_CHARS)) : [];

if ($menyid === 0) {
	echo "INSERT INTO `meny` (`menynamn`) VALUES (:menynamn)";
	$sats = $db->livs->prepare("INSERT INTO `meny` (`menynamn`) VALUES (:menynamn)");
	$sats->bindValue(':menynamn', $menynamn, PDO::PARAM_STR);
	$sats->execute();
	$sats = $db->livs->query("SELECT MAX(`menyid`) FROM `meny`");
	$nytt_menyid = ($sats !== FALSE) ? $sats->fetchAll()[0] : 0;
} else {
	$sats = $db->livs->prepare("REPLACE INTO `meny` (`menyid`, `menynamn`) VALUES (:menyid, :menynamn)");
	$sats->bindValue(':menyid', $menyid, PDO::PARAM_INT);
	$sats->bindValue(':menynamn', $menynamn, PDO::PARAM_STR);
	$sats->execute();
	$nytt_menyid = $menyid;

	$sats = $db->livs->prepare("DELETE FROM `mål` WHERE `menyid`=:menyid");
	$sats->bindValue(':menyid', $menyid, PDO::PARAM_INT);
	$sats->execute();

	$sats = $db->livs->prepare("DELETE FROM `ingrediens` WHERE `menyid`=:menyid");
	$sats->bindValue(':menyid', $menyid, PDO::PARAM_INT);
	$sats->execute();
}

foreach ($mål as $målid => $målnamn) {
	$sats = $db->livs->prepare("INSERT INTO `mål` (`menyid`, `målid`, `målnamn`) VALUES (:menyid, :målid, :malnamn)");
	$sats->bindValue(':menyid', $nytt_menyid, PDO::PARAM_INT);
	$sats->bindValue(':målid', $målid, PDO::PARAM_INT);
	$sats->bindValue(':malnamn', rensa($målnamn), PDO::PARAM_STR);
	$sats->execute();
}

foreach ($data as $målid => $lista) {
	foreach ($lista as $ingrediensid => $värde) {
		if (!isset($värde[1])) continue;
		[$livsmedelsid, $mängd] = explode('|', $värde);
		$sats = $db->livs->prepare("INSERT INTO `ingrediens` (`menyid`, `målid`, `ingrediensid`, `livsmedelsid`, `mängd`)
			VALUES (:menyid, :malid, :ingrediensid, :livsmedelsid, :mangd)");
		$sats->bindValue(':menyid', $nytt_menyid, PDO::PARAM_INT);
		$sats->bindValue(':malid', $målid, PDO::PARAM_INT);
		$sats->bindValue(':ingrediensid', $ingrediensid, PDO::PARAM_INT);
		$sats->bindValue(':livsmedelsid', $livsmedelsid, PDO::PARAM_INT);
		$sats->bindValue(':mangd', $mängd, PDO::PARAM_STR);
		$sats->execute();
	}
}
