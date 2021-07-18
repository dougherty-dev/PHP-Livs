<?php declare(strict_types = 1);

isset($_REQUEST['sortering'], $_REQUEST['antal'], $_REQUEST['grupper'], $_REQUEST['ag']) or exit();

require_once dirname($_SERVER['SCRIPT_FILENAME']) . '/../klasser/Preludium.php';
$livs = new Livs;

$sortering = rensa($_REQUEST['sortering']); // platshållare i PDO bara för värden
$antal = (int) filter_var($_REQUEST['antal'], FILTER_VALIDATE_INT);

$sql = "SELECT * FROM `livs` ORDER BY CAST(`$sortering` AS FLOAT) DESC LIMIT $antal";
if ($_REQUEST['ag'] === 'ag') {
	$grupp_json = json_decode($_REQUEST['grupper'], TRUE);
	if ($grupp_json !== FALSE) {
		$grupplista = [];
		foreach (array_keys($grupp_json) as $index) {
			$grupplista[] = intval(preg_replace('/[^0-9]+/', '', (string) $index), 10);
		}
		$grupper = implode(', ', $grupplista);
		$sql = "SELECT * FROM `livs` WHERE `gruppering` IN ($grupper) ORDER BY `gruppering`,
			CAST(`$sortering` AS FLOAT) DESC LIMIT $antal";
	}
}

$sats = $livs->db->livs->prepare($sql);
$sats->execute();
$rad = $sats->fetchAll(PDO::FETCH_ASSOC);

$k = [];
if ($rad !== FALSE && count($rad) !== 0) {
	foreach (array_keys($rad[0]) as $index) {
		$k[$index] = ($index === $sortering) ? ' sorterad' : '';
	}
} else {
	$rad = [];
}

$tabell = '';
$gruppering = '';
foreach ($rad as $r) {
	$r['namn'] = htmlspecialchars($r['namn']);
	if ($_REQUEST['ag'] === 'ag') {
		if ($r['gruppering'] !== $gruppering) {
			$gruppering = $r['gruppering'];
			$grupp = GRUPPERINGAR[$r['gruppering']];
			$tabell .= <<< EOT
						<tr>
							<th colspan="39" class="grupp">$grupp</th>
						</tr>

EOT;
		}
	}

	$tabell .= <<< EOT
						<tr>
							<td>{$r['namn']}</td>
							<td class="mängd">100</td>
							<td class="g4{$k['Ca']}">{$r['Ca']}</td>
							<td class="g4{$k['Fe']}">{$r['Fe']}</td>
							<td class="g4{$k['I']}">{$r['I']}</td>
							<td class="g4{$k['K']}">{$r['K']}</td>
							<td class="g4{$k['Mg']}">{$r['Mg']}</td>
							<td class="g4{$k['Na']}">{$r['Na']}</td>
							<td class="g4{$k['P']}">{$r['P']}</td>
							<td class="g4{$k['Se']}">{$r['Se']}</td>
							<td class="g4{$k['Zn']}">{$r['Zn']}</td>
							<td class="g3{$k['vit_A']}">{$r['vit_A']}</td>
							<td class="g3{$k['vit_D']}">{$r['vit_D']}</td>
							<td class="g3{$k['vit_E']}">{$r['vit_E']}</td>
							<td class="g3{$k['vit_K']}">{$r['vit_K']}</td>
							<td class="g3{$k['vit_B1']}">{$r['vit_B1']}</td>
							<td class="g3{$k['vit_B2']}">{$r['vit_B2']}</td>
							<td class="g3{$k['vit_B3']}">{$r['vit_B3']}</td>
							<td class="g3{$k['vit_B6']}">{$r['vit_B6']}</td>
							<td class="g3{$k['vit_B9']}">{$r['vit_B9']}</td>
							<td class="g3{$k['vit_B12']}">{$r['vit_B12']}</td>
							<td class="g3{$k['vit_C']}">{$r['vit_C']}</td>
							<td class="g2{$k['scfa']}">{$r['scfa']}</td>
							<td class="g2{$k['mcfa']}">{$r['mcfa']}</td>
							<td class="g2{$k['lcfa']}">{$r['lcfa']}</td>
							<td class="g2{$k['linolsyra']}">{$r['linolsyra']}</td>
							<td class="g2{$k['linolensyra']}">{$r['linolensyra']}</td>
							<td class="g2{$k['arakidonsyra']}">{$r['arakidonsyra']}</td>
							<td class="g2{$k['EPA']}">{$r['EPA']}</td>
							<td class="g2{$k['DPA']}">{$r['DPA']}</td>
							<td class="g2{$k['DHA']}">{$r['DHA']}</td>
							<td class="g1{$k['nytta']}">{$r['nytta']}</td>
							<td class="g1{$k['energi']}">{$r['energi']}</td>
							<td class="g1{$k['kolhydrater']}">{$r['kolhydrater']}</td>
							<td class="g1{$k['protein']}">{$r['protein']}</td>
							<td class="g1{$k['fett']}">{$r['fett']}</td>
							<td class="g1{$k['monosackarider']}">{$r['monosackarider']}</td>
							<td class="g1{$k['disackarider']}">{$r['disackarider']}</td>
							<td class="g1{$k['fullkorn']}">{$r['fullkorn']}</td>
							<td class="g1{$k['sockerarter']}">{$r['sockerarter']}</td>
						</tr>

EOT;
}

echo <<< EOT
					<tbody id="livsmedelstabell">
$tabell					</tbody>
EOT;
