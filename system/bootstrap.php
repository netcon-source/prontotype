<?php defined('DOCROOT') or exit('No direct script access allowed');

( ! is_dir($system_dir) and is_dir(DOCROOT . $system_dir)) and $system_dir = DOCROOT . $system_dir;
( ! is_dir($data_dir) and is_dir(DOCROOT . $data_dir)) and $data_dir = DOCROOT . $data_dir;
( ! is_dir($site_dir) and is_dir(DOCROOT . $site_dir)) and $site_dir = DOCROOT . $site_dir;
( ! is_dir($extensions_dir) and is_dir(DOCROOT . $extensions_dir)) and $extensions_dir = DOCROOT . $extensions_dir;
( ! is_dir($public_dir) and is_dir(DOCROOT . $public_dir)) and $public_dir = DOCROOT . $public_dir;

define('SYSTEM_PATH', realpath($system_dir) . DS);
define('DATA_PATH', realpath($data_dir) . DS);
define('SITE_PATH', realpath($site_dir) . DS);
define('EXTENSIONS_PATH', realpath($extensions_dir) . DS);
define('PUBLIC_PATH', realpath($public_dir) . DS);

define('COMPONENTS_PATH', SITE_PATH . 'macros' . DS);
define('PAGES_PATH', SITE_PATH . 'pages' . DS);
define('LAYOUTS_PATH', SITE_PATH . 'layouts' . DS);

define('PT_ASSETS_PATH', SYSTEM_PATH . 'assets' . DS);
define('PT_COMPONENTS_PATH', PT_ASSETS_PATH . 'macros' . DS);
define('PT_VIEWS_PATH', SYSTEM_PATH . 'views' . DS);

require_once SYSTEM_PATH . '/vendor/Twig/Twig/Autoloader.php';
require_once SYSTEM_PATH . '/vendor/Twig/Extensions/Autoloader.php';
require_once SYSTEM_PATH . '/vendor/Yaml/sfYamlParser.php';


Twig_Autoloader::register();
Twig_Extensions_Autoloader::register();


function autoload( $class )
{
    list($namespace) = explode('_', $class);
    if ( $class !== 'sfYaml' and (! isset($namespace) or $namespace !== 'Twig') )
    {
        include_once SYSTEM_PATH . 'classes' . DS . str_replace( '_', DS, strtolower($class) ) . '.php';  
    }
}

spl_autoload_register('autoload', true, true ); 


function errorHandler($errno, $errstr, $errfile, $errline) {
  if ( E_RECOVERABLE_ERROR===$errno ) {
    // catch Twig errors that occur when a user unwittingly tries to convert a stdClass object to a string  
	return true;
  }
  return false;
}

set_error_handler('errorHandler');


Prontotype::start();

$request = new Request();
$request->execute();
echo $request->response();

Prontotype::finish();