<?php

require_once("includes/config.php");

use TheBalance\event\registerEventForm;

// Comprobar si se ha pulsado el botón de registrar otro evento y resetear la sesión antes de renderizar la página
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["registerAnother"])) {
    header("Location: " . $_SERVER["PHP_SELF"]); // Recargar la página para mostrar el formulario de nuevo
    exit();
}

$titlePage = "Registrar eventos";
$mainContent = "";

if(!isset($_SESSION["user"])) 
{
    $mainContent = <<<EOS
        <h1>No es posible registrar un evento si no has iniciado sesión.</h1>
    EOS;
} 
else 
{
    $userDTO = json_decode($_SESSION["user"], true);
    $user_email = htmlspecialchars($userDTO["email"]);
    $user_type = htmlspecialchars($userDTO["usertype"]);

    // Comprobar si el usuario es proveedor o administrador
    if($user_type != 2 && $user_type != 0)
    {
        $mainContent = <<<EOS
            <h1>No es posible registrar un evento si no se es proveedor o administrador.</h1>
        EOS;
    }
    else
    {
        // Comprobar si el evento ya ha sido registrado
        if (isset($_GET["registered"]) && $_GET["registered"] === "true") 
        {
            $mainContent = <<<EOS
                <h1>Evento registrado correctamente.</h1>
                <form method="post">
                    <button type="submit" name="registerAnother">Registrar otro evento</button>
                    <a href="index.php">
                        <button type="button">Volver a inicio</button>
                    </a>
                </form>
            EOS;
        }
        else
        {
            $form = new registerEventForm($user_email);
            $htmlRegisterEventForm = $form->Manage();

            $mainContent = <<<EOS
                <h1>Registre un evento</h1>
                $htmlRegisterEventForm
            EOS;
        }
    }

}

require_once("includes/views/template/template.php");

