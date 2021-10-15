## MediaWiki Stakeholders Group - Components
# ManifestRegistry for MediaWiki

Provides a combined registry over all in the `attribute` section registered things in `extension.json` files.

**This code is meant to be executed within the MediaWiki application context. No standalone usage is intended.**

## Prerequisites

## Use in a MediaWiki extension

Add `"mwstake/mediawiki-component-manifestregistry": "~1.0"` to the `require` section of your `composer.json` file.

### Register values in extension.json
```JSON
{
	"attributes": {
		"BlueSpiceFoundation": {
			"RoleRegistry": {
				"admin": "\\BlueSpice\\Permission\\Role\\Admin::factory",
				"editor": "\\BlueSpice\\Permission\\Role\\Editor::factory",
				"reader": "\\BlueSpice\\Permission\\Role\\Reader::factory",
				"author": "\\BlueSpice\\Permission\\Role\\Author::factory",
				"reviewer": "\\BlueSpice\\Permission\\Role\\Reviewer::factory",
				"accountmanager": "\\BlueSpice\\Permission\\Role\\AccountManager::factory"
			}
		},
		"BlueSpicePrivacy": {
			"CookieConsentNativeMWCookies": {
				"notificationFlag": {
					"group": "necessary",
					"addPrefix": true
				}
			}
		}
	},
	"manifest_version": 2,
}
```

### Implement in your code
```php
$factory = \MediaWiki\MediaWikiServices::getInstance()->getService( 'MWStakeManifestRegistryFactory' );
$registry = $factory->get( 'MyExtensionMyRegistry' );
$myValues = $registry->get( 'subValue' );
$allMyValues = $registry->getAll();
```

## Configuration
- `mwsgManifestRegistryOverrides`: Used to overwrite existing registries by either add, remove or merge their values:
*Example 1:
```php
$GLOBALS['mwsgManifestRegistryOverrides']['MyRegistry'] = [
	'set' => [
		'ReplaceKey' => 'with new value',
	],
	'merge' => [
		'AddThisKey' => 'with this value',
	],
	'remove' => [ 'keyOfValueThatShouldBeRemoved' ]
]
```
