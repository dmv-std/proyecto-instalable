<?php $modulos = array(

/* --------------------------------------------------------------------------------
 *	Módulos
 * --------------------------------------------------------------------------------
 * | Editar campo 'títulos' para cambiar el nombre con el que el módulo aparecerá
 * | en las interfaces del sistema.
 * --------------------------------------------------------------------------------
 * | El campo 'clase' muestra el color con el que aparecerá el módulo en el index (frontpage)
 * | - 'primary' : azul
 * | - 'info'	 : azul claro
 * | - 'success' : verde
 * | - 'warning' : amarillo
 * | - 'danger'	 : rojo
 * --------------------------------------------------------------------------------
 * | El campo 'icono' muestra el ícono con el que saldrá el link del módulo en el index.
 * | Se pueden ver más iconos en la url:
 * | https://fontawesome.com/v4.7.0/icons/
 * | Si el código del ícono es "fa fa-icono", sólo tomar "icono".
 * --------------------------------------------------------------------------------
 * | El campo 'uri' indica la ruta relativa al módulo.
 * -------------------------------------------------------------------------------- */
	[
		/* -------------------------------
		 *	Calendario
		 * ------------------------------- */
		'nombre'	=> 'calendario',
		'titulo'	=> "Calendario",
		'clase'		=> 'primary',
		'icono'		=> 'calendar',
		'uri'		=> "calendario/",
		'permisos'	=> function(){return ($_SESSION['rolCalPersona'] <= 2||$_SESSION['permisosPersona'] == "admin")&& ($_SESSION['permisosPersona'] != "externo") || $_SESSION['rolCal'] == 1;},
	],

	[
		/* -------------------------------
		 *	Cotizador 1
		 * ------------------------------- */
		'nombre'	=> 'cotizador',
		'titulo'	=> "Cotizaciones",
		'clase'		=> 'success',
		'icono'		=> 'usd',
		'uri'		=> 'cotizador/admin/',
		'permisos'	=> function(){return ($_SESSION['permisosPersona'] != "no") && ($_SESSION['permisosPersona'] != "externo") || $_SESSION['cotizador'] == 1;},
	],

	[
		/* -------------------------------
		 *	Cotizador 2
		 * ------------------------------- */
		'nombre'	=> 'cotizador2',
		'titulo'	=> "Cotizaciones Específicas",
		'clase'		=> 'success',
		'icono'		=> 'usd',
		'uri'		=> 'cotizador2/admin/',
		'permisos'	=> function(){return ($_SESSION['permisosPersona'] != "no") && ($_SESSION['permisosPersona'] != "externo") && $_SESSION['cotizador2'] == 1;},
	],

	[
		/* -------------------------------
		 *	Formularios
		 * ------------------------------- */
		'nombre'	=> 'formularios',
		'titulo'	=> "Contactos",
		'clase'		=> 'success',
		'icono'		=> 'envelope',
		'uri'		=> 'formularios/contactos.php',
		'permisos'	=> function(){return ($_SESSION['permisosPersona'] != "no") || ($_SESSION['permisosPersona'] == "externo") || $_SESSION['formularios'] == 1;},
	],

	[
		/* -------------------------------
		 *	CRM
		 * ------------------------------- */
		'nombre'	=> 'crm',
		'titulo'	=> "CRM",
		'clase'		=> 'warning',
		'icono'		=> 'exchange',
		'uri'		=> 'crm/',
		'permisos'	=> function(){return ($_SESSION['permisosPersona'] != "no") || ($_SESSION['permisosPersona'] == "externo") || $_SESSION['crm'] == 1;},
	],

	[
		/* -------------------------------
		 *	Listas de Precios
		 * ------------------------------- */
		'nombre'	=> 'listas',
		'titulo'	=> "Listas de Precios",
		'clase'		=> 'warning',
		'icono'		=> 'exchange',
		'uri'		=> 'listas/admin/',
		'permisos'	=> function(){return ($_SESSION['permisosPersona'] != "no") || ($_SESSION['permisosPersona'] == "externo") || $_SESSION['listas'] == 1;},
	],

	[
		/* -------------------------------
		 *	Pagos
		 * ------------------------------- */
		'nombre'	=> 'pagos',
		'titulo'	=> "Pagos",
		'clase'		=> 'warning',
		'icono'		=> 'money',
		'uri'		=> 'pagos/',
		'permisos'	=> function(){return ($_SESSION['rolPagPersona'] <= 2||$_SESSION['permisosPersona'] == "admin") && ($_SESSION['permisosPersona'] != "externo") || $_SESSION['pagos'] == 1;},
	],

	[
		/* -------------------------------
		 *	Presupuestos
		 * ------------------------------- */
		'nombre'	=> 'presupuestos',
		'titulo'	=> "Presupuestos",
		'clase'		=> 'primary',
		'icono'		=> 'usd',
		'uri'		=> 'presupuestos/',
		'permisos'	=> function(){return ($_SESSION['rolProPersona'] <= 2 ||$_SESSION['permisosPersona'] == "admin") && ($_SESSION['permisosPersona'] != "externo") && $_SESSION['presupuestos'] == 1;},
	],

	[
		/* -------------------------------
		 *	Producción
		 * ------------------------------- */
		'nombre'	=> 'produccion',
		'titulo'	=> "Producción",
		'clase'		=> 'warning',
		'icono'		=> 'gears',
		'uri'		=> 'produccion/',
		'permisos'	=> function(){return ($_SESSION['rolProPersona'] <= 2 ||$_SESSION['permisosPersona'] == "admin") && ($_SESSION['permisosPersona'] != "externo") || $_SESSION['produccion'] == 1;},
	],

	[
		/* -------------------------------
		 *	Respuestas
		 * ------------------------------- */
		'nombre'	=> 'respuestas',
		'titulo'	=> "Respuestas",
		'clase'		=> 'primary',
		'icono'		=> 'calendar',
		'uri'		=> 'respuestas/',
		'permisos'	=> function(){return ($_SESSION['rolCalPersona'] <= 2||$_SESSION['permisosPersona'] == "admin")&& ($_SESSION['permisosPersona'] != "externo") || $_SESSION['respuestas'] == 1;},
	],

	[
		/* -------------------------------
		 *	Recursos Humanos
		 * ------------------------------- */
		'nombre'	=> 'rrhh',
		'titulo'	=> "RR. HH.",
		'clase'		=> 'info',
		'icono'		=> 'users',
		'uri'		=> 'rrhh/',
		'permisos'	=> function(){return $_SESSION['rrhh'] == 1;},
	],
);

?>