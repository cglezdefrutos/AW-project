<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\application;
use TheBalance\utils\utilsFactory;
use TheBalance\account\myAccountOptions;

$titlePage = 'Mi Cuenta';
$mainContent = "";

$app = application::getInstance();

if(!$app->isCurrentUserLogged())
{
    $mainContent .= utilsFactory::createAlert("Por favor, inicia sesión si quieres ver los detalles de tu cuenta.", "danger");
}
else
{
    $optionsGenerator = new myAccountOptions();
    $options = $optionsGenerator->generateOptions();

    $mainContent .= <<<EOS

        <div id="alertContainer"></div>
        <div class="row">
            <!-- Menú lateral -->
            <div class="col-md-3">
                <h2>Mi Cuenta</h2>
                <ul class="list-group">
                    $options
                </ul>
            </div>
            <!-- Contenido dinámico -->
            <div class="col-md-9" id="content">
                <h5>Selecciona una opción del menú.</h5>
            </div>
            <script src="/AW-project/js/myAccount.js"></script>
            <script src="/AW-project/js/manageProducts.js"></script>
            <script src="/AW-project/js/manageEvents.js"></script>
            <script src="/AW-project/js/manageOrders.js"></script>
            <script src="/AW-project/js/personalData.js"></script>
            <script src="/AW-project/js/manageUsers.js"></script>
            <script src="/AW-project/js/managePlans.js"></script>
        </div>
        
    EOS;
}

require_once BASE_PATH.'/includes/views/template/template.php';