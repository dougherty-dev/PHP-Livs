<?php declare(strict_types = 1);

final class Livsmedel {
	public function __construct(public Livs $livs) {

	}

	public function visa_livsmedel(): string {
		return <<< EOT
		<div id="livsmedel">
{$this->livs->texthuvud}
{$this->sorteringsval()}
			<form id="livsmedelsform">
				<table id="näringstabell">
					<caption>Näringstabell</caption>
					<thead>
{$this->livs->tabellhuvud}
					</thead>
					<tbody id="livsmedelstabell">
					</tbody>
				</table>
			</form>
		</div> <!-- #livsmedel -->
EOT;
	}

	private function sorteringsval(): string {
		$box = '';
		foreach (GRUPPERINGAR as $i => $grupp) {
			$box .= <<< EOT
			<label class="grupper" for="box-$i">
				<input type="checkbox" name="box-$i" id="box-$i"> $grupp
			</label>

EOT;
		}

		return <<< EOT
			| Sortering:
			<select id="sortering">
				<optgroup label="Sammanvägd nyttighet">
					<option value="nytta">Nytta %∑ RDI</option>
				</optgroup>
				<optgroup label="Vitaminer">
					<option selected="selected" value="vit_A">A</option>
					<option value="vit_D">D</option>
					<option value="vit_E">E</option>
					<option value="vit_K">K</option>
					<option value="vit_B1">B1</option>
					<option value="vit_B2">B2</option>
					<option value="vit_B3">B3</option>
					<option value="vit_B6">B6</option>
					<option value="vit_B9">B9</option>
					<option value="vit_B12">B12</option>
					<option value="vit_C">C</option>
				</optgroup>
				<optgroup label="Mineraler">
					<option value="Ca">Ca</option>
					<option value="Fe">Fe</option>
					<option value="I">I</option>
					<option value="K">K</option>
					<option value="Mg">Mg</option>
					<option value="Na">Na</option>
					<option value="P">P</option>
					<option value="Se">Se</option>
					<option value="Zn">Zn</option>
				</optgroup>
				<optgroup label="Fettsyror">
					<option value="scfa">∑ Mättade</option>
					<option value="mcfa">∑ Omättade</option>
					<option value="lcfa">∑ Fleromättade</option>
					<option value="linolsyra">LA ω-6</option>
					<option value="linolensyra">ALA ω-3</option>
					<option value="arakidonsyra">AA ω-6</option>
					<option value="EPA">EPA ω-3</option>
					<option value="DPA">DPA ω-3</option>
					<option value="DHA">DHA ω-3</option>
				</optgroup>
				<optgroup label="Makronutrienter">
					<option value="energi">Energi</option>
					<option value="kolhydrater">Kolhydrater</option>
					<option value="protein">Protein</option>
					<option value="fett">Fett</option>
					<option value="fullkorn">Fullkorn</option>
					<option value="sockerarter">Socker</option>
				</optgroup>
			</select>
			| Antal:
			<select id="antal">
				<option value="20">20</option>
				<option value="50">50</option>
				<option value="100">100</option>
				<option value="200">200</option>
				<option value="500">500</option>
				<option value="5000">∞</option>
			</select>
			<hr>
			<input type="checkbox" id="använd_grupper" checked> <em>Använd grupper</em>
			<form id="gruppform">
				<fieldset id="grupper">
$box			</fieldset>
			</form>
EOT;
	}

}
