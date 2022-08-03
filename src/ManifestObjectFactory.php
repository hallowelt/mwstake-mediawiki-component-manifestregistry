<?php

namespace MWStake\MediaWiki\Component\ManifestRegistry;

use Psr\Log\LoggerInterface;
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
	 * @var LoggerInterface
	 */
	private $logger = null;

	/**
	 * @param ManifestRegistryFactory $registryFactory
	 * @param ObjectFactory $objectFactory
	 * @param LoggerInterface $logger
	 * @return void
	 */
	public function __construct(
		ManifestRegistryFactory $registryFactory,
		ObjectFactory $objectFactory,
		LoggerInterface $logger
		) {
			$this->registryFactory = $registryFactory;
			$this->objectFactory = $objectFactory;
			$this->logger = $logger;
	}

	/**
	 * @param string $registryName
	 * @param string $registryKey
	 * @param array $options
	 * @param string|null $instanceof
	 * @return object|null
	 */
	public function createObject(
		string $registryName,
		string $registryKey,
		array $options = [],
		string $instanceof = null
		): ?object {
		$registry = $this->registryFactory->get( $registryName );

		if ( !isset( $registry[$registryKey] ) ) {
			return null;
		}

		$spec = $this->registry->getValue( $registryKey );
		$object = $this->objectFactory->createObject( $spec, $options );

		if ( ( $instanceof === null ) | is_a( $object, $instanceof, true ) ) {
			return $object;
		}

		return null;
	}

	/**
	 * @param string $registryName
	 * @param array $options
	 * @param string|null $instanceof
	 * @return array
	 */
	public function createAllObjects(
		string $registryName,
		array $options = [],
		string $instanceof = null
		): array {
		$registry = $this->registryFactory->get( $registryName );
		$registryKeys = $registry->getAllKeys();

		$objects = [];
		foreach ( $registryKeys as $registryKey ) {
			$spec = $this->registry->getValue( $registryKey );
			$object = $this->objectFactory->createObject( $spec, $options, $instanceof );

			if ( $object === null ) {
				$this->logger->warning(
					"The object is not a instance of the wanted class",
					[
						'registry' => $registryName,
						'key' => $registryKey,
						'instanceof' => $instanceof
					]
				);

				continue;
			}

			$objects[$registryKey] = $object;
		}

		return $objects;
	}

}
