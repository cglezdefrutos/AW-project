$(document).ready(function () {
    // Delegar el evento click para el botón "Editar"
    $(document).on('click', '.managePlan', function () {
        const planId = $(this).data('id'); // Obtener el ID del plan

        // Realizar una solicitud AJAX para obtener los datos del plan
        $.ajax({
            url: '/AW-project/includes/controllers/managePlansController.php',
            method: 'POST',
            data: { action: 'getPlan', planId: planId },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    const plan = result.data;

                    // Llenar el formulario del modal con los datos del plan
                    $('#planId').val(plan.id);
                    $('#planName').val(plan.name);
                    $('#planDescription').val(plan.description);
                    $('#planDifficulty').val(plan.difficulty);
                    $('#planDuration').val(plan.duration);
                    $('#planPrice').val(plan.price);
                    $('#planCreatedAt').val(plan.createdAt);

                    // Mostrar la imagen actual
                    $('#currentPlanImage').attr('src', plan.image);

                    // Llenar el campo oculto con el GUID de la imagen actual
                    $('#currentImageGUID').val(plan.imageGUID);

                    // Abrir el modal
                    $('#editPlanModal').modal('show');
                } else {
                    showAlert('danger', result.alert); // Mostrar alerta de error
                }
            },
            error: function () {
                showAlert('danger', 'Error al cargar los datos del plan.');
            }
        });
    });

    // Interceptar el envío del formulario para guardar cambios
    $(document).on('submit', '#editPlanForm', function (e) {
        e.preventDefault(); // Evitar el envío estándar del formulario

        // Enviar los datos del formulario mediante AJAX
        $.ajax({
            url: '/AW-project/includes/controllers/managePlansController.php',
            method: 'POST',
            data: $(this).serialize() + '&action=updatePlan', // Agregar la acción
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    showAlert('success', result.alert); // Mostrar alerta de éxito
                    $('#editPlanModal').modal('hide'); // Cerrar el modal
                    loadPlanTable(); // Recargar la tabla de planes
                } else {
                    showAlert('danger', result.alert); // Mostrar alerta de error
                }
            },
            error: function () {
                showAlert('danger', 'Error al guardar los cambios del plan.');
            }
        });
    });

    
    // Delegar el evento click para el botón "Eliminar"
    $(document).on('click', '.eliminarPlan', function () {

        const planId = $(this).data('id'); // Obtener el ID del plan

        if (confirm('¿Estás seguro de que deseas eliminar este plan?')) {
            // Realizar una solicitud AJAX para eliminar el plan
            $.ajax({
                url: '/AW-project/includes/controllers/managePlansController.php',
                method: 'POST',
                data: { action: 'deletePlan', planId: planId },
                success: function (response) {
                    console.log('Respuesta del servidor:', response); // Depuración
                    const result = JSON.parse(response);
                    if (result.success) {
                        showAlert('success', result.alert); // Mostrar alerta de éxito
                        loadPlanTable(); // Recargar la tabla de planes
                    } else {
                        showAlert('danger', result.alert); // Mostrar alerta de error
                    }
                },
                error: function () {
                    showAlert('danger', 'Error al eliminar el plan.');
                }
            });
        }
    });

        // Editar estado del plan
    $(document).on('click', '.edit-statusPlan', function () {
        const planId = $(this).data('id'); // Obtener el ID del plan

        // Realizar una solicitud AJAX para obtener los datos del plan
        $.ajax({
            url: '/AW-project/includes/controllers/managePlansController.php',
            method: 'POST',
            data: { action: 'getPlanStatus', planId: planId }, // Usar action: getPlan
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    const plan = result.data;

                    // Pasar los datos del plan al modal
                    $('#statusPlanId').val(plan.id); // ID del plan
                    $('input[name="status"]').prop('checked', false); // Desmarcar todos los radio buttons
                    $(`input[name="status"][value="${plan.status}"]`).prop('checked', true); // Marcar el estado actual

                    // Abrir el modal
                    $('#changeStatusModal').modal('show');
                } else {
                    showAlert('danger', result.alert); // Mostrar alerta de error
                }
            },
            error: function () {
                showAlert('danger', 'Error al cargar los datos del plan.');
            }
        });
    });

    $(document).on('submit', '#changeStatusForm', function (e) {
        e.preventDefault();

        $.ajax({
            url: '/AW-project/includes/controllers/managePlansController.php',
            method: 'POST',
            data: $(this).serialize() + '&action=updatePlanStatus', // Enviar el formulario con los datos y la acción de actualizar
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    showAlert('success', result.alert);
                    $('#changeStatusModal').modal('hide');
                    loadPlanPurchaseTable(); // Recargar la tabla de planes
                } else {
                    showAlert('danger', result.alert);
                }
            },
            error: function () {
                showAlert('danger', 'Error al actualizar el estado del plan.');
            }
        });
    });

    // Delegar el evento click para el botón "Ver detalles"
    $(document).on('click', '.view-plan-pdf', function () {
        const planId = $(this).data('id');
    
        $.ajax({
            url: '/AW-project/includes/controllers/managePlansController.php',
            method: 'POST',
            data: { action: 'getPdfPath', planId: planId },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    const pdfPath = result.data.pdfPath;
    
                    // Abrir el PDF en una nueva ventana
                    window.open(pdfPath, '_blank');
                } else {
                    showAlert('danger', result.alert || 'No se pudo obtener el PDF.');
                }
            },
            error: function () {
                showAlert('danger', 'Error en la solicitud para descargar el PDF.');
            }
        });
    });
    
    
    // Función para recargar la tabla de planes 
    function loadPlanTable() {
        $.ajax({
            url: '/AW-project/includes/account/managePlans.php',
            method: 'GET',
            success: function (response) {
                $('#content').html(response); // Reemplazar el contenido de la tabla
            },
            error: function () {
                showAlert('danger', 'Error al recargar la tabla de planes.');
            }
        });
    }

    // Función para recargar la tabla de planes comprados
    function loadPlanPurchaseTable() {
        $.ajax({
            url: '/AW-project/includes/account/myPlans.php',
            method: 'GET',
            success: function (response) {
                $('#content').html(response); // Reemplazar el contenido de la tabla
            },
            error: function () {
                showAlert('danger', 'Error al recargar la tabla de planes.');
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