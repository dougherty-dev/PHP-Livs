<?php declare(strict_types = 1);

function slumpid(): string {
	return 'id' . bin2hex(random_bytes(HASHLÄNGD));
}

function rensa(string $s): string {
	$s = (string) preg_replace('/[\x00-\x1F\x7F]/', '', $s); // kontrolltecken
	$s = str_replace(['\'', '"', '&', '#', ';', '<', '>', '`', '~', '%'], '', $s);
	return $s;
}
