<?php declare(strict_types = 1);

final class Uppmärkning {
	public Livsmedel $livsmedel;
	public Manual $manual;

	public function __construct(public Livs $livs) {
		$this->livsmedel = new Livsmedel($this->livs);
		$this->manual = new Manual();
		$this->visa();
	}

	private function visa(): void {
		$this->initiera_uppmärkning();
		echo <<< EOT
		<h1 class="fix"><a href="./"><span class="emoji">🫕</span></a></h1>
		<ul>
			<li><a href="#menyer">Menyer</a></li>
			<li><a href="#livsmedel">Livsmedel</a></li>
			<li><a href="#manual">Manual</a></li>
		</ul>
{$this->visa_menyer()}
{$this->livsmedel->visa_livsmedel()}
{$this->manual->visa_manual()}
EOT;
		$this->terminera_uppmärkning();
	}

	private function visa_menyer(): string {
		return <<< EOT
		<div id="menyer">
{$this->livs->texthuvud}
{$this->hämta_menyer()}
			<form data-id="0">
				<input name="tabellid" type="hidden" value="0">
				<table data-id="0">
					<caption>
						<input name="meny" class="meny" type="text" placeholder="Ny meny">
						<button type="button" class="spara_meny" disabled>✅</button>
						<button type="button" class="kollapsa">🔆</button>
					</caption>
					<thead>
{$this->livs->tabellhuvud}
					</thead>
{$this->nytt_mål()}
{$this->livs->summering()}
				</table>
			</form>
		</div> <!-- #menyer -->
EOT;
	}

	private function hämta_menyer(): string {
		$menyer = '';
		$sats = $this->livs->db->livs->query("SELECT * FROM `meny`");
		$menydata = ($sats !== FALSE) ? $sats->fetchAll(PDO::FETCH_ASSOC) : FALSE;
		if ($menydata === FALSE) return '';
		foreach ($menydata as $meny) {
			$menyer .= <<< EOT
			<form data-id="{$meny['menyid']}">
				<input name="tabellid" type="hidden" value="{$meny['menyid']}">
				<table data-id="{$meny['menyid']}">
					<caption>
						<button type="button" class="radera_meny">❌</button>
							<input name="meny" class="meny" type="text" value="{$meny['menynamn']}">
						<button type="button" class="spara_meny">✅</button>
						<button type="button" class="kollapsa">🔆</button>
					</caption>
					<thead>
{$this->livs->tabellhuvud}
					</thead>

EOT;
			$sats = $this->livs->db->livs->prepare("SELECT `målid`, `målnamn` FROM `mål` WHERE `menyid`=:menyid ORDER BY `målid`");
			$sats->bindValue(':menyid', $meny['menyid'], PDO::PARAM_INT);
			$sats->execute();
			$måldata = $sats->fetchAll(PDO::FETCH_ASSOC);
			if ($måldata === FALSE) return '';
			foreach($måldata as $mål) {
				$målid = slumpid();
				$menyer .= <<< EOT
					<tbody id="$målid" class="måldefinition">
						<tr>
							<td colspan="38">
								<input name="mål[$målid]" class="mål" type="text" placeholder="Mål" value="{$mål['målnamn']}">
								<button type="button" class="radera_mål">❌</button>
							</td>
						</tr>

EOT;

				$sats = $this->livs->db->livs->prepare("SELECT `ingrediensid`, `livsmedelsid`, `mängd`
					FROM `ingrediens` WHERE `menyid`=:menyid AND `målid`=:malid ORDER BY `menyid`, `målid`");
				$sats->bindValue(':menyid', $meny['menyid'], PDO::PARAM_INT);
				$sats->bindValue(':malid', $mål['målid'], PDO::PARAM_INT);
				$sats->execute();
				$ingrediensdata = $sats->fetchAll(PDO::FETCH_ASSOC);
				if ($ingrediensdata === FALSE) return '';
				foreach ($ingrediensdata as $ingrediensid => $ingrediens) {
					$menyer .= $this->livs->hämta_ingrediens((int) $ingrediens['livsmedelsid'], $målid, (float) $ingrediens['mängd']);
				}
					$menyer .= <<< EOT
{$this->livs->ingrediensmall($målid)}
					</tbody>

EOT;
			}

					$menyer .= <<< EOT
{$this->livs->summering()}
				</table>
			</form>

EOT;
		}
		return $menyer;
	}

	private function nytt_mål(): string {
		$målid = slumpid();
		return <<< EOT
					<tbody id="$målid" class="måldefinition">
						<tr>
							<td colspan="38">
								<input name="mål[$målid]" class="mål" type="text" placeholder="Mål">
								<button type="button" class="radera_mål">❌</button>
							</td>
						</tr>
{$this->livs->ingrediensmall($målid)}
					</tbody>
EOT;
	}

	private function initiera_uppmärkning(): void {
		echo <<< EOT
<!doctype html>
<html lang="sv">
<head>
	<meta charset="UTF-8">
	<script src="js/jquery.min.js"></script>
	<script src="js/jquery-ui.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="css/livs.css?{$this->c(VERSIONSDATUM)}">
	<title>Livs {$this->c(VERSION)}</title>
</head>
<body>
	<div id="flikar">

EOT;
	}

	private function terminera_uppmärkning(): void {
		echo <<< EOT
	</div> <!-- flikar -->
	<script src="js/funktioner.js?{$this->c(VERSIONSDATUM)}"></script>
</body>
</html>

EOT;
	}

	private function c(string $v):string {return $v;}

}
