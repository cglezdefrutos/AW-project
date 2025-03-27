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

    /**
     * Devuelve los orders asociados al tipo de usuario
     * 
     * @param string $user_type Tipo de usuario
     * 
     * @return array Resultado de la búsqueda
     */
    public function getOrdersByUserType()
    {
        $IOrderDAO = orderFactory::CreateOrder();
        $ordersDTO = null;

        $app = application::getInstance();

        // Si es administrador, tomamos todos los orders
        if ($app->isCurrentUserAdmin())
        {
            $ordersDTO = $IOrderDAO->getAllOrders();
        }
        // Si es usuario, tomamos SOLO los orders del usuario
        else
        {
            // Tomamos el id del usuario
            $userId = $app->getCurrentUserId();

            // Pasamos como filtro el id del usuario
            $ordersDTO = $IOrderDAO->getOrdersByUserId($userId);
        }

        return $ordersDTO;
    }

}