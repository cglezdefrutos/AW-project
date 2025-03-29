<?php

use TheBalance\application;

/**
 * Muestra el login o el nombre del usuario si está logueado
 */
function mostrarLogin() 
{
    $app = application::getInstance();
    
    if ($app->isCurrentUserLogged()) 
    {
        $email = $app->getCurrentUserEmail();
        echo "Bienvenido, " . $email . ". <a href='logout.php'>(salir)</a>";
    }
    else 
    {
        echo "Usuario desconocido. <a href='login.php'>Login.</a> <a href='register.php'>Registro</a>";
    }
}

?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <div class="container-fluid">
        <!-- Botón de colapso para pantallas pequeñas -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menú desplegable -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <!-- Opción Catálogo -->
                <li class="nav-item">
                    <a class="nav-link" href="catalog.php">Catálogo</a>
                </li>

                <!-- Dropdown Eventos -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="eventosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Eventos
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="eventosDropdown">
                        <li><a class="dropdown-item" href="searchEvents.php">Apuntarse</a></li>
                        <li><a class="dropdown-item" href="registerEvents.php">Registrar</a></li>
                        <li><a class="dropdown-item" href="manageEvents.php">Gestionar</a></li>
                    </ul>
                </li>

                <!-- Dropdown Pedidos -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="pedidosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Pedidos
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="pedidosDropdown">
                        <li><a class="dropdown-item" href="index.php">Mis Pedidos</a></li>
                        <li><a class="dropdown-item" href="index.php">Gestionar</a></li>
                    </ul>
                </li>

                <!-- Opción Carrito -->
                <li class="nav-item">
                    <a class="nav-link" href="cart.php">Carrito</a>
                </li>
            </ul>
        </div>

        <!-- Saludo al usuario -->
        <div class="text-light">
            <?php mostrarLogin(); ?>
        </div>
    </div>
</nav>