<?php
/**
 * Simulador de API de Usuarios
 * Simula una respuesta JSON basada en una petición POST con filtros y paginación.
 */

if (!defined('ABSPATH')) exit;

/**
 * Simula la llamada POST a la API externa.
 * * @param array $params Datos provenientes del formulario (nombre, apellidos, email, pagina).
 * @return array Estructura de datos con el listado de usuarios y el total para paginación.
 */
function ula_simulate_api_call($params) {
    // 1. Generamos una base de datos ficticia de usuarios
    $todos_los_usuarios = [];
    for ($i = 1; $i <= 18; $i++) {
        $todos_los_usuarios[] = [
            "id"       => $i,
            "email"    => "usuario{$i}@yopmail.com",
            "name"     => "Nombre{$i}",
            "surname1" => "ApellidoA{$i}",
            "surname2" => "ApellidoB{$i}",
        ];
    }

    // 2. Aplicamos el filtrado (Simulando la lógica del servidor API)
    $usuarios_filtrados = array_filter($todos_los_usuarios, function($u) use ($params) {
        $match = true;

        if (!empty($params['nombre']) && stripos($u['name'], $params['nombre']) === false) {
            $match = false;
        }
        if (!empty($params['apellidos'])) {
            $apellidos_completos = $u['surname1'] . ' ' . $u['surname2'];
            if (stripos($apellidos_completos, $params['apellidos']) === false) {
                $match = false;
            }
        }
        if (!empty($params['email']) && stripos($u['email'], $params['email']) === false) {
            $match = false;
        }

        return $match;
    });

    // 3. Lógica de Paginación (5 usuarios por página)
    $por_pagina = 5;
    $total_usuarios = count($usuarios_filtrados);
    $pagina_actual = max(1, $params['pagina']);
    $offset = ($pagina_actual - 1) * $por_pagina;

    // Extraemos solo el trozo (slice) que corresponde a la página actual
    $usuarios_paginados = array_slice($usuarios_filtrados, $offset, $por_pagina);

    // 4. Devolvemos el formato JSON esperado 
    return [
        "usuarios" => $usuarios_paginados,
        "total"    => $total_usuarios // Dato extra necesario para calcular el número de botones en el frontend
    ];
}
