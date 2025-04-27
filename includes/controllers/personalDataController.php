<?php

require_once '../config.php';

use TheBalance\application;
use TheBalance\user\userAppService;
use TheBalance\utils\utilsFactory;

$action = $_POST['action'] ?? null;

if ($action) {
    $userAppService = userAppService::GetSingleton();
    $userId = application::getInstance()->getCurrentUserId();

    switch ($action) {
        case 'getEmail':
            // Obtener el email actual del usuario
            $email = application::getInstance()->getCurrentUserEmail();
            if ($email) {
                echo json_encode(['success' => true, 'email' => htmlspecialchars($email)]);
            } else {
                $alert = utilsFactory::createAlert('No se pudo obtener el email del usuario.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
            }
            break;

        case 'changeEmail':
            // Validar y limpiar el nuevo email
            $newEmail = trim($_POST['newEmail'] ?? '');
            $newEmail = filter_var($newEmail, FILTER_SANITIZE_EMAIL);

            if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                $alert = utilsFactory::createAlert('El email proporcionado no es válido.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }

            // Intentar actualizar el email
            $result = $userAppService->changeEmail($userId, $newEmail);
            if ($result) {
                // Actualizar el email en la sesión
                application::getInstance()->changeCurrentUserEmail($newEmail);
                // Crear la alerta de éxito
                $alert = utilsFactory::createAlert('Email actualizado correctamente.', 'success');
                echo json_encode(['success' => true, 'alert' => $alert]);
            } else {
                $alert = utilsFactory::createAlert('Error al actualizar el email. Es posible que ya esté en uso.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
            }
            break;

        case 'changePassword':
            // Validar y limpiar las contraseñas
            $newPassword = trim($_POST['newPassword'] ?? '');
            $newPassword = filter_var($newPassword, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $repeatNewPassword = trim($_POST['repeatNewPassword'] ?? '');
            $repeatNewPassword = filter_var($repeatNewPassword, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if (empty($newPassword) || strlen($newPassword) < 8) {
                $alert = utilsFactory::createAlert('La nueva contraseña debe tener al menos 8 caracteres.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }

            if ($newPassword !== $repeatNewPassword) {
                $alert = utilsFactory::createAlert('Las contraseñas no coinciden.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }

            // Intentar actualizar la contraseña
            $result = $userAppService->changePassword($userId, $newPassword, $repeatNewPassword);
            if ($result) {
                $alert = utilsFactory::createAlert('Contraseña actualizada correctamente.', 'success');
                echo json_encode(['success' => true, 'alert' => $alert]);
            } else {
                $alert = utilsFactory::createAlert('Error al actualizar la contraseña.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
            }
            break;

        default:
            $alert = utilsFactory::createAlert('Acción no válida.', 'danger');
            echo json_encode(['success' => false, 'alert' => $alert]);
            break;
    }
}