## MediaWiki Stakeholders Group - Components
# ManifestRegistry for MediaWiki

Provides background tasks infrastructure based on MediaWikis `maintenance/runJobs.php`.

**This code is meant to be executed within the MediaWiki application context. No standalone usage is intended.**

## Prerequisites

## Use in a MediaWiki extension

Add `"mwstake/mediawiki-component-manifestregistry": "~1.0"` to the `require` section of your `composer.json` file.

### Implement a handler

Create a class that implements `MWStake\MediaWiki\Component\RunJobsTrigger\IHandler`. For convenience you may want to derive directly from the abstract base class `MWStake\MediaWiki\Component\RunJobsTrigger\HandlerBase`

In the `getInterval` method you can return any object that implements `MWStake\MediaWiki\Component\RunJobsTrigger\Interval`. There are a couple of predefined intevals available
- `MWStake\MediaWiki\Component\RunJobsTrigger\Interval\OnceADay`
- `MWStake\MediaWiki\Component\RunJobsTrigger\Interval\OnceAWeek`
- `MWStake\MediaWiki\Component\RunJobsTrigger\Interval\OnceEveryHour`
- `MWStake\MediaWiki\Component\RunJobsTrigger\Interval\TwiceADay`

### Register a handler

There are two ways to register a handler:
1. Using the `mwsgRunJobsTriggerHandlerRegistry` GlobalVars configuraton
2. Using the hook `MWStakeRunJobsTriggerRegisterHandlers`

On both cases a [ObjectFactory specification](https://www.mediawiki.org/wiki/ObjectFactory) must be provided.

*Example 1: GlobalVars*
```php
$GLOBALS['mwsgRunJobsTriggerHandlerRegistry']['my-own-handler'] = [
    'class' => '\\MediaWiki\Extension\\MyExt\\MyHandler,
    'services' => 'MainConfig'
];
```
*Example 2: Hookhandler*
```php
$GLOBALS['wgHooks']['MWStakeRunJobsTriggerRegisterHandlers'][] = function( &$handlers ) {
    $handlers["my-own-handler"] = [
        'class' => '\\MediaWiki\Extension\\MyExt\\MyHandler,
        'services' => 'MainConfig'
    ]
}
```

## Configuration
- `mwsgRunJobsTriggerRunnerWorkingDir`: Sets where to store data durin execution. If not set otherwise, the operating systems temp dir will be used
- `mwsgRunJobsTriggerOptions`: Allows to change timing options for particular handlers. E.g. to run a `OnceAWeek` on Friday instead of Sunday.
- `mwsgRunJobsTriggerHandlerRegistry`: Allows to add own handlers

## Debugging
A debug log can be enabled by putting

    $GLOBALS['wgDebugLogGroups']['runjobs-trigger-runner'] = "/tmp/runjobs-trigger-runner.log";

to your `LocalSettings.php` file