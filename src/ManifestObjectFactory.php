<?php

namespace MWStake\MediaWiki\Component\ManifestRegistry;

use Wikimedia\ObjectFactory;

class ManifestObjectFactory {

	/**
	 * @var ManifestRegistryFactory
	 */
	private $registryFactory = null;

	/**
	 * @var ObjectFactory
	 */
	private $objectFactory = null;

	/**
	 * @param ManifestRegistryFactory $registryFactory
	 * @param ObjectFactory $objectFactory
	 * @return void
	 */
	public function __construct(
		ManifestRegistryFactory $registryFactory,
		ObjectFactory $objectFactory
		) {
			$this->registryFactory = $registryFactory;
			$this->objectFactory = $objectFactory;
	}

	/**
	 * @param string $registryName
	 * @param string $registryKey
	 * @param array $options
	 * @return object|null
	 */
	public function createObject( $registryName, $registryKey, $options = [] ): object {
		$registry = $this->registryFactory->get( $registryName );

		if ( !isset( $registry[$registryKey] ) ) {
			return null;
		}

		$spec = $this->registry->getValue( $registryKey );

		return $this->objectFactory->createObject( $spec, $options );
	}

}
