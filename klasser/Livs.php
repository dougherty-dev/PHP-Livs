<?php declare(strict_types = 1);

final class Livs {
	public Databas $db;
	public string $tabellhuvud, $texthuvud;

	public function __construct() {
		$this->db = new Databas;
		$this->tabellhuvud = $this->tabellhuvud();
		$this->texthuvud = $this->texthuvud();
	}

	public function texthuvud(): string {
		return <<< EOT
			<button type="button" class="fullvy">↕️</button> <button type="button" class="fullvidd">↔️</button>
EOT;
	}

	public function hämta_ingrediens(int $id, string $målid, float $mängd, string $ny = ''): string {
		$sats = $this->db->livs->prepare('SELECT * FROM `livs` WHERE `id`=:id LIMIT 1');
		$sats->bindValue(':id', $id, PDO::PARAM_INT);
		$sats->execute();
		($d = $sats->fetch(PDO::FETCH_ASSOC)) === FALSE and exit();

		$d['namn'] = htmlspecialchars($d['namn']);

		$f = fn ($m) => (string) round(floatval($m) * $mängd / 100, 2);

		return <<< EOT
						<tr class="dra$ny" data-ingrediens-id="$id">
							<td class="nw"><button type="button" class="radera_ingrediens">✖️</button>
								<input type="hidden" name="data[$målid][]" value="$id|$mängd">
								<input class="ingrediens" type="text" value="{$d['namn']}"></td>
							<td><input class="mängd$ny" type="text" value="$mängd"></td>
							<td class="v g4">{$f($d['Ca'])}</td>
							<td class="v g4">{$f($d['Fe'])}</td>
							<td class="v g4">{$f($d['I'])}</td>
							<td class="v g4">{$f($d['K'])}</td>
							<td class="v g4">{$f($d['Mg'])}</td>
							<td class="v g4">{$f($d['Na'])}</td>
							<td class="v g4">{$f($d['P'])}</td>
							<td class="v g4">{$f($d['Se'])}</td>
							<td class="v g4">{$f($d['Zn'])}</td>
							<td class="v g3">{$f($d['vit_A'])}</td>
							<td class="v g3">{$f($d['vit_D'])}</td>
							<td class="v g3">{$f($d['vit_E'])}</td>
							<td class="v g3">{$f($d['vit_K'])}</td>
							<td class="v g3">{$f($d['vit_B1'])}</td>
							<td class="v g3">{$f($d['vit_B2'])}</td>
							<td class="v g3">{$f($d['vit_B3'])}</td>
							<td class="v g3">{$f($d['vit_B6'])}</td>
							<td class="v g3">{$f($d['vit_B9'])}</td>
							<td class="v g3">{$f($d['vit_B12'])}</td>
							<td class="v g3">{$f($d['vit_C'])}</td>
							<td class="v g2">{$f($d['scfa'])}</td>
							<td class="v g2">{$f($d['mcfa'])}</td>
							<td class="v g2">{$f($d['lcfa'])}</td>
							<td class="v g2">{$f($d['linolsyra'])}</td>
							<td class="v g2">{$f($d['linolensyra'])}</td>
							<td class="v g2">{$f($d['arakidonsyra'])}</td>
							<td class="v g2">{$f($d['EPA'])}</td>
							<td class="v g2">{$f($d['DPA'])}</td>
							<td class="v g2">{$f($d['DHA'])}</td>
							<td class="v g1">{$f($d['nytta'])}</td>
							<td class="v g1">{$f($d['energi'])}</td>
							<td class="v g1">{$f($d['kolhydrater'])}</td>
							<td class="v g1">{$f($d['protein'])}</td>
							<td class="v g1">{$f($d['fett'])}</td>
							<td class="v g1">{$f($d['monosackarider'])}</td>
							<td class="v g1">{$f($d['disackarider'])}</td>
							<td class="v g1">{$f($d['fullkorn'])}</td>
							<td class="v g1">{$f($d['sockerarter'])}</td>
						</tr>

EOT;

	}

