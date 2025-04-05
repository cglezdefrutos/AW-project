<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\event\joinEventForm;
use TheBalance\application;
use TheBalance\utils\utilsFactory;

$titlePage = "Apuntarse a un evento";
$mainContent = "";

$success = $_GET['success'] ?? 'false';

$app = application::getInstance();

if(!$app->isCurrentUserLogged())
{
    $mainContent .= utilsFactory::createAlert("No puedes apuntarte a un evento si no has iniciado sesión.", "danger");
}
else if(!$app->isCurrentUserClient())
{
    $mainContent .= utilsFactory::createAlert("No puedes apuntarte a un evento si no eres un cliente.", "danger");
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
        $mainContent .= utilsFactory::createAlert("Te has apuntado al evento correctamente.", "success");
    }

}

require_once __DIR__.'/includes/views/template/template.php';