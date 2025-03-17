<?php

namespace TheBalance;

/**
 * Clase que mantiene el estado global de la aplicación.
 */
class application
{
    // Constantes
    /** 
     * @var string Clave para almacenar los atributos de la petición en la sesión
     */
    const REQUEST_ATTRIBUTES = 'requestAtts';

    // Patrón Singleton
    /**
     * @var Aplicacion Instancia de la aplicación
     */
    private static $instance;
    
	/**
	 * Devuele una instancia de {@see Aplicacion}.
	 * 
	 * @return Applicacion Obtiene la única instancia de la <code>Aplicacion</code>
	 */    
    public static function getInstance() 
    {
        if ( !self::$instance instanceof self ) 
        {
            self::$instance = new static();
        }
        
        return self::$instance;
    }

	/**
	 * Evita que se pueda instanciar la clase directamente.
	 */    
    private function __construct()
    {
    }

    // Atributos
	/**
	 * @var array Almacena los datos de configuración de la BD
	 */    
    private $dbConnectionData;

	/**
	 * Almacena si la Aplicacion ya ha sido inicializada.
	 * 
	 * @var boolean
	 */    
    private $init = false;
    
	/**
	 * @var \mysqli Conexión de BD.
	 */    
    private $conn;
    
	/**
	 * @var array Tabla asociativa con los atributos pendientes de la petición. Es decir, almacena los atributos de la petición
	 */    
    private $requestAttributes;
    
	/**
	 * Inicializa la aplicación.
     *
     * Opciones de conexión a la BD:
     * <table>
     *   <thead>
     *     <tr>
     *       <th>Opción</th>
     *       <th>Descripción</th>
     *     </tr>
     *   </thead>
     *   <tbody>
     *     <tr>
     *       <td>host</td>
     *       <td>IP / dominio donde se encuentra el servidor de BD.</td>
     *     </tr>
     *     <tr>
     *       <td>bd</td>
     *       <td>Nombre de la BD que queremos utilizar.</td>
     *     </tr>
     *     <tr>
     *       <td>user</td>
     *       <td>Nombre de usuario con el que nos conectamos a la BD.</td>
     *     </tr>
     *     <tr>
     *       <td>pass</td>
     *       <td>Contraseña para el usuario de la BD.</td>
     *     </tr>
     *   </tbody>
     * </table>
	 * 
	 * @param array $dbConnectionData datos de configuración de la BD
	 */
    public function init($dbConnectionData)
    {
        if ( ! $this->init ) 
        {
            $this->dbConnectionData = $dbConnectionData;
            $this->init = true;
            session_start();

			/** 
            * Se inicializa los atributos asociados a la petición en base a la sesión y se eliminan para que
			* no estén disponibles después de la gestión de esta petición.
			*/
            $this->requestAttributes = $_SESSION[self::REQUEST_ATTRIBUTES] ?? [];
            unset($_SESSION[self::REQUEST_ATTRIBUTES]);
        }
    }
    
	/**
	 * Cierre de la aplicación (se cierra la conexión a la BD).
	 */
    public function shutdown()
    {
        $this->checkInitializedInstance();
        
        if ($this->conn !== null && ! $this->conn->connect_errno) 
        {
            $this->conn->close();
        }
    }
    
	/**
	 * Comprueba si la aplicación está inicializada. Si no lo está muestra un mensaje y termina la ejecución.
	 */
    private function checkInitializedInstance()
    {
        if (! $this->init ) 
        {
            echo "Aplicacion no inicializa";
            exit();
        }
    }
    
	/**
	 * Devuelve una conexión a la BD. Se encarga de que exista como mucho una conexión a la BD por petición.
	 * 
	 * @return \mysqli Conexión a MySQL.
	 */
    public function getConnectionDb()
    {
        $this->checkInitializedInstance();
        
        if (! $this->conn ) 
        {
            $dbHost = $this->dbConnectionData['host'];
            $dbUser = $this->dbConnectionData['user'];
            $dbPass = $this->dbConnectionData['pass'];
            $db     = $this->dbConnectionData['db'];
            
            //$driver = new mysqli_driver();
            
            //$driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;

            $conn = new \mysqli($dbHost, $dbUser, $dbPass, $db);
            
            if ( $conn->connect_errno ) 
            {
                echo "Error de conexión a la BD ({$conn->connect_errno}):  {$conn->connect_error}";
                exit();
            }
            
            if ( ! $conn->set_charset("utf8mb4")) 
            {
                echo "Error al configurar la BD ({$conn->errno}):  {$conn->error}";
                exit();
            }
            
            $this->conn = $conn;
        }
        
        return $this->conn;
    }

	/**
	 * Añade un atributo <code>$value</code> para que esté disponible en la siguiente petición bajo la clave <code>$key</code>.
	 * 
	 * @param string $key Clave bajo la que almacenar el atributo.
	 * @param any    $value Valor a almacenar como atributo de la petición.
	 * 
	 */
    public function putRequestAttribute($key, $value)
    {
        $atts = null;
        
        if (isset($_SESSION[self::REQUEST_ATTRIBUTES])) 
        {
            $atts = &$_SESSION[self::REQUEST_ATTRIBUTES];
        } 
        else 
        {
            $atts = array();
            $_SESSION[self::REQUEST_ATTRIBUTES] = &$atts;
        }

        $atts[$key] = $value;
    }

	/**
	 * Devuelve un atributo establecido en la petición actual o en la petición justamente anterior.
	 * 
	 * 
	 * @param string $key Clave sobre la que buscar el atributo.
	 * 
	 * @return any Atributo asociado a la sesión bajo la clave <code>$key</code> o <code>null</code> si no existe.
	 */
    public function getRequestAttribute($key)
    {
        $result = $this->requestAttributes[$key] ?? null;
        
        if(is_null($result) && isset($_SESSION[self::REQUEST_ATTRIBUTES])) 
        {
            $result = $_SESSION[self::REQUEST_ATTRIBUTES][$key] ?? null;
        }
        
        return $result;
    }
}