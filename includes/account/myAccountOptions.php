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

        $options .= <<<EOS
            <li class="list-group-item menu-item" data-section="welcome">Bienvenido</li>
        EOS;

        if ($app->isCurrentUserAdmin()) 
        {
            $options .= <<<EOS
                <li class="list-group-item menu-item" data-section="adminProducts">Administrar Productos</li>
                <li class="list-group-item menu-item" data-section="adminEvents">Administrar Eventos</li>
                <li class="list-group-item menu-item" data-section="adminPlans">Administrar Planes</li>
                <li class="list-group-item menu-item" data-section="adminUsers">Administrar Usuarios</li>
            EOS;
        } 
        elseif ($app->isCurrentUserClient())
        {  
            $options .= <<<EOS
                <li class="list-group-item menu-item" data-section="orders">Mis Pedidos</li>
            EOS;
        }
        elseif ($app->isCurrentUserProvider())
        {
            $options .= <<<EOS
                <li class="list-group-item menu-item" data-section="myProducts">Mis Productos</li>
                <li class="list-group-item menu-item" data-section="myEvents">Mis Eventos</li>
            EOS;
        }
        else
        {
            $options .= <<<EOS
                <li class="list-group-item menu-item" data-section="myPlans">Mis Planes</li>
            EOS;
        }

        $options .= <<<EOS
            <li class="list-group-item menu-item" data-section="personalData">Datos Personales</li>
            <li class="list-group-item"><a href="logout.php">Cerrar Sesión</a></li>
        EOS;

        return $options;
    }
}