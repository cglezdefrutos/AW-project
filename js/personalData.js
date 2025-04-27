$(document).ready(function () {
    // Abrir modal para cambiar email
    $(document).on('click', '#changeEmailButton', function () {
        // Obtener el email actual del usuario mediante AJAX
        $.ajax({
            url: '/AW-project/includes/controllers/personalDataController.php',
            method: 'POST',
            data: { action: 'getEmail' },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    $('#newEmail').val(result.email); // Rellenar el campo con el email actual
                    $('#changeEmailModal').modal('show'); // Mostrar el modal
                } else {
                    showAlert('danger', result.alert);
                }
            },
            error: function () {
                showAlert('danger', 'Error al obtener el email actual.');
            }
        });
    });

    // Abrir modal para cambiar contraseña
    $(document).on('click', '#changePasswordButton', function () {
        $('#changePasswordModal').modal('show'); // Mostrar el modal
    });

    // Cambiar Email
    $(document).on('submit', '#changeEmailForm', function (e) {
        e.preventDefault();
        const newEmail = $('#newEmail').val();

        $.ajax({
            url: '/AW-project/includes/controllers/personalDataController.php',
            method: 'POST',
            data: { action: 'changeEmail', newEmail: newEmail },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    showAlert('success', result.alert);
                    $('#changeEmailModal').modal('hide');
                } else {
                    showAlert('danger', result.alert);
                }
            },
            error: function () {
                showAlert('danger', 'Error al actualizar el email.');
            }
        });
    });

    // Cambiar Contraseña
    $(document).on('submit', '#changePasswordForm', function (e) {
        e.preventDefault();
        const newPassword = $('#newPassword').val();
        const repeatNewPassword = $('#repeatNewPassword').val();

        $.ajax({
            url: '/AW-project/includes/controllers/personalDataController.php',
            method: 'POST',
            data: {
                action: 'changePassword',
                newPassword: newPassword,
                repeatNewPassword: repeatNewPassword
            },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    showAlert('success', result.alert);
                    $('#changePasswordModal').modal('hide');
                } else {
                    showAlert('danger', result.alert);
                }
            },
            error: function () {
                showAlert('danger', 'Error al actualizar la contraseña.');
            }
        });
    });

    // Función para mostrar alertas personalizadas
    function showAlert(type, message) {
        // Verificar si el mensaje ya es una alerta en HTML
        if (message.includes('class="alert')) {
            $('#alertContainer').html(message); // Insertar directamente la alerta
        } else {
            // Construir la alerta si no es HTML
            const alertHTML = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
            $('#alertContainer').html(alertHTML);
        }
    
        // Hacer que la alerta desaparezca automáticamente después de 5 segundos
        setTimeout(() => {
            $('.alert').alert('close');
        }, 5000);
    }
});