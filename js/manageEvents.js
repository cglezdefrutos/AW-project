$(document).ready(function () {
    // Delegar el evento click para el botón "Editar"
    $(document).on('click', '.edit-event', function () {
        const eventId = $(this).data('id'); // Obtener el ID del evento

        // Realizar una solicitud AJAX para obtener los datos del evento
        $.ajax({
            url: '/AW-project/includes/controllers/manageEventsController.php',
            method: 'POST',
            data: { action: 'getEvent', eventId: eventId },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    const event = result.data;

                    // Llenar el formulario del modal con los datos del evento
                    $('#eventId').val(event.id);
                    $('#eventName').val(event.name);
                    $('#eventDescription').val(event.description);
                    $('#eventDate').val(event.date);
                    $('#eventLocation').val(event.location);
                    $('#eventPrice').val(event.price);
                    $('#eventCapacity').val(event.capacity);
                    $('#eventCategory').val(event.category);

                    // Abrir el modal
                    $('#editEventModal').modal('show');
                } else {
                    showAlert('danger', result.alert); // Mostrar alerta de error
                }
            },
            error: function () {
                showAlert('danger', 'Error al cargar los datos del evento.');
            }
        });
    });

    // Interceptar el envío del formulario para guardar cambios
    $(document).on('submit', '#editEventForm', function (e) {
        e.preventDefault(); // Evitar el envío estándar del formulario

        // Enviar los datos del formulario mediante AJAX
        $.ajax({
            url: '/AW-project/includes/controllers/manageEventsController.php',
            method: 'POST',
            data: $(this).serialize() + '&action=updateEvent', // Agregar la acción
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    showAlert('success', result.alert); // Mostrar alerta de éxito
                    $('#editEventModal').modal('hide'); // Cerrar el modal
                    loadEventsTable(); // Recargar la tabla de eventos
                } else {
                    showAlert('danger', result.alert); // Mostrar alerta de error
                }
            },
            error: function () {
                showAlert('danger', 'Error al guardar los cambios del evento.');
            }
        });
    });

    // Delegar el evento click para el botón "Eliminar"
    $(document).on('click', '.delete-event', function () {
        const eventId = $(this).data('id'); // Obtener el ID del evento

        if (confirm('¿Estás seguro de que deseas eliminar este evento?')) {
            // Realizar una solicitud AJAX para eliminar el evento
            $.ajax({
                url: '/AW-project/includes/controllers/manageEventsController.php',
                method: 'POST',
                data: { action: 'deleteEvent', eventId: eventId },
                success: function (response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        showAlert('success', result.alert); // Mostrar alerta de éxito
                        loadEventsTable(); // Recargar la tabla de eventos
                    } else {
                        showAlert('danger', result.alert); // Mostrar alerta de error
                    }
                },
                error: function () {
                    showAlert('danger', 'Error al eliminar el evento.');
                }
            });
        }
    });

    // Función para recargar la tabla de eventos
    function loadEventsTable() {
        $.ajax({
            url: '/AW-project/includes/account/manageEvents.php',
            method: 'GET',
            success: function (response) {
                $('#content').html(response); // Reemplazar el contenido de la tabla
            },
            error: function () {
                showAlert('danger', 'Error al recargar la tabla de eventos.');
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