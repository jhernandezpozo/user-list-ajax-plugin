jQuery(document).ready(function($) {
    function loadUsers(page = 1) {
        const data = {
            action: 'ula_get_users',
            nonce: ula_obj.nonce,
            nombre: $('#ula-nombre').val(),
            apellidos: $('#ula-apellidos').val(),
            email: $('#ula-email').val(),
            pagina: page
        };

        $.post(ula_obj.ajax_url, data, function(response) {
            $('#ula-results').html(response);
        });
    }

    // Evento buscar
    $('#ula-filter-form').on('submit', function(e) {
        e.preventDefault();
        loadUsers(1);
    });

    // Evento paginación (usar delegación por ser contenido dinámico)
    $(document).on('click', '.page-btn', function() {
        const page = $(this).data('page');
        loadUsers(page);
    });

    // Carga inicial
    loadUsers();
});