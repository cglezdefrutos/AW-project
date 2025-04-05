<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\order\orderAppService;
use TheBalance\order\updateOrderForm;
use TheBalance\application;
use TheBalance\utils\utilsFactory;

$titlePage = "Actualizar pedido";
$mainContent = "";

$app = application::getInstance();

if (!$app->isCurrentUserLogged())
{
    $mainContent .= utilsFactory::createAlert("No has iniciado sesión. Por favor, inicia sesión para actualizar pedidos.", "danger");
} 
else 
{
    // Comprobar si el usuario es administrador
    if (! $app->isCurrentUserAdmin() )
    {
        $mainContent .= utilsFactory::createAlert("No tienes permisos para actualizar pedidos. Solo los administradores pueden hacerlo.", "danger");
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
            $mainContent .= utilsFactory::createAlert("No se ha proporcionado un ID de pedido válido.", "danger");
        }
    }
}

require_once __DIR__.'/includes/views/template/template.php';