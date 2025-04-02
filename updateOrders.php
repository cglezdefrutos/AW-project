<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\order\orderAppService;
use TheBalance\order\updateOrderForm;
use TheBalance\application;

$titlePage = "Actualizar pedido";
$mainContent = "";

$app = application::getInstance();

if (!$app->isCurrentUserLogged())
{
    $mainContent = <<<EOS
        <h1>No es posible actualizar eventos si no has iniciado sesión.</h1>
    EOS;
} 
else 
{
    // Comprobar si el usuario es administrador
    if (! $app->isCurrentUserAdmin() )
    {
        $mainContent = <<<EOS
            <h1>No es posible actualizar eventos si no se es administrador.</h1>
        EOS;
    } 
    else 
    {
        // Manejar la actualización del evento si se proporciona un orderId en la URL o al enviar el formulario
        $orderId = $_GET['orderId'] ?? $_POST['orderId'] ?? null;

        if ($orderId) 
        {
            // Obtenemos la instancia del servicio de eventos
            $orderAppService = orderAppService::GetSingleton();
            $order = $orderAppService->getOrderById($orderId);

            // Creamos el formulario
            $form = new updateOrderForm($order);
            $htmlUpdateEventForm = $form->Manage();

            // Mostrar el formulario con los datos del evento
            $mainContent = <<<EOS
                <h1>Actualice el estado del pedido</h1>
                $htmlUpdateEventForm
            EOS;
        } 
        else 
        {
            $mainContent = "<p>No se ha proporcionado un ID de pedido válido.</p>";
        }
    }
}

require_once __DIR__.'/includes/views/template/template.php';