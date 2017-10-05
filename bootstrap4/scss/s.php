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

echo "/* use template variables => " . $params->get('favicon') . "*/\n\n";

require_once "../scssphp-0.0.12/scss.inc.php";

$scssDir = "../scss";

$scss = new scssc();
$scss->setImportPaths($scssDir);
$scss->setFormatter("scss_formatter_compressed");

$scss->setVariables(array(
	'navBG' => '#f00',
));

$server = new scss_server($scssDir, null, $scss);
$server->serve();
