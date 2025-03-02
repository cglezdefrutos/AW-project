<?php
    require_once("includes/config.php");
    require_once("includes/event/joinEventForm.php");

    echo "Session ID en joinEvent.php: " . session_id() . "<br>";
    echo "Contenido de la sesión: ";
    var_dump($_SESSION);
    var_dump($_SESSION["sentJoinEvent"]);

    $titlePage = "Apuntarse a un evento";
    $mainContent = "";

    if (!isset($_SESSION["sentJoinEvent"])) 
    {
        $eventId = $_GET['id'];

        $form = new joinEventForm($eventId);
        $htmlJoinEventForm = $form->Manage();

        $mainContent = <<<EOS
            <h1>Apuntarse a un evento</h1>
            $htmlJoinEventForm
        EOS;
    }
    else 
    {
        $mainContent = <<<EOS
            <h1>¡Enhorabuena!¡Te has apuntado al evento!</h1>
        EOS;
    }

    require_once("includes/views/template/template.php");
?>