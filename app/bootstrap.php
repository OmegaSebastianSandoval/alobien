<?php
session_start();
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__)) . DS);
define('FRAMEWORK_PATH', ROOT . '../framework' . DS);
define('APP_PATH', ROOT . '../app' . DS);
define('VIEWS_PATH', APP_PATH . 'View' . DS);
define('LAYOUTS_PATH', APP_PATH . 'layout' . DS);
define('IMAGE_PATH', APP_PATH . "../public/images/");
define('FILE_PATH', APP_PATH . "../public/files/");
define('PUBLIC_PATH', APP_PATH . "../public/");


date_default_timezone_set('America/Bogota');

// Establecer idioma por defecto
$selected_lang = 'en';

// Verificar si se está cambiando el idioma
if(isset($_GET['lang'])){
  $locale = $_GET['lang'];
  setcookie('user_lang', $locale, time() + (86400 * 30), "/");
  $selected_lang = $locale;
  
  // Redireccionar para remover el parámetro lang de la URL y aplicar el cambio inmediatamente
  $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  header("Location: " . $current_path);
  exit();
} else if (isset($_COOKIE['user_lang'])) {
    $selected_lang = $_COOKIE['user_lang'];
}

require_once FRAMEWORK_PATH . 'Config/Config.php';
set_include_path(
  implode(
    PATH_SEPARATOR,
    array(
      get_include_path(),
      FRAMEWORK_PATH
    )
  )
);



function framework_autoload($classname)
{
  $ruta = explode('_', $classname);
  if (substr(end($ruta), -10) == 'Controller') {
    $file = strtolower($ruta[0]) . '/Controllers/' . $ruta[1] . '.php';
    if (file_exists(APP_PATH . 'modules/' . $file)) {
      require_once(APP_PATH . 'modules/' . $file);
    }
  } else if (isset($ruta[1]) && $ruta[1] == 'Model') {
    $file = strtolower($ruta[0]) . "/Models/";
    unset($ruta[0]);
    unset($ruta[1]);
    $file = $file . implode("/", $ruta) . '.php';
    if (file_exists(APP_PATH . 'modules/' . $file)) {
      require_once(APP_PATH . 'modules/' . $file);
    }
  } else {
    $file = implode("/", $ruta) . '.php';
    if (file_exists(APP_PATH . '../framework/' . $file)) {
      require_once($file);
    }
  }
}
spl_autoload_register('framework_autoload');

include(APP_PATH . '/../vendor/autoload.php');
$env = "development";
if (strpos($_SERVER['HTTP_HOST'], "xovis.omegasolucionesweb.com") !== false) {
  $env = "staging";
} else if (strpos($_SERVER['HTTP_HOST'], "...") !== false) {
  $env = "production";
}
define('APPLICATION_ENV', getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $env);



error_reporting(E_STRICT);
if ($_GET['debug'] == "1") {
  error_reporting(E_ALL);
}
ini_set("display_errors", 1);

if (!file_exists(IMAGE_PATH)) {
  mkdir(IMAGE_PATH, 0777, true);
}

if (!file_exists(FILE_PATH)) {
  mkdir(FILE_PATH, 0777, true);
}

require_once '../vendor/langs/' . $selected_lang . '.php';


// require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
require_once '../vendor/tcpdf/tcpdf.php';
