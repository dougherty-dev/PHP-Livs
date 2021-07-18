<?php declare(strict_types = 1);

final class Databas {
	public PDO $livs;

	public function __construct() {
		$this->anslut();
	}

	private function anslut(): void {
		$this->livs = new PDO('sqlite:' . DB . '/livs.db', NULL, NULL, [PDO::ATTR_PERSISTENT => TRUE]);
		$this->livs->exec('PRAGMA temp_store = MEMORY; PRAGMA mmap_size = 1000000000');
		$this->livs->sqliteCreateFunction('like', [$this, 'mb_like'], 2); // SQLite LIKE begränsad till ASCII
	}

	public function mb_like(string $mask, string $sträng): int {
		$mask = str_replace(['%', '_'], ['.*?', '.'], preg_quote($mask, "/"));
		$mask = "/^$mask$/ui";
		return (int) preg_match($mask, $sträng);
	}

	public function __sleep(): array {
		return [];
	}

	public function __wakeup(): void {
		$this->anslut();
	}
}
