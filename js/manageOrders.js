$(document).ready(function () {
    // Cargar la tabla de pedidos al cargar la página
    loadOrdersTable();

    // Ver detalles del pedido
    $(document).on('click', '.view-order', function () {
        const orderId = $(this).data('id');

        $.ajax({
            url: '/AW-project/includes/controllers/manageOrdersController.php',
            method: 'POST',
            data: { action: 'getOrderDetails', orderId: orderId },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    $('#orderDetailsModal .modal-body').html(result.data);
                    $('#orderDetailsModal').modal('show');
                } else {
                    showAlert('danger', result.alert);
                }
            },
            error: function () {
                showAlert('danger', 'Error al cargar los detalles del pedido.');
            }
        });
    });

    // Editar estado del pedido
    $(document).on('click', '.edit-order', function () {
        const orderId = $(this).data('id');
        $('#orderId').val(orderId);
        $('#editOrderModal').modal('show');
    });

    $(document).on('submit', '#editOrderForm', function (e) {
        e.preventDefault();

        $.ajax({
            url: '/AW-project/includes/controllers/manageOrdersController.php',
            method: 'POST',
            data: $(this).serialize() + '&action=updateOrderStatus',
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    showAlert('success', result.alert);
                    $('#editOrderModal').modal('hide');
                    loadOrdersTable();
                } else {
                    showAlert('danger', result.alert);
                }
            },
            error: function () {
                showAlert('danger', 'Error al actualizar el estado del pedido.');
            }
        });
    });

    // Eliminar pedido
    $(document).on('click', '.delete-order', function () {
        const orderId = $(this).data('id');

        if (confirm('¿Estás seguro de que deseas eliminar este pedido?')) {
            $.ajax({
                url: '/AW-project/includes/controllers/manageOrdersController.php',
                method: 'POST',
                data: { action: 'deleteOrder', orderId: orderId },
                success: function (response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        showAlert('success', result.alert);
                        loadOrdersTable();
                    } else {
                        showAlert('danger', result.alert);
                    }
                },
                error: function () {
                    showAlert('danger', 'Error al eliminar el pedido.');
                }
            });
        }
    });

    // Función para cargar la tabla de pedidos
    function loadOrdersTable() {
        $.ajax({
            url: '/AW-project/includes/account/manageOrders.php',
            method: 'GET',
            success: function (response) {
                $('#content').html(response);
            },
            error: function () {
                showAlert('danger', 'Error al cargar la tabla de pedidos.');
            }
        });
    }

    // Función para mostrar alertas personalizadas
    function showAlert(type, message) {
        const alertHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
        $('#alertContainer').html(alertHTML);

        setTimeout(() => {
            $('.alert').alert('close');
        }, 5000);
    }
});