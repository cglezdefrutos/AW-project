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

<header>
    <div class="logo">
        <a href="index.php">The Balance</a>
    </div>
    <nav>
        <ul>
            <li><a href="index.php">Shop</a></li>
            <li><a href="searchEvents.php">Events</a></li>
            <li><a href="registerEvents.php">Register Events</a></li>
            <li><a href="manageEvents.php">Manage Events</a></li>
            <li><a href="index.php">Training Plans</a></li>
            <li><a href="index.php">Work with Us</a></li>
        </ul>
    </nav>
    <div class="Login">
        <?php
            mostrarLogin();
        ?>
    </div>
</header>