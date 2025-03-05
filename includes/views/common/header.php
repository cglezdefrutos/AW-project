<?php
    function mostrarLogin() 
    {
        if (isset($_SESSION["login"]) && ($_SESSION["login"]===true)) 
        {
            $user = json_decode($_SESSION["user"], true);
            /* echo "Bienvenido, " . $_SESSION["email"] . ". <a href='logout.php'>(salir)</a>"; */
            echo "Bienvenido, " . $user["email"] . ". <a href='logout.php'>(salir)</a>";
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