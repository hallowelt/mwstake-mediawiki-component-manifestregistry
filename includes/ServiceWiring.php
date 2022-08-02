<?php

use MediaWiki\MediaWikiServices;
use MWStake\MediaWiki\Component\ManifestRegistry\ManifestObjectFactory;
use MWStake\MediaWiki\Component\ManifestRegistry\ManifestRegistryFactory;

return [
	'MWStakeManifestRegistryFactory' => function ( MediaWikiServices $services ) {
		$extensionRegistry = ExtensionRegistry::getInstance();
		$overrides = $GLOBALS['mwsgManifestRegistryOverrides'];
		return new ManifestRegistryFactory( $extensionRegistry, $overrides );
	},
	'MWStakeManifestObjectFactory' => function ( MediaWikiServices $services ) {
		return new ManifestObjectFactory(
			$services->get( 'MWStakeManifestRegistryFactory' ),
			$services->getObjectFactory()
		);
	}
];
