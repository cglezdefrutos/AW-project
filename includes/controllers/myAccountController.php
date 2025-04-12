<?php

require_once '../config.php';

$section = $_POST['section'] ?? null;

if ($section) {
    switch ($section) {
        case 'welcome':
            echo '<h3>Bienvenido a tu cuenta</h3><p>Selecciona una opción del menú para comenzar.</p>';
            break;

        case 'myProducts':
            include '../account/manageProducts.php'; // Cargar la vista de gestión de productos
            break;

        case 'myEvents':
            include '../account/manageEvents.php'; // Cargar la vista de gestión de eventos
            break;

        case 'orders':
            include '../account/manageOrders.php'; // Cargar la vista de pedidos
            break;

        case 'personalData':
            include '../account/personalData.php'; // Cargar la vista de datos personales
            break;

        default:
            echo '<p>Sección no válida.</p>';
            break;
    }
} else {
    echo '<p>No se ha especificado ninguna sección.</p>';
}