<?php
/*
Plugin Name: Listado de Usuarios AJAX
Description: Prueba técnica: Listado paginado con filtro y AJAX.
Version: 1.0
Author: Josue Ernesto Hernández Pozo
*/

if (!defined('ABSPATH')) exit;

// Definir constantes
define('ULA_PATH', plugin_dir_path(__FILE__));
define('ULA_URL', plugin_dir_url(__FILE__));

// Cargar simulador de API
require_once ULA_PATH . 'includes/api-simulator.php';

// Encolar scripts y estilos
add_action('wp_enqueue_scripts', 'ula_enqueue_assets');
function ula_enqueue_assets() {
    wp_enqueue_style('ula-style', ULA_URL . 'assets/css/style.css');
    wp_enqueue_script('ula-js', ULA_URL . 'assets/js/user-ajax.js', array('jquery'), '1.0', true);

    // Pasar la URL de AJAX de WordPress al JS
    wp_localize_script('ula-js', 'ula_obj', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('ula_nonce')
    ));
}

//Contenedor que el usuario verá en el frontend
add_shortcode('listado_usuarios', 'ula_render_shortcode');
function ula_render_shortcode() {
    ob_start(); ?>
    <div id="ula-container">
        <form id="ula-filter-form">
            <input type="text" id="ula-nombre" placeholder="Nombre">
            <input type="text" id="ula-apellidos" placeholder="Apellidos">
            <input type="email" id="ula-email" placeholder="Correo electrónico">
            <button type="submit">Buscar</button>
        </form>

        <div id="ula-results">
            <p>Cargando usuarios...</p>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

//Procesamiento de la búsqueda y la paginación
// Escuchar peticiones AJAX (para usuarios logueados y visitantes)
add_action('wp_ajax_ula_get_users', 'ula_handle_ajax_request');
add_action('wp_ajax_nopriv_ula_get_users', 'ula_handle_ajax_request');

function ula_handle_ajax_request() {
    check_ajax_referer('ula_nonce', 'nonce');

    // Sanitizar datos recibidos 
    $filtros = array(
        'nombre'    => sanitize_text_field($_POST['nombre']),
        'apellidos' => sanitize_text_field($_POST['apellidos']),
        'email'     => sanitize_email($_POST['email']),
        'pagina'    => isset($_POST['pagina']) ? intval($_POST['pagina']) : 1
    );

    // Obtener datos de la "API" 
    $response = ula_simulate_api_call($filtros);
    
    // Renderizar la tabla (HTML que se devolverá al JS)
    ula_render_user_table($response, $filtros['pagina']);

    wp_die(); // Siempre terminar procesos AJAX en WP
}

function ula_render_user_table($data, $current_page) {
    if (empty($data['usuarios'])) {
        echo '<p>No se encontraron usuarios.</p>';
        return;
    }

    echo '<table>';
    echo '<thead><tr><th>Usuario</th><th>Nombre</th><th>Apellido 1</th><th>Apellido 2</th><th>Email</th></tr></thead>';
    echo '<tbody>';
    foreach ($data['usuarios'] as $u) {
        // Escapar datos para salida segura
        printf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
            esc_html($u['name']),
            esc_html($u['name']),
            esc_html($u['surname1']),
            esc_html($u['surname2']),
            esc_html($u['email'])
        );
    }
    echo '</tbody></table>';

    // Paginador 
    $total_pages = ceil($data['total'] / 5);
    echo '<div class="pagination">';
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = ($i == $current_page) ? 'active' : '';
        echo "<button class='page-btn $active' data-page='$i'>$i</button>";
    }
    echo '</div>';
}
