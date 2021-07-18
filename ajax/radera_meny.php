<?php declare(strict_types = 1);

isset($_REQUEST['tabellid']) or die("ID Saknas.");
require_once dirname($_SERVER['SCRIPT_FILENAME']) . '/../klasser/Preludium.php';
$db = new Databas;

$menyid = filter_var($_POST['tabellid'], FILTER_VALIDATE_INT);
$menyid === FALSE and die("Databasfel.");

// foreign keys vore elegantare
$sats = $db->livs->prepare("DELETE FROM `meny` WHERE `menyid`=:menyid");
$sats->bindValue(':menyid', $menyid, PDO::PARAM_INT);
$sats->execute();

$sats = $db->livs->prepare("DELETE FROM `mål` WHERE `menyid`=:menyid");
$sats->bindValue(':menyid', $menyid, PDO::PARAM_INT);
$sats->execute();

$sats = $db->livs->prepare("DELETE FROM `ingrediens` WHERE `menyid`=:menyid");
$sats->bindValue(':menyid', $menyid, PDO::PARAM_INT);
$sats->execute();
