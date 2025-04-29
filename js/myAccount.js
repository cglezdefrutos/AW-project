$(document).ready(function () {
    $('.menu-item').on('click', function () {
        const section = $(this).data('section');

        $.ajax({
            url: '/AW-project/includes/controllers/myAccountController.php',
            method: 'POST',
            data: { section: section },
            success: function (response) {
                $('#content').html(response);
            },
            error: function () {
                alert('Error al cargar la sección.');
            }
        });
    });
});