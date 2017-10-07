<?php
define( '_JEXEC', 1 );
define( '_VALID_MOS', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
define( 'JPATH_BASE', realpath(dirname(__FILE__).'/../../..' ) ); // define JPATH_BASE on the external file

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

$app = JFactory::getApplication('site');

// Getting params from template
$params = $app->getTemplate(true)->params;

// echo "/* use template variables => " . $params->get('favicon') . "*/\n\n";

require_once "../scssphp-0.6.7/scss.inc.php";

use Leafo\ScssPhp\Compiler;
use Leafo\ScssPhp\Server;

$scssDir = "../scss";

$scss = new Compiler();
$scss->setImportPaths($scssDir);
$scss->setFormatter('Leafo\ScssPhp\Formatter\Compressed');

$scss->setVariables(array(
	'navBG' => '#f00',
));

$server = new Server($scssDir, null, $scss);
$server->serve();
