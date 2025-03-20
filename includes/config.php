<?php

use TheBalance\application;

/**
 * Parámetros de conexión a la BD
 */
/* define('DB_HOST', 'vm012.db.swarm.test');
define('DB_NAME', 'the_balance');
define('DB_USER', 'root');
define('DB_PASS', 'EvbBYyU2kNwH0XUxjWRw'); */

define('DB_HOST', 'localhost');
define('DB_NAME', 'the_balance');
define('DB_USER', 'root');
define('DB_PASS', '');


/**
 * Configuración del soporte de UTF-8, localización (idioma y país) y zona horaria
 */
ini_set('default_charset', 'UTF-8');
setLocale(LC_ALL, 'es_ES.UTF.8');
date_default_timezone_set('Europe/Madrid');

/**
 * Parámetros de configuración utilizados para generar las URLs y las rutas a ficheros en la aplicación
 */
define('RAIZ_APP', dirname(__DIR__));
define('RUTA_APP', '/AW-project');
define('RUTA_IMGS', RUTA_APP.'/img');
define('RUTA_CSS', RUTA_APP.'/css');
define('RUTA_JS', RUTA_APP.'/js');

/**
 * Función para autocargar clases PHP.
 *
 * @see http://www.php-fig.org/psr/psr-4/
 */
spl_autoload_register(function ($class) {
    // Prefijo del namespace del proyecto
    $prefix = 'TheBalance\\';

    // Directorio base para el namespace raíz
    $base_dir = __DIR__ . DIRECTORY_SEPARATOR;

    // Verificar si la clase usa nuestro namespace
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Obtener la parte relativa de la clase
    $relative_class = substr($class, $len);

    // Convertir namespace en ruta de archivos
    $file = str_replace(DIRECTORY_SEPARATOR, '/', $base_dir) . str_replace(DIRECTORY_SEPARATOR, '/', $relative_class) . '.php';

    // Cargar el archivo si existe
    if (file_exists($file)) {
        require_once $file;
    }
});

// Inicializa la aplicación
$app = application::getInstance();
$app->init(array('host'=>DB_HOST, 'db'=>DB_NAME, 'user'=>DB_USER, 'pass'=>DB_PASS));

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

    $mainContent = <<<EOS
        <h1>Oops</h1>
        <p> Parece que ha habido un fallo.</p>
        $errorMessage
    EOS;

    require_once("includes/views/template/template.php");
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