	public function summering(): string {
		return <<< EOT
					<tfoot class="summering">
						<tr>
							<td colspan="40"><button type="button" class="addera_mål">➕</button></td>
						</tr>
{$this->tr('summa', '∑ Näringsämnen')}
{$this->tr('procent li', '% Lägsta intag ⟳')}
{$this->tr('procent sb', '% Snittbehov ⟳')}
{$this->tr('procent rdi', '% RDI ⟳')}
					</tfoot>
EOT;
	}

	private function tr(string $klass, string $rubrik): string {
		return <<< EOT
						<tr class="$klass">
							<td colspan="2">$rubrik</td>
{$this->td()}
						</tr>
EOT;
	}

	private function td(): string {
		return <<< EOT
							<td class="s g4"></td>
							<td class="s g4"></td>
							<td class="s g4"></td>
							<td class="s g4"></td>
							<td class="s g4"></td>
							<td class="s g4"></td>
							<td class="s g4"></td>
							<td class="s g4"></td>
							<td class="s g4"></td>
							<td class="s g3"></td>
							<td class="s g3"></td>
							<td class="s g3"></td>
							<td class="s g3"></td>
							<td class="s g3"></td>
							<td class="s g3"></td>
							<td class="s g3"></td>
							<td class="s g3"></td>
							<td class="s g3"></td>
							<td class="s g3"></td>
							<td class="s g3"></td>
							<td class="s g2"></td>
							<td class="s g2"></td>
							<td class="s g2"></td>
							<td class="s g2"></td>
							<td class="s g2"></td>
							<td class="s g2"></td>
							<td class="s g2"></td>
							<td class="s g2"></td>
							<td class="s g2"></td>
							<td class="s g1"></td>
							<td class="kcal s g1"></td>
							<td class="s g1"></td>
							<td class="s g1"></td>
							<td class="s g1"></td>
							<td class="s g1"></td>
							<td class="s g1"></td>
							<td class="s g1"></td>
							<td class="s g1"></td>
EOT;
	}

	public function ingrediensmall(string $målid): string {
		return <<< EOT
						<tr data-ingrediens-id="">
							<td class="nw">
								<input type="hidden" name="data[$målid][]" value="">
								<input class="ingrediens" type="text" placeholder="Ny ingrediens"></td>
							<td><input class="mängd" type="text" value="100"></td>
{$this->td()}
						</tr>
EOT;
	}

