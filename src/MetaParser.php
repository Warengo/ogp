<?php declare(strict_types = 1);

namespace Webchemistry\OGP;

class MetaParser {

	/** @var array */
	private $metadata = [
		'properties' => [],
		'names' => [],
	];

	/** @var string|null */
	private $charset;

	public function __construct(string $content) {
		if (preg_match_all('#<meta (.*?)>#ms', $content, $matches)) {
			foreach ($matches[1] as $match) {
				if (preg_match('#property="(.*?)"#', $match, $matches)) {
					$name = $matches[1];
					if (preg_match('#content="(.*?)"#ms', $match, $matches)) {
						$this->metadata['properties'][$name] = $matches[1];
					}
				} else if (preg_match('#charset="(.*?)"#', $match, $matches)) {
					$this->charset = $matches[1];
				} else if (preg_match('#name="(.*?)"#', $match, $matches)) {
					$name = $matches[1];
					if (preg_match('#content="(.*?)"#ms', $match, $matches)) {
						$this->metadata['names'][$name] = $matches[1];
					}
				}
			}
		}
	}

	protected function decode(string $content): string {
		if ($this->charset && strtoupper($this->charset) !== 'UTF-8') {
			$str = @iconv($this->charset, 'utf-8//TRANSLIT', $content);
			if ($str !== false) {
				$content = $str;
			}
		}
		$content = html_entity_decode($content, ENT_QUOTES);

		return $content;
	}

	public function hasProperty(string $name): bool {
		return isset($this->metadata['properties'][$name]);
	}

	public function getProperty(string $name): string {
		return $this->decode($this->metadata['properties'][$name]);
	}

	public function hasName(string $name): bool {
		return isset($this->metadata['properties'][$name]);
	}

	public function getName(string $name): string {
		return $this->decode($this->metadata['properties'][$name]);
	}

}
