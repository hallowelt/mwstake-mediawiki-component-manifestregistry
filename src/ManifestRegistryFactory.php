<?php

namespace MWStake\MediaWiki\Component\ManifestRegistry;

use ExtensionRegistry;
use Wikimedia\ObjectFactory;

class ManifestRegistryFactory {

	/**
	 *
	 * @var ExtensionRegistry
	 */
	private $extensionRegistry = null;

	/**
	 *
	 * @var array
	 */
	private $overrides = [];

	/**
	 *
	 * @var [type]
	 */
	private $objectFactory = null;

	/**
	 *
	 * @param ExtensionRegistry $extensionRegistry
	 * @param array $overrides
	 * @param ObjectFactory $objectFactory
	 */
	public function __construct( $extensionRegistry, $overrides, $objectFactory ) {
		$this->extensionRegistry = $extensionRegistry;
		$this->overrides = $overrides;
		$this->objectFactory = $objectFactory;
	}

	/**
	 *
	 * @param string $manifestAttributeKey
	 * @return IRegistry
	 */
	public function get( $manifestAttributeKey ) : IRegistry {
		$registry = new ManifestAttributeBasedRegistry(
			$manifestAttributeKey,
			$this->extensionRegistry,
			$this->overrides,
			$this->objectFactory
		);

		return $registry;
	}
}
