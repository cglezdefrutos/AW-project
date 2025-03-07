<?php
    require_once("includes/config.php");
    require_once("includes/event/joinEventForm.php");

    $titlePage = "Apuntarse a un evento";
    $mainContent = "";

    $success = $_GET['success'] ?? 'false';

    $user = json_decode($_SESSION["user"], true);
    $userId = $user["id"];
    $userType = $user["usertype"];

    if($userType != 1)
    {
        $mainContent = <<<EOS
            <h1>No es posible apuntarse a eventos si no eres cliente.</h1>
        EOS;
    }
    else
    {
        if(!isset($success) || ($success != 'true'))
        {
            $eventId = $_GET['id'];
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

    }

    require_once("includes/views/template/template.php");
?>