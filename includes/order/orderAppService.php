<?php

namespace TheBalance\order;

/**
 * Clase que contiene la lógica de la aplicación de orders
 */
class orderAppService
{
    // Patrón Singleton
    /**
     * @var orderAppService Instancia de la clase
     */
    private static $instance;

    /**
     * Devuelve una instancia de {@see orderAppService}.
     * 
     * @return orderAppService Obtiene la única instancia de la <code>orderAppService</code>
     */
    public static function GetSingleton()
    {
        if (!self::$instance instanceof self)
        {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Evita que se pueda instanciar la clase directamente.
     */
    private function __construct()
    {
    } 


}