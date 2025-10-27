# Woo Filter Pro

Woo Filter Pro es un sistema avanzado de filtros y orden para WooCommerce que trabaja mediante AJAX.
Permite a los usuarios filtrar productos por categorías y atributos, cuenta dinámicamente los resultados y ofrece una experiencia responsive optimizada para móviles.

## Instalación

1. Copia el plugin en el directorio `wp-content/plugins/` o instálalo como un plugin normal.
2. Activa el plugin desde el panel de administración de WordPress.
3. Asegúrate de tener WooCommerce activo.
4. No se requiere Composer ni archivos de traducción; todas las dependencias están incluidas.

## Funcionalidades

- Filtrado jerárquico de categorías y atributos con recuentos dinámicos.
- Las categorías padre funcionan como etiquetas no seleccionables; solo las
  subcategorías pueden marcarse.
- Listado de productos con paginación y priorización del tag `destacado`.
- Selector de orden y contador de resultados.
- Interfaz responsive con panel off‑canvas para móviles.
- Traducciones personalizadas de mensajes de WooCommerce sin archivos `.po`/`.mo`.

## Shortcodes disponibles

| Shortcode               | Descripción                                                                                |
| ----------------------- | ------------------------------------------------------------------------------------------ |
| `[filtro_productos]`    | Genera el formulario de filtros jerárquicos para categorías y atributos.                   |
| `[productos_ajax]`      | Renderiza los productos filtrados mediante AJAX con paginación.                            |
| `[filtros_responsive]`  | Muestra un contenedor responsive con el formulario de filtros dentro de un panel off-canvas. |
| `[ordenador_productos]` | Añade el selector de orden y el contador de productos filtrados.                           |

## Scripts y estilos

El plugin incluye y registra automáticamente los siguientes archivos:

- `assets/js/filtros.js`: gestiona las solicitudes AJAX, paginación y recuento de atributos.
- `assets/js/responsive.js`: controla el panel off‑canvas y eventos para móviles.
- `assets/styles/styles.css`: estilos principales de filtros, productos y componentes.

## Plantillas

Las vistas de los shortcodes se encuentran en el directorio `templates/` del plugin. Para sobrescribirlas, copia cualquier archivo de ese directorio a `wp-content/themes/tu-tema/woo-filter-pro/` manteniendo el mismo nombre. WordPress cargará la versión ubicada en el tema en lugar de la incluida en el plugin.

## Notas

- Las acciones AJAX públicas disponibles son `obtener_atributos_por_categoria` y `contar_atributos_dinamicos`.
- Los textos predeterminados de WooCommerce se traducen al español para una experiencia consistente.
