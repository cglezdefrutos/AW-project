<?php
    require_once("includes/config.php");
    require_once("includes/event/joinEventForm.php");

    $titlePage = "Apuntarse a un evento";
    $mainContent = "";

    if($_GET['success'] != 'true')
    {
        $eventId = $_GET['id'];

        $user = json_decode($_SESSION["user"], true);
        $userId = $user["id"];

        $form = new joinEventForm($eventId, $userId);
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