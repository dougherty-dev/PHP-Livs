<?php declare(strict_types = 1);

final class Manual {
	public function __construct() {}

	public function visa_manual(): string {
		return <<< EOT
		<div id="manual">
			<article>
				<h2>Livs</h2>
				<p>Med detta verktyg kan du enklare komponera dina måltider med hjälp av Livsmedelssverkets databas över näringsämnen. Definiera en <strong>meny</strong> för varje <em>dag</em> eller en annan tidsperiod. Bestäm därefter ett eller flera <strong>mål</strong> i varje meny, till exempel <em>frukost</em>, <em>lunch</em> och <em>middag</em>. Fyll slutligen varje mål med lämpliga <strong>ingredienser</strong> i den <strong>mängd</strong> du vill ha.</p>

				<p>Summering av kolumner sker automatiskt och presenteras i botten av tabellen för aktuell meny. I grundläge visas enbart <em>Vitaminer</em>, men man kan växla vy till <em>Mineraler</em>, <em>Fetter</em> och <em>Makrodata</em> genom att klicka på tabellens högra sida. På så sätt kan man koncentrera kompositionen av måltider till en viss kategori av näringsämnen, men man kan även växla till en översiktlig megatabell med ikonen ↔️, och tillbaka med ◀️. Dessa och andra val sparas i kakor.</p>

				<p>En annan vyväxlare gäller huruvida man vill visa <em>RDI</em>, <em>Lägsta intag</em> eller <em>Snittbehov</em> i tabellhuvudet. Rekommenderat dagligt intag av näringsämnen är nämligen tilltaget i överkant, varför man kan ha behov av att nyttja en annan modell, exempelvis om man är vegetarian. Växla genom att klicka på respektive rubrik, eller visa alla tre samtidigt med ikonen ↕️ / 🔼.</p>

				<p>Summering av näringsämnen åtföljs av en beräkning av procentsats för respektive intagsmodell, och vid fullt intag växlar cellen färg till en ljusgrön nyans. Målet är just att uppfylla RDI eller annan modell med lämpligt val av ingredienser. Därvid bör man ha i åtanke att man inte varje dag behöver nå full behovstäckning, utan bör sikta på att i medel nå ungefärlig täckning över tid.</p>

				<p>Exempelvis vitaminer A, D, E och K är fettlösliga, och lagras därför i kroppen över en längre tid. Det gäller även essentiella fettsyror som EPA, DPA och DHA och de flesta mineraler. En konservburk sardiner eller makrill per vecka ger således full täckning av fettsyror, och man kan därför ha överseende med övriga dagar.</p>

				<p>Databasen är givetvis inte fullständig, och täcker därför inte varje upptäckligt livsmedel. Exempelvis kan det vara svårt att nå erforderliga mängder av jod med annat än tillsats i salt. Men databasen har bara vanligt salt och mineralsalt, där det senare inte innehåller jod. Ett vanligt mineralsalt som Seltin innehåller dock jod, varför behovet ändå uppfylls.</p>

				<p>Ingredienser kan dras och ordnas inom respektive mål. Enskilda ingredienser och hela mål raderas med ikonerna ✖️ respektive ❌. Nytt mål skapas genom ikonen ➕. Ändringar sparas genom att klicka på ikonen ✅ givet att det finns ett menynamn. Det finns alltid en tom ny meny tillgänglig.</p>

				<h2>Livsmedel</h2>
				<p>Fliken <strong>Livsmedel</strong> erbjuder samma slags tabell med direkt åtkomst till livsmedelsdatabasen i ett antal variationer. Ordningen bestäms av valt näringsämne i avtagande storlek, i det antal rader man vill visa. På så sätt kan man få vägledning i vilka livsmedel som kan fylla ett visst behov. Det kan också underlätta att söka i särskilda grupper av livsmedel, till exempel grönsaker. Rangordningen sker då per kategori.</p>

				<p>Ett särskilt mått ges genom kategorin <strong>nytta</strong>, som bestäms som 100 · ∑ min(mängd / RDI, 1) / 26, det vill säga en sorts snitt av det samlade intaget. Det visar sig inte oväntat att en måltidsersättning får högst poäng i denna definition med drygt 50. Därefter följer animaliska produkter med lever, ägg och fisk i topp, medan grönsaker med detta mått får låga värden. </p>

				<figure>
					<img src="bild/megatabell.webp" style="max-width:100%;" alt="Stor tabell">
					<figcaption>Megatabeller visas lämpligen på större skärm</figcaption>
				</figure>
			</article>
		</div> <!-- #manual -->
EOT;
	}

}
