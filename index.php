<?php declare(strict_types = 1);

version_compare(PHP_VERSION, '8.0', '>=') or die('Kräver PHP 8.0+');
require dirname(__FILE__) . '/klasser/Preludium.php';
new Uppmärkning($livs = new Livs);