	public function tabellhuvud(): string {
		return <<< EOT
						<tr>
							<th colspan="2">Kategori</th>
							<th colspan="9" class="g4 mineraler">Mineraler ⟳</th>
							<th colspan="11" class="g3 vitaminer">Vitaminer ⟳</th>
							<th class="g2 mättade">Mätt.</th>
							<th class="g2 omättade">Omätt.</th>
							<th colspan="7" class="g2 fleromättade">Fleromättade fettsyror ⟳</th>
							<th class="g1 nytta">Nytta</th>
							<th class="g1 energi">Ener.</th>
							<th colspan="3" class="g1 makronutrienter">Makronutrienter</th>
							<th colspan="4" class="g1 kolhydrater">Kolhydrater ⟳</th>
						</tr>
						<tr>
							<th colspan="2">Struktur</th>
							<th colspan="9" class="g4"></th>
							<th colspan="11" class="g3"></th>
							<th colspan="3" class="g2"></th>
							<th class="g2 ω6" title="ω−6">18:2</th>
							<th class="g2 ω3" title="ω−3">18:3</th>
							<th class="g2 ω6" title="ω−6">20:4</th>
							<th class="g2 ω3" title="ω−3">20:5</th>
							<th class="g2 ω3" title="ω−3">22:5</th>
							<th class="g2 ω3" title="ω−3">22:6</th>
							<th colspan="9" class="g1"></th>
						</tr>
						<tr>
							<th colspan="2">Enhet</th>
							<th class="g4">mg</th>
							<th class="g4">mg</th>
							<th class="g4">µg</th>
							<th class="g4">mg</th>
							<th class="g4">mg</th>
							<th class="g4">mg</th>
							<th class="g4">mg</th>
							<th class="g4">µg</th>
							<th class="g4">mg</th>
							<th class="g3">µg</th>
							<th class="g3">µg</th>
							<th class="g3">mg</th>
							<th class="g3">µg</th>
							<th class="g3">mg</th>
							<th class="g3">mg</th>
							<th class="g3">mg</th>
							<th class="g3">mg</th>
							<th class="g3">µg</th>
							<th class="g3">µg</th>
							<th class="g3">mg</th>
							<th class="g2">g</th>
							<th class="g2">g</th>
							<th class="g2">g</th>
							<th class="g2">g</th>
							<th class="g2">g</th>
							<th class="g2">g</th>
							<th class="g2">g</th>
							<th class="g2">g</th>
							<th class="g2">g</th>
							<th class="g1">%∑ RDI</th>
							<th class="g1">kcal</th>
							<th class="g1">g</th>
							<th class="g1">g</th>
							<th class="g1">g</th>
							<th class="g1">g</th>
							<th class="g1">g</th>
							<th class="g1">g</th>
							<th class="g1">g</th>
						</tr>
						<tr class="li">
							<th colspan="2">Lägsta intag ⟳</th>
							<th class="liv g4">400</th>
							<th class="liv g4">7</th>
							<th class="liv g4">70</th>
							<th class="liv g4">1600</th>
							<th class="liv g4"></th>
							<th class="liv g4"></th>
							<th class="liv g4">300</th>
							<th class="liv g4">20</th>
							<th class="liv g4">5</th>
							<th class="liv g3">500</th>
							<th class="liv g3">2.5</th>
							<th class="liv g3">4</th>
							<th class="liv g3"></th>
							<th class="liv g3">0.6</th>
							<th class="liv g3">0.8</th>
							<th class="liv g3">12</th>
							<th class="liv g3">1.0</th>
							<th class="liv g3">100</th>
							<th class="liv g3">1.0</th>
							<th class="liv g3">10</th>
							<th class="liv g2"></th>
							<th class="liv g2"></th>
							<th class="liv g2">5</th>
							<th class="liv g2">3</th>
							<th class="liv g2">1</th>
							<th class="liv g2"></th>
							<th class="liv g2">0.05</th>
							<th class="liv g2">0.05</th>
							<th class="liv g2">0.1</th>
							<th class="liv g1"></th>
							<th class="liv g1"></th>
							<th class="liv g1"></th>
							<th class="liv g1"></th>
							<th class="liv g1"></th>
							<th class="liv g1"></th>
							<th class="liv g1"></th>
							<th class="liv g1"></th>
							<th class="liv g1"></th>
						</tr>
						<tr class="sb">
							<th colspan="2">Snittbehov ⟳</th>
							<th class="sbv g4">500</th>
							<th class="sbv g4">10</th>
							<th class="sbv g4">100</th>
							<th class="sbv g4">1600</th>
							<th class="sbv g4"></th>
							<th class="sbv g4"></th>
							<th class="sbv g4">450</th>
							<th class="sbv g4">35</th>
							<th class="sbv g4">6</th>
							<th class="sbv g3">600</th>
							<th class="sbv g3">7.5</th>
							<th class="sbv g3">6</th>
							<th class="sbv g3"></th>
							<th class="sbv g3">1.2</th>
							<th class="sbv g3">1.4</th>
							<th class="sbv g3">15</th>
							<th class="sbv g3">1.4</th>
							<th class="sbv g3">200</th>
							<th class="sbv g3">1.4</th>
							<th class="sbv g3">50</th>
							<th class="sbv g2"></th>
							<th class="sbv g2"></th>
							<th class="sbv g2">12</th>
							<th class="sbv g2">7</th>
							<th class="sbv g2">3</th>
							<th class="sbv g2"></th>
							<th class="sbv g2">0.1</th>
							<th class="sbv g2">0.1</th>
							<th class="sbv g2">0.2</th>
							<th class="sbv g1"></th>
							<th class="sbv g1"></th>
							<th class="sbv g1"></th>
							<th class="sbv g1"></th>
							<th class="sbv g1"></th>
							<th class="sbv g1"></th>
							<th class="sbv g1"></th>
							<th class="sbv g1"></th>
							<th class="sbv g1"></th>
						</tr>
						<tr class="rdi">
							<th colspan="2">RDI ⟳</th>
							<th class="rdiv g4">800</th>
							<th class="rdiv g4">10</th>
							<th class="rdiv g4">150</th>
							<th class="rdiv g4">3500</th>
							<th class="rdiv g4">350</th>
							<th class="rdiv g4">1500</th>
							<th class="rdiv g4">600</th>
							<th class="rdiv g4">60</th>
							<th class="rdiv g4">9</th>
							<th class="rdiv g3">900</th>
							<th class="rdiv g3">10</th>
							<th class="rdiv g3">10</th>
							<th class="rdiv g3">120</th>
							<th class="rdiv g3">1.4</th>
							<th class="rdiv g3">1.6</th>
							<th class="rdiv g3">16</th>
							<th class="rdiv g3">1.7</th>
							<th class="rdiv g3">300</th>
							<th class="rdiv g3">2.0</th>
							<th class="rdiv g3">75</th>
							<th class="rdiv g2"></th>
							<th class="rdiv g2"></th>
							<th class="rdiv g2">19</th>
							<th class="rdiv g2">10</th>
							<th class="rdiv g2">3</th>
							<th class="rdiv g2"></th>
							<th class="rdiv g2">0.1</th>
							<th class="rdiv g2">0.1</th>
							<th class="rdiv g2">0.3</th>
							<th class="rdiv g1"></th>
							<th class="rdiv g1"></th>
							<th class="rdiv g1"></th>
							<th class="rdiv g1"></th>
							<th class="rdiv g1"></th>
							<th class="rdiv g1"></th>
							<th class="rdiv g1"></th>
							<th class="rdiv g1"></th>
							<th class="rdiv g1"></th>
						</tr>
						<tr>
							<th>Namn</th>
							<th>m (g)</th>
							<th class="g4 mineraler">Ca</th>
							<th class="g4 mineraler">Fe</th>
							<th class="g4 mineraler">I</th>
							<th class="g4 mineraler">K</th>
							<th class="g4 mineraler">Mg</th>
							<th class="g4 mineraler">Na</th>
							<th class="g4 mineraler">P</th>
							<th class="g4 mineraler">Se</th>
							<th class="g4 mineraler">Zn</th>
							<th class="g3 vitaminer">A</th>
							<th class="g3 vitaminer">D</th>
							<th class="g3 vitaminer">E</th>
							<th class="g3 vitaminer">K</th>
							<th class="g3 vitaminer">B1</th>
							<th class="g3 vitaminer">B2</th>
							<th class="g3 vitaminer">B3</th>
							<th class="g3 vitaminer">B6</th>
							<th class="g3 vitaminer">B9</th>
							<th class="g3 vitaminer">B12</th>
							<th class="g3 vitaminer">C</th>
							<th class="g2 mättade">SCFA</th>
							<th class="g2 omättade">MCFA</th>
							<th class="g2 fleromättade">LCFA</th>
							<th class="g2 fleromättade">LA</th>
							<th class="g2 fleromättade">ALA</th>
							<th class="g2 fleromättade">AA</th>
							<th class="g2 fleromättade">EPA</th>
							<th class="g2 fleromättade">DPA</th>
							<th class="g2 fleromättade">DHA</th>
							<th class="g1 nytta">N</th>
							<th class="g1 energi">E</th>
							<th class="g1 makronutrienter">Kolh</th>
							<th class="g1 makronutrienter">Prot</th>
							<th class="g1 makronutrienter">Fett</th>
							<th class="g1 kolhydrater">MS</th>
							<th class="g1 kolhydrater">DS</th>
							<th class="g1 kolhydrater">Fullk</th>
							<th class="g1 kolhydrater">Sock</th>
						</tr>
EOT;
	}
}
