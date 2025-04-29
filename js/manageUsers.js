
$(document).ready(function () {
    // Abrir modal para editar usuario
    $(document).on('click', '.edit-user', function () {
        const userId = $(this).data('id');
        const userEmail = $(this).data('email');
        const userType = $(this).data('usertype');

        // Rellenar los campos del modal
        $('#editEmail').val(userEmail);
        $('#editUserType').val(userType);
        $('#editUserModal').data('userId', userId);
        $('#editUserModal').modal('show');
    });

    // Guardar cambios en el usuario
    $(document).on('submit', '#editUserForm', function (e) {
        e.preventDefault();
        const userId = $('#editUserModal').data('userId');
        const email = $('#editEmail').val();
        const userType = $('#editUserType').val();

        $.ajax({
            url: '/AW-project/includes/controllers/manageUsersController.php',
            method: 'POST',
            data: { action: 'updateUser', userId: userId, email: email, userType: userType },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    showAlert('success', result.alert);
                    $('#editUserModal').modal('hide');
                    loadUsersTable();
                } else {
                    showAlert('danger', result.alert);
                }
            },
            error: function () {
                showAlert('danger', 'Error al actualizar el usuario.');
            }
        });
    });

    // Eliminar usuario
    $(document).on('click', '.delete-user', function () {
        const userId = $(this).data('id');

        if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
            $.ajax({
                url: '/AW-project/includes/controllers/manageUsersController.php',
                method: 'POST',
                data: { action: 'deleteUser', userId: userId },
                success: function (response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        showAlert('success', result.alert);
                        loadUsersTable();
                    } else {
                        showAlert('danger', result.alert);
                    }
                },
                error: function () {
                    showAlert('danger', 'Error al eliminar el usuario.');
                }
            });
        }
    });

    // Función para recargar la tabla de usuarios
    function loadUsersTable() {
        $.ajax({
            url: '/AW-project/includes/account/manageUsers.php',
            method: 'GET',
            success: function (response) {
                $('#content').html(response); // Reemplazar el contenido de la tabla
            },
            error: function () {
                showAlert('danger', 'Error al recargar la tabla de usuarios.');
            }
        });
    }

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