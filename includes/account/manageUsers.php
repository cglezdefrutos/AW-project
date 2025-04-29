<?php

require_once '../config.php';

use TheBalance\application;
use TheBalance\user\manageUserModal;
use TheBalance\user\userAppService;
use TheBalance\user\manageUsersTable;
use TheBalance\utils\utilsFactory;

$app = application::getInstance();

if (!$app->isCurrentUserAdmin()) 
{
    echo utilsFactory::createAlert("No tienes permisos para acceder a esta sección.", "danger");
}
else
{
    // Obtenemos la instancia del servicio de usuarios
    $userAppService = userAppService::GetSingleton();

    $usersDTO = $userAppService->getUsers();

    // Definir las columnas de la tabla
    $columns = ['Id', 'Email del Usuario', 'Tipo de Usuario', 'Acciones'];
    $usersTable = new manageUsersTable($usersDTO, $columns);
    $html = $usersTable->generateTable();

    echo <<<EOS
        <div class="container mt-4">
            <h2>Gestión de usuarios</h2>
            $html
        </div>
    EOS;

    // Generar el modal para editar email
    echo manageUserModal::generateEditUserModal();
}