<?php
    require_once("includes/config.php");
    require_once("includes/event/joinEventForm.php");

    $titlePage = "Apuntarse a un evento";
    $mainContent = "";

    if(!isset($_SESSION["sentJoinEvent"]))
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
    else
    {
        $mainContent = "<p>Te has apuntado al evento correctamente.</p>";
    }

    require_once("includes/views/template/template.php");
?>