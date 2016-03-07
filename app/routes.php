<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */
Route::get('/locale/{locale}/{type}/{ang}', 'BaseController@setLocale');

if (in_array(Request::segment(1), Config::get('app.alt_langs'))) {

    App::setLocale(Request::segment(1));
    Config::set('app.locale_prefix', Request::segment(1));
}

Route::group(array('prefix' => Config::get('app.locale_prefix')), function() {

    Route::get('/', 'HomeController@inicio');

    Route::get('/contacto', 'HomeController@contacto');
    
    Route::get('/contact', 'HomeController@contacto');

    Route::group(array('before' => 'guest'), function() {

        Route::get('login', 'UsuarioController@login', function() {
            // Save the attempted URL
            Session::put('pre_login_url', URL::current());

            var_dump(Session::get('pre_login_url'));
        });

        Route::post('login', 'UsuarioController@loguearse');
    });

//Ruteo de Categoría
    Route::get('categoria/{url}', 'CategoriaController@mostrarInfoCategoria');

//Ruteo de Item
    Route::get('item/{url}', 'ItemController@mostrarInfoItem');



//Ruteo de Seccion
    Route::get('seccion/{id}', 'SeccionController@mostrarInfoSeccion');

//Ruteo de Categoría
    Route::get('tc-error', 'HomeController@error');

//Ruteo de Producto
    Route::get('producto/{url}', 'ProductoController@mostrarInfoProducto');

//Ruteo de Noticia
    Route::get('noticia/{url}', 'NoticiaController@mostrarInfo');

//Ruteo de Evento
    Route::get('evento/{url}', 'EventoController@mostrarInfo');

//Ruteo de Portfolio
    Route::get('portfolio/{url}', 'PortfolioController@mostrarInfo');

//Ruteo de PortfolioCompleto
    Route::get('portfolio_completo/{url}', 'PortfolioCompletoController@mostrarInfo');

//Ruteo de Muestra
    Route::get('muestra/{url}', 'MuestraController@mostrarInfo');

    Route::post('admin/producto/producto-consulta', 'ProductoController@consultarProductoLista');
    Route::post('admin/producto/consulta-general', 'ProductoController@consultaGeneral');

    Route::post('consulta', 'ClienteController@consultaContacto');

    Route::post('registrar-newsletter', 'ClienteController@registrar');

    Route::get('carrito', 'CarritoController@vistaListado');

    Route::get('carrito/agregar/{producto_id}/{continue}/{sec_id}', 'CarritoController@agregarProducto');

    Route::post('carrito/editar/{producto_id}/{rowId}', 'CarritoController@editarProducto');

    Route::get('carrito/borrar/{producto_id}/{rowId}/{continue}/{sec_id}', 'CarritoController@borrarProducto');

    Route::get('carrito/borrar', 'CarritoController@borrar');

    Route::post('pedido/agregar', 'PedidoController@agregarPedido');

// Para todas estas rutas el usuario debe haber iniciado sesión. 
    Route::group(array('before' => 'auth'), function() {

        Route::get('admin/exportar-clientes', 'ClienteController@exportarEmail');

        Route::get('admin/exportar-personas', 'PersonaController@exportarEmail');

        /*
         * Ruteo de Categoría
         */
        Route::get('admin/categoria', 'CategoriaController@vistaListado');

        Route::get('admin/categoria/agregar', 'CategoriaController@vistaAgregar');

        Route::post('admin/categoria/agregar', 'CategoriaController@agregar');

        Route::get('admin/categoria/editar/{id}', 'CategoriaController@vistaEditar');

        Route::post('admin/categoria/editar', 'CategoriaController@editar');

        Route::post('admin/categoria/borrar', 'CategoriaController@borrar');



        Route::post('admin/imagen/borrar', 'ImagenController@borrar');

        Route::post('admin/archivo/borrar', 'ArchivoController@borrar');

        Route::post('admin/video/borrar', 'VideoController@borrar');
        /*
         * Ruteo de Item
         */
        Route::get('admin/item', 'ItemController@vistaListado');

        Route::get('admin/item/agregar/{id}', 'ItemController@vistaAgregar');

        Route::post('admin/item/agregar', 'ItemController@agregar');

        Route::get('admin/item/editar/{id}', 'ItemController@vistaEditar');

        Route::post('admin/item/editar', 'ItemController@editar');

        Route::post('admin/item/borrar', 'ItemController@borrar');

        Route::post('admin/item/borrar-item-seccion', 'ItemController@borrarItemSeccion');

        Route::get('admin/item/ordenar-por-seccion/{id}', 'ItemController@vistaOrdenar');

        Route::post('admin/item/ordenar-por-seccion', 'ItemController@ordenar');

        Route::post('admin/item/destacar', 'ItemController@destacarItemSeccion');

        Route::post('admin/item/quitar-destacado', 'ItemController@quitarDestacadoItemSeccion');

        /*
         * Ruteo Imagen 
         */

        Route::post('admin/imagen/crop/upload', 'ImagenController@uploadImagenCrop');

        Route::post('admin/imagen/slide/upload', 'ImagenController@uploadGaleriaSlideHome');

        Route::post('admin/imagen/ordenar-por-item', 'ImagenController@ordenar');

        /*
         * Ruteo Video 
         */

        Route::get('admin/video', 'VideoController@verListado');

        Route::get('admin/video/agregar', 'VideoController@vistaAgregar');

        Route::post('admin/video/agregar/youtube', 'VideoController@agregarYoutube');

        /*
         * Ruteo de Menu
         */
        Route::get('admin/menu', 'MenuController@vistaListado');

        Route::get('admin/menu/agregar', 'MenuController@vistaAgregar');

        Route::post('admin/menu/agregar', 'MenuController@agregar');

        Route::get('admin/menu/editar/{id}', 'MenuController@vistaEditar');

        Route::post('admin/menu/editar', 'MenuController@editar');

        //Route::post('menu/ordenar', 'MenuController@ordenar');

        Route::post('admin/menu/borrar', 'MenuController@borrar');

        //Route::get('admin/menu/pasar-secciones-categoria/{id}', 'MenuController@pasarSeccionesCategoria');
        Route::post('admin/menu/pasar-secciones-categoria', 'MenuController@pasarSeccionesCategoria');

        Route::get('admin/menu/ordenar-menu', 'MenuController@vistaOrdenar');

        Route::post('admin/menu/ordenar-menu', 'MenuController@ordenar');

        Route::get('admin/menu/ordenar-submenu/{id}', 'MenuController@vistaOrdenarSubmenu');

        Route::post('admin/menu/ordenar-submenu', 'MenuController@ordenarSubmenu');

        /*
         * Ruteo de Seccion
         */
        Route::get('admin/seccion', 'SeccionController@vistaListado');

        Route::get('admin/seccion/agregar/{id}', 'SeccionController@vistaAgregar');

        Route::post('admin/seccion/agregar', 'SeccionController@agregar');

        Route::get('admin/seccion/editar/{id}', 'SeccionController@vistaEditar');

        Route::post('admin/seccion/editar', 'SeccionController@editar');



        Route::get('admin/seccion/ordenar-por-menu/{id}', 'SeccionController@vistaOrdenar');

        Route::post('admin/seccion/ordenar-por-menu', 'SeccionController@ordenar');

        //Route::get('seccion/pasar-categoria/{id}', 'SeccionController@pasarCategoria');

        /*
         * Ruteo de Slide
         */

        Route::get('admin/slide/agregar/{menu_id}/{tipo}', 'SlideController@vistaAgregar');

        Route::post('admin/slide/agregar', 'SlideController@agregar');

        Route::get('admin/slide/editar/{id}/{next}/{tipo}', 'SlideController@vistaEditar');

        Route::post('admin/slide/editar', 'SlideController@editar');

        /*
         * Ruteo de Galeria
         */

        Route::get('admin/galeria/agregar/{menu_id}', 'GaleriaController@vistaAgregar');

        Route::post('admin/galeria/agregar', 'GaleriaController@agregar');

        Route::get('admin/galeria/editar/{item_id}', 'GaleriaController@vistaEditar');

        Route::post('admin/galeria/editar', 'GaleriaController@editar');

        /*
         * Ruteo de Texto
         */

        Route::get('admin/texto/agregar/{menu_id}', 'TextoController@vistaAgregar');

        Route::post('admin/texto/agregar', 'TextoController@agregar');

        Route::get('admin/texto/editar/{item_id}', 'TextoController@vistaEditar');

        Route::post('admin/texto/editar', 'TextoController@editar');

        /*
         * Ruteo de HTML
         */

        Route::get('admin/html/agregar/{menu_id}', 'HtmlController@vistaAgregar');

        Route::post('admin/html/agregar', 'HtmlController@agregar');

        Route::get('admin/html/editar/{item_id}', 'HtmlController@vistaEditar');

        Route::post('admin/html/editar', 'HtmlController@editar');

        /*
         * Ruteo de Producto
         */
        Route::get('admin/producto', 'ProductoController@vistaListado');

        Route::get('admin/producto/agregar/{seccion_id}', 'ProductoController@vistaAgregar');

        Route::post('admin/producto/agregar', 'ProductoController@agregar');

        Route::get('admin/producto/editar/{id}/{next}/{seccion_next}', 'ProductoController@vistaEditar');

        Route::post('admin/producto/editar', 'ProductoController@editar');

        Route::get('admin/producto/destacar/{id}/{next}', 'ProductoController@vistaDestacar');

        Route::post('admin/producto/destacar', 'ProductoController@destacar');

        Route::post('admin/producto/nuevo', 'ProductoController@nuevo');

        Route::get('admin/producto/oferta/{id}/{seccion_id}/{next}', 'ProductoController@vistaOferta');

        Route::post('admin/producto/oferta', 'ProductoController@oferta');
        /*
         * Ruteo de Noticia
         */
        Route::get('admin/noticia', 'NoticiaController@vistaListado');

        Route::get('admin/noticia/agregar/{seccion_id}', 'NoticiaController@vistaAgregar');

        Route::post('admin/noticia/agregar', 'NoticiaController@agregar');

        Route::get('admin/noticia/editar/{id}/{next}/{seccion_next}', 'NoticiaController@vistaEditar');

        Route::post('admin/noticia/editar', 'NoticiaController@editar');
//            
//            Route::get('admin/producto/destacar/{id}/{next}', 'ProductoController@vistaDestacar');
//            
//            Route::post('admin/producto/destacar', 'ProductoController@destacar');


        /*
         * Ruteo de Evento
         */
        Route::get('admin/evento', 'EventoController@vistaListado');

        Route::get('admin/evento/agregar/{seccion_id}', 'EventoController@vistaAgregar');

        Route::post('admin/evento/agregar', 'EventoController@agregar');

        Route::get('admin/evento/editar/{id}/{next}', 'EventoController@vistaEditar');

        Route::post('admin/evento/editar', 'EventoController@editar');

        /*
         * Ruteo de Portfolio
         */
        Route::get('admin/portfolio_simple', 'PortfolioController@vistaListado');

        Route::get('admin/portfolio_simple/agregar/{seccion_id}', 'PortfolioController@vistaAgregar');

        Route::post('admin/portfolio_simple/agregar', 'PortfolioController@agregar');

        Route::get('admin/portfolio_simple/editar/{id}/{next}/{seccion_next}', 'PortfolioController@vistaEditar');

        Route::post('admin/portfolio_simple/editar', 'PortfolioController@editar');

        /*
         * Ruteo de PortfolioCompleto
         */
        Route::get('admin/portfolio_completo', 'PortfolioCompletoController@vistaListado');

        Route::get('admin/portfolio_completo/agregar/{seccion_id}', 'PortfolioCompletoController@vistaAgregar');

        Route::post('admin/portfolio_completo/agregar', 'PortfolioCompletoController@agregar');

        Route::get('admin/portfolio_completo/editar/{id}/{next}/{seccion_next}', 'PortfolioCompletoController@vistaEditar');

        Route::post('admin/portfolio_completo/editar', 'PortfolioCompletoController@editar');

        /*
         * Ruteo de Muestra
         */
        Route::get('admin/muestra', 'MuestraController@vistaListado');

        Route::get('admin/muestra/agregar/{seccion_id}', 'MuestraController@vistaAgregar');

        Route::post('admin/muestra/agregar', 'MuestraController@agregar');

        Route::get('admin/muestra/editar/{id}/{next}/{seccion_next}', 'MuestraController@vistaEditar');

        Route::post('admin/muestra/editar', 'MuestraController@editar');


        /*
         * Ruteo de Marca
         */
        Route::get('admin/marca', 'MarcaController@vistaListado');

        Route::get('admin/marca/agregar', 'MarcaController@vistaAgregar');

        Route::post('admin/marca/agregar', 'MarcaController@agregar');

        Route::get('admin/marca/editar/{id}', 'MarcaController@vistaEditar');

        Route::post('admin/marca/editar', 'MarcaController@editar');

        Route::post('admin/marca/borrar', 'MarcaController@borrar');

        Route::post('admin/marca/quitar-imagen', 'MarcaController@quitarImagen');

        Route::post('admin/marca/imagen', 'MarcaController@vistaImagen');

        //DIRECCION

        Route::get('admin/direccion/alta', 'DireccionController@vistaAgregar');

        Route::post('admin/direccion/agregar', 'DireccionController@agregar');

        //TELEFONO

        Route::get('admin/telefono/alta', 'TelefonoController@vistaAgregar');

        Route::post('admin/telefono/agregar', 'TelefonoController@agregar');

        //PERSONA

        Route::get('admin/persona/alta', 'PersonaController@vistaAgregar');

        Route::post('admin/persona/agregar', 'PersonaController@agregar');

        Route::get('admin/persona/{id}', 'PersonaController@mostrarInfo');

        /*
         * Ruteo de Usuario
         */

        //Restricción de Filtro a partir de la condición de SuperAdmin
        Route::group(array('before' => 'superadmin'), function() {

            Route::get('registro', 'UsuarioController@registro');

            Route::post('registro', 'UsuarioController@registrarUsuario');

            Route::get('admin/usuarios-permisos', 'UsuarioController@vistaAsignarUsuarioPermiso');

            Route::post('admin/usuarios-permisos', 'UsuarioController@asignarPermisoUsuario');

            Route::get('admin/permisos', 'PermisoController@vistaAgregar');

            Route::post('admin/permiso/agregar', 'PermisoController@agregar');

            Route::post('admin/permiso/asignar-roles', 'PermisoController@asignarRoles');

            Route::post('admin/seccion/borrar', 'SeccionController@borrar');
        });

        Route::get('logout', 'UsuarioController@logout');
    });

//Ruteo de Menu
    Route::get('/{url}', 'MenuController@mostrarInfoMenu');
    Route::get('/{url}/{marca}', 'MenuController@mostrarInfoMenuPorMarca');
});
App::missing(function($exception) {

    // shows an error page (app/views/error.blade.php)
    // returns a page not found error
    return Redirect::to(Config::get('app.locale_prefix'), 'tc-error')->with('texto', 'Está intentado acceder a un lugar indebido.');
});
