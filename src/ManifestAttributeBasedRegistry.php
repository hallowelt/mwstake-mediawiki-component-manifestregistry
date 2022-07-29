<?php

namespace MWStake\MediaWiki\Component\ManifestRegistry;

use ExtensionRegistry;
use GlobalVarConfig;
use MediaWiki\MediaWikiServices;
use Wikimedia\ObjectFactory;

class ManifestAttributeBasedRegistry implements IRegistry {

	public const OVERRIDE_SET = 'set';
	public const OVERRIDE_MERGE = 'merge';
	public const OVERRIDE_REMOVE = 'remove';

	/**
	 *
	 * @var string
	 */
	protected $attribName = '';

	/**
	 *
	 * @var \ExtensionRegistry
	 */
	protected $extensionRegistry = null;

	/**
	 *
	 * @var array
	 */
	protected $overrides = [];

	/**
	 * @var QbjectFactory
	 */
	protected $objectFactory = null;

	/**
	 *
	 * @param string $attribName
	 * @param \ExtensionRegistry|null $extensionRegistry
	 * @param ObjectFactory|null $objectFactory
	 * @param array|null $overrides
	 */
	public function __construct( $attribName, $extensionRegistry = null, $overrides = null, ObjectFactory $objectFactory = null ) {
		$this->attribName = $attribName;
		$this->extensionRegistry = $extensionRegistry;
		$this->overrides = $overrides;
		$this->objectFactory = $objectFactory;

		if ( $this->extensionRegistry === null ) {
			$this->extensionRegistry = ExtensionRegistry::getInstance();
		}

		if ( $this->overrides === null ) {
			$config = new GlobalVarConfig( 'mwsgManifestRegistry' );
			$configOverrides = $config->get( 'Overrides' );

			$this->overrides = [];
			if ( isset( $configOverrides[ $attribName ] ) ) {
				$this->overrides = $configOverrides[ $attribName ];
			}
		}

		if ( $this->objectFactory === null ) {
			$services = MediaWikiServices::getInstance();
			$this->objectFactory = $services->getObjectFactory();
		}
	}

	/**
	 *
	 * @param string $key
	 * @param string $default
	 * @return string|callable
	 */
	public function getValue( $key, $default = '' ) {
		$registry = $this->getRegistryArray();
		$value = isset( $registry[$key] ) ? $registry[$key] : $default;

		if ( is_array( $value ) ) {
			// Attributes get merged together instead of being overwritten,
			// so just take the last one
			$value = end( $value );
		}

		return $value;
	}

	/**
	 *
	 * @return string[]
	 */
	public function getAllKeys() {
		$registry = $this->getRegistryArray();
		return array_keys( $registry );
	}

	/**
	 *
	 * @return array
	 */
	public function getAllValues() {
		$all = [];
		foreach ( $this->getAllKeys() as $key ) {
			$all[$key] = $this->getValue( $key );
		}
		return $all;
	}

	/**
	 *
	 * @return array
	 */
	protected function getRegistryArray() {
		$registry = $this->extensionRegistry->getAttribute( $this->attribName );
		if ( isset( $this->overrides[static::OVERRIDE_SET ] ) ) {
			$registry = $this->overrides[static::OVERRIDE_SET ];
		} else {
			if ( isset( $this->overrides[static::OVERRIDE_MERGE ] ) ) {
				$registry = array_merge(
					$registry,
					$this->overrides[static::OVERRIDE_MERGE ]
				);
			}
			if ( isset( $this->overrides[static::OVERRIDE_REMOVE ] ) ) {
				foreach ( $this->overrides[static::OVERRIDE_REMOVE ] as $removeKey ) {
					if ( isset( $registry[ $removeKey ] ) ) {
						unset( $registry[ $removeKey ] );
					}
				}
			}
		}

		return $registry;
	}

	/**
	 * @param string $key
	 * @param array $options
	 * @return object
	 */
	public function createObjectFromSpec( $key, $options = [] ): object {
		$registry = $this->getRegistryArray();

		if ( !isset( $registry[$key] ) ) {
			return null;
		}

		return $this->objectFactory->createObject( $registry[$key], $options );
	}

}
