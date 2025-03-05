<?php

    class application
    {
        // Patrón Singleton
        private static $instance;
        
        public static function getInstance() 
        {
            if ( !self::$instance instanceof self ) 
            {
                self::$instance = new static();
            }
            
            return self::$instance;
        }

        private function __construct()
        {
        }

        // Atributos y constantes
        private $dbConnectionData;                  // Almacena datos de conexión a la base de datos
        private $init = false;                      // Indica si la aplicación ha sido inicializada
        private $conn;                              // Almacena la conexión a la base de datos
        private $requestAttributes;                 // Almacena los atributos de la petición
        const REQUEST_ATTRIBUTES = 'requestAtts';   // Clave para almacenar los atributos de la petición en la sesión
        
        // Inicializa la aplicación con los datos de conexión a la base de datos
        public function init($dbConnectionData)
        {
            if ( ! $this->init ) 
            {
                $this->dbConnectionData = $dbConnectionData;
                $this->init = true;
                session_start();
                $this->requestAttributes = $_SESSION[self::REQUEST_ATTRIBUTES] ?? [];
                unset($_SESSION[self::REQUEST_ATTRIBUTES]);
            }
        }
        
        // Cierra la conexión a la base de datos
        public function shutdown()
        {
            $this->checkInitializedInstance();
            
            if ($this->conn !== null && ! $this->conn->connect_errno) 
            {
                $this->conn->close();
            }
        }
        
        // Comprueba si la instancia de la aplicación ha sido inicializada
        private function checkInitializedInstance()
        {
            if (! $this->init ) 
            {
                echo "Aplicacion no inicializa";
                exit();
            }
        }
        
        // Devuelve la conexión a la base de datos
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

                $conn = new mysqli($dbHost, $dbUser, $dbPass, $db);
                
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

        // Almacena un atributo de la petición
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

        // Devuelve un atributo de la petición
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
?>