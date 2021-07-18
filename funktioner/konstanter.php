<?php declare(strict_types = 1);

define('VERSION', '1.0');
define('VERSIONSDATUM', '2021-07-09a13');
define('FÖRFATTARE', 'Niklas Dougherty');

define('FELRAPPORTERING', TRUE);

define('BAS', realpath(dirname(__FILE__) . '/..'));
define('DB', BAS . '/db');
define('FUNKTIONER', BAS . '/funktioner');
define('KLASSER', BAS . '/klasser');

define('GRUPPERINGAR', [
	'Bröd',
	'Bullar, kakor, tårtor',
	'Dryck',
	'Fett, olja',
	'Fisk, skaldjur',
	'Flingor, frukostflingor, müsli, gröt, välling',
	'Frukt, bär',
	'Glass',
	'Godis',
	'Grönsaker, baljväxter, svamp',
	'Korv',
	'Kyckling, fågel',
	'Kött',
	'Lever, njure, tunga etc.',
	'Mejeri',
	'Mjöl',
	'Måltidsersättning, sportpreparat',
	'Nötter, frön',
	'Pasta, ris, gryn',
	'Potatis',
	'Pålägg',
	'Quorn, sojaprotein, vegetariska produkter',
	'Rätter',
	'Smaksättare',
	'Snacks',
	'Sylt, marmelad, gelé, chutney',
	'Ägg, rom, kaviar',
	'Övrigt',
]);

define('HASHLÄNGD', 10);
