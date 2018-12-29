<?php declare(strict_types = 1);

namespace Webchemistry\OGP;

class OpenGraphParser {

	/** @var string */
	private $link;

	public function __construct(string $link) {
		$this->link = $link;
	}

	public function parse(): ?OpenGraphResult {
		$content = @file_get_contents($this->link);
		if ($content === false) {
			return null;
		}
		$meta = new MetaParser($content);

		// title
		$title = null;
		if ($meta->hasProperty('og:title')) {
			$title = $meta->getProperty('og:title');
		} else if ($meta->hasProperty('twitter:title')) {
			$title = $meta->getProperty('twitter:title');
		} else {
			if (preg_match('#<title>(.*?)</title>#m', $content, $matches)) {
				$title = trim($matches[1]);
			}
		}

		// description
		$description = null;
		if ($meta->hasProperty('og:description')) {
			$description = $meta->getProperty('og:description');
		} else if ($meta->hasProperty('twitter:description')) {
			$description = $meta->getProperty('twitter:description');
		} else if ($meta->hasName('description')) {
			$description = $meta->getProperty('description');
		}

		// image
		$image = null;
		if ($meta->hasProperty('og:image')) {
			$image = $meta->getProperty('og:image');
		} else if ($meta->hasProperty('twitter:image:src')) {
			$image = $meta->getProperty('twitter:image:src');
		}

		// site name
		$site = null;
		if ($meta->hasProperty('og:site_name')) {
			$site = $meta->getProperty('og:site_name');
		}

		return new OpenGraphResult($this->link, $title, $description, $image, $site);
	}

}
