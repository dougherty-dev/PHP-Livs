<?php declare(strict_types = 1);

require_once dirname($_SERVER['SCRIPT_FILENAME']) . '/../klasser/Preludium.php';
$livs = new Livs;

$målid = slumpid();
echo <<< EOT
					<tbody id="$målid" class="måldefinition">
						<tr>
							<td colspan="40">
								<input name="mål[$målid]" class="mål" type="text" placeholder="Mål">
								<button type="button" class="radera_mål">❌</button>
							</td>
						</tr>
{$livs->ingrediensmall($målid)}
					</tbody>
EOT;
