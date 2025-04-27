<?php

require_once '../config.php';

use TheBalance\user\personalDataModal;
use TheBalance\application;

$app = application::getInstance();

if (!$app->isCurrentUserLogged()) 
{
    echo utilsFactory::createAlert("No has iniciado sesión. Por favor, inicia sesión para ver tus pedidos.", "danger");
} 
else
{
    echo <<<EOS
        <div class="container mt-4">
            <h2>Datos Personales</h2>
            <div class="row">
                <div class="col-md-6">
                    <button id="changeEmailButton" class="btn btn-primary w-100 mb-3">Cambiar Email</button>
                </div>
                <div class="col-md-6">
                    <button id="changePasswordButton" class="btn btn-primary w-100">Cambiar Contraseña</button>
                </div>
            </div>
        </div>
    EOS;

    // Generar los modales
    echo personalDataModal::generateChangeEmailModal();
    echo personalDataModal::generateChangePasswordModal();
}