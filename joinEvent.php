<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\event\joinEventForm;
use TheBalance\application;

$titlePage = "Apuntarse a un evento";
$mainContent = "";

$success = $_GET['success'] ?? 'false';

$app = application::getInstance();

if(!$app->isCurrentUserLogged())
{
    $mainContent = <<<EOS
        <h1>No es posible apuntarse a eventos si no has iniciado sesión.</h1>
    EOS;
}
else if(!$app->isCurrentUserClient())
{
    $mainContent = <<<EOS
        <h1>No es posible apuntarse a eventos si no eres cliente.</h1>
    EOS;
}
else
{
    if(!isset($success) || ($success != 'true'))
    {
        $eventId = $_GET['id'] ?? $_POST['eventId'] ?? null;
        if($eventId == null)
        {
            $mainContent = <<<EOS
                <h1>No se ha especificado el evento al que apuntarse.</h1>
            EOS;
        }
        else 
        {
            $userId = $app->getCurrentUserId();
            $form = new joinEventForm($eventId, $userId);
            $htmlJoinEventForm = $form->Manage();
    
            $mainContent = <<<EOS
                <h1>Apuntarse a un evento</h1>
                $htmlJoinEventForm
            EOS;
        }
    }
    else 
    {
        $mainContent = <<<EOS
            <h1>¡Enhorabuena!¡Te has apuntado al evento!</h1>
        EOS;
    }

}

require_once __DIR__.'/includes/views/template/template.php';