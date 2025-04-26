<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\plan\registerPlanForm;
use TheBalance\plan\registerAnotherPlanForm;
use TheBalance\application;
use TheBalance\utils\utilsFactory;

$titlePage = "Registrar plan de entrenamiento";
$mainContent = "";

$app = application::getInstance();

if(!$app->isCurrentUserLogged())
{
    $mainContent .= utilsFactory::createAlert("No has iniciado sesión. Por favor, inicia sesión para registrar planes.", "danger");
} 
else 
{
    // Comprobar si el usuario es proveedor o administrador
    if(!$app->isCurrentUserTrainer() && !$app->isCurrentUserAdmin())
    {
        $mainContent .= utilsFactory::createAlert("No tienes permisos para registrar productos. Solo los entrenadores y administradores pueden hacerlo.", "danger");
    }
    else
    {
        // Comprobar si el producto ya ha sido registrado
        if (isset($_GET["registered"]) && $_GET["registered"] === "true") 
        {
            $form = new registerAnotherPlanForm();
            $htmlRegisterAnotherPlanForm = $form->Manage();

            // Alerta de éxito
            $alert = utilsFactory::createAlert("El plan de entrenamiento ha sido registrado correctamente.", "success");

            $mainContent = <<<EOS
                $alert
                $htmlRegisterAnotherPlanForm
            EOS;
        }
        else
        {
            $trainer_id = $app->getCurrentUserId();
            $trainer_email = $app->getCurrentUserEmail();
            $form = new registerPlanForm($trainer_id, $trainer_email);
            $htmlRegisterPlanForm = $form->Manage();

            $mainContent = <<<EOS
                <h1 class="mb-4">Registrar nuevo plan de entrenamiento</h1>
                $htmlRegisterPlanForm
            EOS;
        }
    }
}

require_once BASE_PATH.'/includes/views/template/template.php';