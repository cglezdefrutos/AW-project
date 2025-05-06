$(document).ready(function () {
    // Recuperar la opción activa del localStorage
    const activeSection = localStorage.getItem('activeSection');

    if (activeSection) {
        // Marcar como activa la opción correspondiente
        $('.menu-item').each(function () {
            if ($(this).data('section') === activeSection) {
                $(this).addClass('active');
            }
        });

        // Cargar automáticamente el contenido de la sección activa
        $.ajax({
            url: '/AW-project/includes/controllers/myAccountController.php',
            method: 'POST',
            data: { section: activeSection },
            success: function (response) {
                $('#content').html(response).show(); // Mostrar el contenido inmediatamente
            },
            error: function () {
                alert('Error al cargar la sección activa.');
                $('#content').show(); // Mostrar el contenido aunque haya un error
            }
        });
    } else {
        // Si no hay una sección activa, mostrar el contenido por defecto
        $('#content').show();
    }

    // Manejar el clic en las opciones del menú
    $('.menu-item').on('click', function () {
        const section = $(this).data('section');

        // Eliminar la clase 'active' de todas las opciones
        $('.menu-item').removeClass('active');

        // Agregar la clase 'active' a la opción seleccionada
        $(this).addClass('active');

        // Guardar la sección activa en el localStorage
        localStorage.setItem('activeSection', section);

        // Ocultar el contenido mientras se carga la nueva sección
        $('#content').hide(); // Ocultar el contenido inmediatamente
        $.ajax({
            url: '/AW-project/includes/controllers/myAccountController.php',
            method: 'POST',
            data: { section: section },
            success: function (response) {
                $('#content').html(response).show(); // Mostrar el contenido inmediatamente
            },
            error: function () {
                alert('Error al cargar la sección.');
                $('#content').show(); // Mostrar el contenido aunque haya un error
            }
        });
    });
});