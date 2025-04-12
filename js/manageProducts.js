$(document).ready(function () {
    // Delegar el evento click para el botón "Editar"
    $(document).on('click', '.edit-product', function () {
        const productId = $(this).data('id'); // Obtener el ID del producto

        // Realizar una solicitud AJAX para obtener los datos del producto
        $.ajax({
            url: '/AW-project/includes/controllers/manageProductsController.php',
            method: 'POST',
            data: { action: 'getProduct', productId: productId },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    const product = result.data;

                    // Llenar el formulario del modal con los datos del producto
                    $('#productId').val(product.id);
                    $('#productName').val(product.name);
                    $('#productDescription').val(product.description);
                    $('#productPrice').val(product.price);
                    $('#productCategory').val(product.category);
                    $('#productEmailProvider').val(product.emailProvider);
                    $('#productCreatedAt').val(product.createdAt);
                    $('#productActive').val(product.active);

                    // Llenar el stock por tallas
                    const sizes = product.sizes;
                    for (const size in sizes) {
                        $(`#stock_${size}`).val(sizes[size]);
                    }

                    // Mostrar la imagen actual
                    $('#currentProductImage').attr('src', product.image);

                    // Llenar el campo oculto con el GUID de la imagen actual
                    $('#currentImageGUID').val(product.imageGUID);

                    // Abrir el modal
                    $('#editProductModal').modal('show');
                } else {
                    showAlert('danger', result.alert); // Mostrar alerta de error
                }
            },
            error: function () {
                showAlert('danger', 'Error al cargar los datos del producto.');
            }
        });
    });

    // Interceptar el envío del formulario para guardar cambios
    $(document).on('submit', '#editProductForm', function (e) {
        e.preventDefault(); // Evitar el envío estándar del formulario

        // Enviar los datos del formulario mediante AJAX
        $.ajax({
            url: '/AW-project/includes/controllers/manageProductsController.php',
            method: 'POST',
            data: $(this).serialize() + '&action=updateProduct', // Agregar la acción
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    showAlert('success', result.alert); // Mostrar alerta de éxito
                    $('#editProductModal').modal('hide'); // Cerrar el modal
                    loadProductsTable(); // Recargar la tabla de productos
                } else {
                    showAlert('danger', result.alert); // Mostrar alerta de error
                }
            },
            error: function () {
                showAlert('danger', 'Error al guardar los cambios del producto.');
            }
        });
    });

    // Delegar el evento click para el botón "Eliminar"
    $(document).on('click', '.delete-product', function () {
        const productId = $(this).data('id'); // Obtener el ID del producto

        if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
            // Realizar una solicitud AJAX para eliminar el producto
            $.ajax({
                url: '/AW-project/includes/controllers/manageProductsController.php',
                method: 'POST',
                data: { action: 'deleteProduct', productId: productId },
                success: function (response) {
                    console.log('Respuesta del servidor:', response); // Depuración
                    const result = JSON.parse(response);
                    if (result.success) {
                        showAlert('success', result.alert); // Mostrar alerta de éxito
                        loadProductsTable(); // Recargar la tabla de productos
                    } else {
                        showAlert('danger', result.alert); // Mostrar alerta de error
                    }
                },
                error: function () {
                    showAlert('danger', 'Error al eliminar el producto.');
                }
            });
        }
    });

    // Delegar el evento click para el botón "Activar"
    $(document).on('click', '.activate-product', function () {
        const productId = $(this).data('id'); // Obtener el ID del producto

        if (confirm('¿Estás seguro de que deseas activar este producto?')) {
            // Realizar una solicitud AJAX para activar el producto
            $.ajax({
                url: '/AW-project/includes/controllers/manageProductsController.php',
                method: 'POST',
                data: { action: 'activateProduct', productId: productId },
                success: function (response) {
                    console.log('Respuesta del servidor:', response); // Depuración
                    const result = JSON.parse(response);
                    if (result.success) {
                        showAlert('success', result.alert); // Mostrar alerta de éxito
                        loadProductsTable(); // Recargar la tabla de productos
                    } else {
                        showAlert('danger', result.alert); // Mostrar alerta de error
                    }
                },
                error: function () {
                    showAlert('danger', 'Error al activar el producto.');
                }
            });
        }
    });

    // Función para recargar la tabla de productos
    function loadProductsTable() {
        $.ajax({
            url: '/AW-project/includes/account/manageProducts.php',
            method: 'GET',
            success: function (response) {
                $('#content').html(response); // Reemplazar el contenido de la tabla
            },
            error: function () {
                showAlert('danger', 'Error al recargar la tabla de productos.');
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