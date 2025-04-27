<?php

namespace TheBalance\account;

use TheBalance\application;

/**
 * Clase para generar las opciones de la cuenta del usuario.
 */
class myAccountOptions
{
    /**
     * Constructor de la clase.
     */
    public function __construct()
    {

    }

    /**
     * Genera las opciones de la cuenta del usuario.
     * 
     * @return string Opciones de la cuenta en formato HTML.
     */
    public function generateOptions()
    {
        $app = application::getInstance();
        $options = '';

        if ($app->isCurrentUserAdmin()) 
        {
            $options .= <<<EOS
                <li class="list-group-item menu-item" data-section="manageProducts">Administrar Productos</li>
                <li class="list-group-item menu-item" data-section="manageEvents">Administrar Eventos</li>
                <li class="list-group-item menu-item" data-section="managePlans">Administrar Planes</li>
                <li class="list-group-item menu-item" data-section="manageUsers">Administrar Usuarios</li>
                <li class="list-group-item menu-item" data-section="manageOrders">Administrar Pedidos</li>
            EOS;
        } 
        elseif ($app->isCurrentUserClient())
        {  
            $options .= <<<EOS
                <li class="list-group-item menu-item" data-section="myOrders">Mis Pedidos</li>
                <li class="list-group-item menu-item" data-section="myEvents">Mis Eventos</li>
                <li class="list-group-item menu-item" data-section="myPlans">Mis Planes</li>
            EOS;
        }
        elseif ($app->isCurrentUserProvider())
        {
            $options .= <<<EOS
                <li class="list-group-item menu-item" data-section="manageProducts">Mis Productos</li>
                <li class="list-group-item menu-item" data-section="manageEvents">Mis Eventos</li>
            EOS;
        }
        else
        {
            $options .= <<<EOS
                <li class="list-group-item menu-item" data-section="managePlans">Mis Planes</li>
            EOS;
        }

        $options .= <<<EOS
            <li class="list-group-item menu-item" data-section="personalData">Datos Personales</li>
            <li class="list-group-item"><a href="logout.php">Cerrar Sesión</a></li>
        EOS;

        return $options;
    }
}