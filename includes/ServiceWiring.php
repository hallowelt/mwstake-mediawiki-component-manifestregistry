<?php

use MediaWiki\MediaWikiServices;
use MWStake\MediaWiki\Component\ManifestRegistry\ManifestRegistryFactory;

return [
	'MWStakeManifestRegistryFactory' => function ( MediaWikiServices $services ) {
		$extensionRegistry = ExtensionRegistry::getInstance();
		$overrides = $GLOBALS['mwsgManifestRegistryOverrides'];
		return new ManifestRegistryFactory( $extensionRegistry, $overrides );
	}
];