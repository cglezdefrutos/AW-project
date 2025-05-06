<?php

use TheBalance\application;
use TheBalance\utils\utilsFactory;

/**
 * Parámetros de conexión a la BD
 */
define('DB_HOST', 'vm012.db.swarm.test');
define('DB_NAME', 'the_balance');
define('DB_USER', 'root');
define('DB_PASS', 'EvbBYyU2kNwH0XUxjWRw');

/* define('DB_HOST', 'localhost');
define('DB_NAME', 'the_balance');
define('DB_USER', 'root');
define('DB_PASS', ''); */


/**
 * Configuración del soporte de UTF-8, localización (idioma y país) y zona horaria
 */
ini_set('default_charset', 'UTF-8');
setLocale(LC_ALL, 'es_ES.UTF.8');
date_default_timezone_set('Europe/Madrid');

/**
 * Parámetros de configuración utilizados para generar las URLs y las rutas a ficheros en la aplicación
 */
define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', '/AW-project/');
define('IMG_PATH', BASE_URL.'img');
define('CSS_PATH', BASE_URL.'css');
define('JS_PATH', BASE_URL.'js');

/**
 * Parámetros de configuración de Stripe
 * 
 * @see https://stripe.com/docs/keys
 */
define('STRIPE_SECRET_KEY', 'sk_test_51R7x6U9DyzOhuTL3kJyeV8AMQCRPV9MLDaYRlaQxE403NLLUq9HpEMoPjQqRVrA8SOeAGKlA8l7AkqYdtd15lbCS00KQYgmwIq'); // Reemplaza con tu clave real
define('STRIPE_PUBLIC_KEY', 'pk_test_51R7x6U9DyzOhuTL31NiHXH9x47iAXtetYn98Xmj053HpGFP27xb03rRe828ZWZoCL9o0vnjw7m1MIkWvngFFSZbk00LQf8rlvR');


/**
 * Función para autocargar clases PHP.
 *
 * @see http://www.php-fig.org/psr/psr-4/
 */
spl_autoload_register(function ($class) {
    // Prefijo del namespace del proyecto
    $prefix = 'TheBalance\\';

    // Directorio base para el namespace raíz
    $base_dir = __DIR__ . "/";

    // Verificar si la clase usa nuestro namespace
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Obtener la parte relativa de la clase
    $relative_class = substr($class, $len);

    // Convertir namespace en ruta de archivos
    $file = str_replace('\\', '/', $base_dir) . str_replace("\\", '/', $relative_class) . '.php';

    // Cargar el archivo si existe
    if (file_exists($file)) {
        require_once $file;
    }
});

// Inicializa la aplicación
$app = application::getInstance();
$app->init(array('host'=>DB_HOST, 'db'=>DB_NAME, 'user'=>DB_USER, 'pass'=>DB_PASS));

/**
 * Configurar el entorno de desarrollo
 * APP_ENV = development => Muestra errores
 * APP_ENV = production => No muestra errores
 */
putenv('APP_ENV=development');

/**
 * @see http://php.net/manual/en/function.register-shutdown-function.php
 * @see http://php.net/manual/en/language.types.callable.php
 */
register_shutdown_function([$app, 'shutdown']);

/**
 * Manejador de excepciones
 */
function exceptionHandler(Throwable $exception) 
{
    error_log(jTraceEx($exception)); 

    http_response_code(500);

    $titlePage = 'Error';
    $errorMessage = $exception->getMessage();

    $mainContent = utilsFactory::generateErrorContent($errorMessage);

    require_once BASE_PATH.'/includes/views/template/template.php';
}

// Establecer el manejador de excepciones
set_exception_handler('exceptionHandler');

// http://php.net/manual/es/exception.gettraceasstring.php#114980
/**
 * jTraceEx() - provide a Java style exception trace
 * @param Throwable $exception
 * @param string[] $seen Array passed to recursive calls to accumulate trace lines already seen leave as NULL when calling this function
 * @return string  string stack trace, one entry per trace line
 */
function jTraceEx($e, $seen=null) 
{
    $starter = $seen ? 'Caused by: ' : '';
    $result = array();
    if (!$seen) $seen = array();
    $trace  = $e->getTrace();
    $prev   = $e->getPrevious();
    $result[] = sprintf('%s%s: %s', $starter, get_class($e), $e->getMessage());
    $file = $e->getFile();
    $line = $e->getLine();
    while (true) {
        $current = "$file:$line";
        if (is_array($seen) && in_array($current, $seen)) {
            $result[] = sprintf(' ... %d more', count($trace)+1);
            break;
        }
        $result[] = sprintf(' at %s%s%s(%s%s%s)',
            count($trace) && array_key_exists('class', $trace[0]) ? str_replace('\\', '.', $trace[0]['class']) : '',
            count($trace) && array_key_exists('class', $trace[0]) && array_key_exists('function', $trace[0]) ? '.' : '',
            count($trace) && array_key_exists('function', $trace[0]) ? str_replace('\\', '.', $trace[0]['function']) : '(main)',
            $line === null ? $file : basename($file),
            $line === null ? '' : ':',
            $line === null ? '' : $line);
        if (is_array($seen))
            $seen[] = "$file:$line";
        if (!count($trace))
            break;
        $file = array_key_exists('file', $trace[0]) ? $trace[0]['file'] : 'Unknown Source';
        $line = array_key_exists('file', $trace[0]) && array_key_exists('line', $trace[0]) && $trace[0]['line'] ? $trace[0]['line'] : null;
        array_shift($trace);
    }
    $result = join(PHP_EOL , $result);
    if ($prev)
        $result  .= PHP_EOL . jTraceEx($prev, $seen);
        
    return $result;
}