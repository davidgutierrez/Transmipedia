<?php
/**
 * ----------------------------------------------------------------------------------------
 * This file is provided by the wikibase/wikibase docker image.
 * This file will be passed through envsubst which will replace "$" with "$".
 * If you want to change MediaWiki or Wikibase settings then either mount a file over this
 * template and or run a different entrypoint.
 * ----------------------------------------------------------------------------------------
 */

## The protocol and server name to use in fully-qualified URLs
$wgServer = "https://8080-dot-4026032-dot-devshell.appspot.com";

## Database settings
## Environment variables will be substituted in here.
$wgDBserver = "database:3306";
$wgDBname = "my_wiki";
$wgDBuser = "wikiuser";
$wgDBpassword = "example";

## Logs
## Save these logs inside the container
$wgDebugLogGroups = array(
	'resourceloader' => '/var/log/mediawiki/resourceloader.log',
	'exception' => '/var/log/mediawiki/exception.log',
	'error' => '/var/log/mediawiki/error.log',
);

## Site Settings
# TODO pass in the rest of this with env vars?
$wgShellLocale = "C.UTF-8";
$wgLanguageCode = "es";
$wgSitename = "wikibase-docker";
$wgMetaNamespace = "Project";
# Configured web paths & short URLs
# This allows use of the /wiki/* path
## https://www.mediawiki.org/wiki/Manual:Short_URL
$wgScriptPath = "/w";        // this should already have been configured this way
$wgLogo = $wgScriptPath . "/resources/assets/TransMilenio.png";
$wgArticlePath = "/wiki/$1";

#Set Secret
$wgSecretKey = "secretkey";

## RC Age
# https://www.mediawiki.org/wiki/Manual:
# Items in the recentchanges table are periodically purged; entries older than this many seconds will go.
# The query service (by default) loads data from recent changes
# Set this to 1 year to avoid any changes being removed from the RC table over a shorter period of time.
$wgRCMaxAge = 365 * 24 * 3600;

wfLoadSkin( 'Vector' );

## Wikibase
# Load Wikibase repo, client & lib with the example / default settings.
require_once "$IP/extensions/Wikibase/vendor/autoload.php";
require_once "$IP/extensions/Wikibase/lib/WikibaseLib.php";
require_once "$IP/extensions/Wikibase/repo/Wikibase.php";
require_once "$IP/extensions/Wikibase/repo/ExampleSettings.php";
require_once "$IP/extensions/Wikibase/client/WikibaseClient.php";
require_once "$IP/extensions/Wikibase/client/ExampleSettings.php";


# Enabled extensions. Most of the extensions are enabled by adding
# wfLoadExtensions('ExtensionName');
# to LocalSettings.php. Check specific extension documentation for more details.
# The following extensions were automatically enabled:
wfLoadExtension( 'ParserFunctions' );
wfLoadExtension( 'ImageMap' );
wfLoadExtension( 'PdfHandler' );
wfLoadExtension( 'WikiEditor' );
$wgHiddenPrefs[] = 'usebetatoolbar';
wfLoadExtension( 'Cite' );
wfLoadExtension( 'Scribunto' );
$wgScribuntoDefaultEngine = 'luastandalone';

$wgShowExceptionDetails = true;
$wgShowDBErrorBacktrace = true;
$wgShowSQLErrors = true;
