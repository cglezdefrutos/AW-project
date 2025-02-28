<?php
    require_once("includes/config.php");
    require_once("includes/event/joinEventForm.php");

    $titlePage = "Apuntarse a un evento";
    $mainContent = "";

    // Verificamos si el usuario está logueado
    if (isset($_SESSION["user_id"])) {
        $mainContent = <<<EOS
            <p>Debes iniciar sesión para apuntarte a un evento</p>
            <a href="login.php"><button>Iniciar sesión</button></a>
        EOS;
    }
    // Si no se ha enviado el formulario todavía
    else if(!isset($_SESSION["sentJoinEvent"]))
    {
        // Verificar si se ha pasado un ID de evento
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) 
        {
            $mainContent = "<p>Error: Evento no válido.</p>";
        }
        else
        {
            $eventId = $_GET['id'];
            $form = new joinEventForm($eventId);
            $htmlJoinEventForm = $form->Manage();

            $mainContent = <<<EOS
                <h1>Apuntarse a un evento</h1>
                $htmlJoinEventForm
            EOS;
        }
    }
    // Si ya se ha enviado el formulario correctamente
    else
    {
        $mainContent = "<p>Te has apuntado al evento correctamente.</p>";
    }

    require_once("includes/views/template/template.php");
?>