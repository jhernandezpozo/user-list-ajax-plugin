# Prueba Técnica WordPress de Josue Hernández Pozo- Listado AJAX

Este plugin genera un listado de usuarios consumiendo una API simulada con filtrado y paginación asíncrona.

## Instalación y Uso
1. Clonar el repositorio en "wp-content/plugins/".
2. Importar el archivo en la carpeta "BBDD/" a la base de datos local.
3. Activar el plugin desde el panel de administración.
4. Usar el shortcode "[listado_usuarios]" en cualquier página.

## Base de Datos
Para el desarrollo y visualización de los resultados se usó un usuario de WP (registrado en bbdd.sql) cuyas credenciales son las siguientes:
1. User: admin
2. Password: admin123*
3. Email: prueba@gmail.com

## Requisitos
- WordPress 6.0+
- PHP 8.0+

## Descripción
Este proyecto consiste en el desarrollo de un plugin de WordPress que implementa un módulo para mostrar un listado de usuarios con las siguientes características:
1. Listado paginado (5 usuarios por página)
2. Filtro por:
    - Nombre
    - Apellidos
    - Email
3. Actualización dinámica mediante AJAX (sin recarga de página)
4. Simulación de una API externa:
    - Genera usuarios dinámicamente
    - Permite aplicar filtros y paginación
    - Devuelve datos en formato JSON

## Estructura del proyecto
user-list-ajax/
├── assets/
│   ├── js/
│   │   └── user-ajax.js
│   └── css/
│       └── style.css
├── BBDD/ (bbdd.sql) 
├── includes/
│   └── api-simulator.php
├── user-list-ajax.php (Archivo principal)
└── .gitignore
└── README.md

## Seguridad y Buenas Prácticas

Este proyecto sigue los estándares de desarrollo de WordPress para garantizar la integridad de los datos y la seguridad del sitio:

**Verificación de Nonces:** Se implementó un token de seguridad (nonce) mediante `wp_create_nonce` y `check_ajax_referer` para prevenir ataques de falsificación de solicitudes entre sitios (CSRF) en todas las llamadas AJAX.

**Sanitización de Entradas:** Todos los datos recibidos desde el formulario de búsqueda se limpian antes de ser procesados utilizando `sanitize_text_field()` y `sanitize_email()`.

**Escapado de Salida:** Para prevenir ataques XSS (Cross-Site Scripting), toda la información mostrada en la tabla de usuarios se escapa en el momento de la impresión usando `esc_html()` y `printf()`.

**Restricción de Acceso Directo:** El archivo principal del plugin incluye una comprobación de seguridad `defined('ABSPATH') || exit;` para evitar la ejecución directa de scripts desde el navegador.

**Prevención de Inyección de Código:** Al no utilizar SQL directo y procesar la información de forma programada mediante PHP puro, se elimina el riesgo de inyecciones SQL en la visualización del listado.

## Autor Josue Ernesto Hernández Pozo
Desarrollado como prueba técnica para puesto de desarrollador PHP / WordPress.

## Notas finales
El proyecto está preparado para ser clonado, instalado y ejecutado directamente siguiendo los pasos descritos.
Se ha priorizado la claridad, mantenibilidad y buenas prácticas en el código.
