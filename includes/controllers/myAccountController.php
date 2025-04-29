<?php

require_once '../config.php';

$section = $_POST['section'] ?? null;

if ($section) {
    switch ($section) {
        case 'manageProducts':
            include '../account/manageProducts.php'; // Cargar la vista de gestión de productos
            break;

        case 'manageEvents':
            include '../account/manageEvents.php'; // Cargar la vista de gestión de eventos
            break;

        case 'manageOrders':
            include '../account/manageOrders.php'; // Cargar la vista de pedidos
            break;
        
        case 'managePlans':
            include '../account/managePlans.php'; // Cargar la vista de gestión de planes
            break;

        case 'manageUsers':
            include '../account/manageUsers.php'; // Cargar la vista de gestión de usuarios
            break;

        case 'myOrders':
            include '../account/myOrders.php'; // Cargar la vista de pedidos
            break;
        
        case 'myEvents':
            include '../account/myEvents.php'; // Cargar la vista de eventos
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