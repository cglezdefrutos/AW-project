<?php

require_once '../config.php';

use TheBalance\application;
use TheBalance\user\userAppService;
use TheBalance\utils\utilsFactory;

$action = $_POST['action'] ?? null;

if ($action) {
    $userAppService = userAppService::GetSingleton();
    $app = application::getInstance();

    if (!$app->isCurrentUserAdmin()) {
        $alert = utilsFactory::createAlert('No tienes permisos para realizar esta acción.', 'danger');
        echo json_encode(['success' => false, 'alert' => $alert]);
        exit;
    }

    switch ($action) {
        case 'deleteUser':
            // Eliminar un usuario
            $userId = intval($_POST['userId']);
            $currentUserId = $app->getCurrentUserId(); // Obtener el ID del usuario actual

            // Verificar si el admin intenta eliminar su propia cuenta
            if ($userId === $currentUserId) {
                $alert = utilsFactory::createAlert('No puedes eliminar tu propia cuenta.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }
            
            $result = $userAppService->deleteUser($userId);

            if ($result) {
                $alert = utilsFactory::createAlert('Usuario eliminado correctamente.', 'success');
                echo json_encode(['success' => true, 'alert' => $alert]);
            } else {
                $alert = utilsFactory::createAlert('Error al eliminar el usuario.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
            }
            break;

        case 'updateUser':
            $userId = intval($_POST['userId']);
            $currentUserId = $app->getCurrentUserId(); // Obtener el ID del usuario actual

            // Verificar si el admin intenta editar sus propios datos
            if ($userId === $currentUserId) {
                $alert = utilsFactory::createAlert('No puedes editar tus propios datos desde esta sección. Edítalos desde la opción Datos Personales', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }

            $newEmail = trim($_POST['email'] ?? '');
            $newEmail = filter_var($newEmail, FILTER_SANITIZE_EMAIL);
            $userType = intval($_POST['userType']);
        
            if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                $alert = utilsFactory::createAlert('El email proporcionado no es válido.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }
        
            if (!in_array($userType, [0, 1, 2, 3])) {
                $alert = utilsFactory::createAlert('El tipo de usuario seleccionado no es válido.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }
        
            $emailResult = $userAppService->changeEmail($userId, $newEmail);
            $typeResult = $userAppService->changeUserType($userId, $userType);
        
            if ($emailResult && $typeResult) {
                $alert = utilsFactory::createAlert('Usuario actualizado correctamente.', 'success');
                echo json_encode(['success' => true, 'alert' => $alert]);
            } else {
                $alert = utilsFactory::createAlert('Error al actualizar el usuario.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
            }
            break;

        default:
            $alert = utilsFactory::createAlert('Acción no válida.', 'danger');
            echo json_encode(['success' => false, 'alert' => $alert]);
            break;
    }
}