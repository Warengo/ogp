<?php declare(strict_types = 1);

namespace Webchemistry\OGP;

final class OpenGraphResult {

	const MAX_LENGTH = null;

	/** @var string|null */
	private $title;

	/** @var string|null */
	private $description;

	/** @var string|null */
	private $image;

	/** @var string|null */
	private $site;

	/** @var string */
	private $link;

	public function __construct(string $link, ?string $title, ?string $description, ?string $image, ?string $site) {
		$this->link = $link;
		$this->title = $title;
		$this->description = $description;
		$this->image = $image;
		$this->site = $site;
	}

	public function isOk(): bool {
		return $this->title || $this->description || $this->image || $this->site;
	}

	/**
	 * @return string|null
	 */
	public function getTitle(): ?string {
		return $this->title;
	}

	/**
	 * @return string|null
	 */
	public function getDescription(): ?string {
		return $this->description;
	}

	/**
	 * @return string|null
	 */
	public function getImage(): ?string {
		return $this->image;
	}

	/**
	 * @return string|null
	 */
	public function getSite(): ?string {
		return $this->site;
	}

	/**
	 * @return string
	 */
	public function getLink(): string {
		return $this->link;
	}

	public function serialize(): string {
		return serialize([
			$this->link,
			$this->title,
			$this->description,
			$this->image,
			$this->site,
		]);
	}

	public static function unserialize(string $string): ?self {
		$array = @unserialize($string);
		if (!is_array($array)) {
			return null;
		}

		return new self(...$array);
	}

}
