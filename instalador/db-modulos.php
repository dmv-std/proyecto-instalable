<?php
	
	$db_modulos = [
		"sistema"		=> "CREATE TABLE `sist_enlaces` (
								`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
								`titulo` varchar(100) NOT NULL,
								`enlace` varchar(255) NOT NULL,
								`imagen` varchar(255) NOT NULL,
								`orden` int(11) NOT NULL,
								`color` varchar(50) NOT NULL,
								`usuario` int(11) NOT NULL,
								`comentario` varchar(255) NOT NULL,
								`tipo` varchar(100) NOT NULL
							) ENGINE=MyISAM DEFAULT CHARSET=utf8;
							
							CREATE TABLE `sist_usersloginacceso` (
								`id_usersLoginAcceso` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
								`username` varchar(50) NOT NULL,
								`fecha` datetime NOT NULL,
								`acceso` tinyint(1) NOT NULL,
								`ip` varchar(30) NOT NULL,
								`hostname` varchar(300) NOT NULL,
								`city` varchar(500) NOT NULL,
								`region` varchar(500) NOT NULL,
								`country` varchar(10) NOT NULL,
								`loc` varchar(100) NOT NULL,
								`org` varchar(500) NOT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8;
							
							CREATE TABLE `sist_usuarios` (
								`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
								`id_empleado` int(11) NOT NULL,
								`nombre` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`apellido` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
								`user` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`pass` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
								`telefono` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci,
								`permisos` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`idCal` int(4) NOT NULL,
								`rolCal` int(1) NOT NULL,
								`rolPag` int(1) NOT NULL,
								`rolPro` int(1) NOT NULL,
								`calendario` int(11) NOT NULL DEFAULT '1',
								`chat` int(11) NOT NULL DEFAULT '1',
								`cotizador` int(11) NOT NULL DEFAULT '1',
								`crm` int(11) NOT NULL DEFAULT '1',
								`formularios` int(11) NOT NULL DEFAULT '1',
								`listas` int(11) NOT NULL DEFAULT '1',
								`outs` int(11) NOT NULL DEFAULT '1',
								`pagos` int(11) NOT NULL DEFAULT '1',
								`produccion` int(11) NOT NULL DEFAULT '1',
								`respuestas` int(11) NOT NULL DEFAULT '1',
								`stock` int(11) NOT NULL DEFAULT '1',
								`certificados` int(11) NOT NULL DEFAULT '1',
								`cotizador2` int(11) NOT NULL DEFAULT '1',
								`rrhh` int(11) NOT NULL DEFAULT '1',
								`firma` varchar(80) CHARACTER SET utf8 COLLATE utf8_spanish_ci
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",



		"presupuestos"	=> "CREATE TABLE `presupuestos` (
								`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
								`id_usuario` int(11) NOT NULL,
								`nombre` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`apellido` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`telefono` varchar(200) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`correo` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`detalles` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`condiciones` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`importe` float NOT NULL,
								`estado` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`fecha` date NOT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8;

							CREATE TABLE `presupuestos_archivos` (
								`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
								`id_presupuesto` int(11) NOT NULL,
								`file` varchar(500) CHARACTER SET utf8 NOT NULL,
								`mimetype` varchar(100) CHARACTER SET utf8 NOT NULL,
								`size` int(11) NOT NULL,
								`fecha` datetime NOT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8;

							CREATE TABLE `presupuestos_configuracion` (
								`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
								`emailremitente` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`emailconf` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`mensajeprincipal` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`mensajefinal` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`mensajeintermedio` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`asuntoemail` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`mail_logo` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`reenvio_remitente_nombre` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`reenvio_remitente_email` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`reenvio_datos_json` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`empresa` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`web` varchar(120) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`direccion` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`email` varchar(200) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`telefonos` varchar(200) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`logo` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`estados` varchar(1000) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`max_size` int(11) NOT NULL,
								`dias_borrado` int(11) NOT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8;

							CREATE TABLE `presupuestos_observaciones` (
								`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
								`id_presupuesto` int(11) NOT NULL,
								`observacion` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`fecha` datetime NOT NULL,
								`tipo` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`id_user` int(11) NOT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8;

							CREATE TABLE `presupuestos_tareas` (
								`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
								`fecha` date NOT NULL,
								`funcion` varchar(100) CHARACTER SET utf8 NOT NULL,
								`arg` varchar(1000) CHARACTER SET utf8 NOT NULL,
								`id_presupuesto` int(11) NOT NULL,
								`id_accion` int(11) NOT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8;

							INSERT INTO `presupuestos_configuracion` (`id`, `emailremitente`, `emailconf`, `mensajeprincipal`, `mensajefinal`, `mensajeintermedio`, `asuntoemail`, `mail_logo`, `reenvio_remitente_nombre`, `reenvio_remitente_email`, `reenvio_datos_json`, `empresa`, `web`, `direccion`, `email`, `telefonos`, `logo`, `estados`, `max_size`, `dias_borrado`) VALUES
							(1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 500000, 30);
							",



		"sanciones"		=> "CREATE TABLE `sanciones` (
								`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
								`fecha` date NOT NULL,
								`tipo` varchar(256) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
								`tipo1` varchar(256) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`tipo2` varchar(512) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`id_usuario` int(11) NOT NULL,
								`id_empleado` int(11) NOT NULL,
								`observacion` varchar(1000) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8;

							CREATE TABLE `sanciones_configuracion` (
								`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
								`empresa` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`direccion` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`telefono` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`web` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
								`email` varchar(100) CHARACTER SET utf16 COLLATE utf16_spanish_ci NOT NULL,
								`logo` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
								`sanciones_json` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8;",



		"cotizador2"	=> "CREATE TABLE `cot2_catalytics` (
							  `cat_id` mediumint(9) NOT NULL,
							  `cat_code` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
							  `cat_ref1` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
							  `cat_ref2` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
							  `cat_value` decimal(8,2) NOT NULL,
							  `ctr_id` tinyint(4) NOT NULL,
							  `bnd_id` tinyint(4) NOT NULL,
							  `aux_id` tinyint(4) NOT NULL,
							  `cur_id` tinyint(4) NOT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

							CREATE TABLE `cot2_catalytics_images` (
							  `cai_id` bigint(20) NOT NULL,
							  `cai_path` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
							  `cat_id` mediumint(9) NOT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

							CREATE TABLE `cot2_colores` (
							  `id` int(11) NOT NULL,
							  `preciounitario` double NOT NULL,
							  `cantidades100` double NOT NULL,
							  `cantidades200` double NOT NULL,
							  `cantidades500` double NOT NULL,
							  `cantidades1000` double NOT NULL,
							  `cantidades5000` double NOT NULL,
							  `cantidades10000` double NOT NULL
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

							CREATE TABLE `cot2_configuraciones` (
							  `id` int(11) NOT NULL,
							  `emailconf` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
							  `mensajeprincipal` text COLLATE utf8_spanish_ci NOT NULL,
							  `mensajefinal` text COLLATE utf8_spanish_ci NOT NULL,
							  `mensajecotizador` text COLLATE utf8_spanish_ci NOT NULL,
							  `asuntoemail` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
							  `mail_logo` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
							  `descuento` double NOT NULL,
							  `activardescuento` int(11) NOT NULL,
							  `reenvio_remitente_nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
							  `reenvio_remitente_email` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
							  `reenvio_datos_json` text COLLATE utf8_spanish_ci NOT NULL,
							  `habilitar_impresion` tinyint(1) NOT NULL,
							  `empresa` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
							  `web` varchar(120) COLLATE utf8_spanish_ci NOT NULL,
							  `direccion` varchar(500) COLLATE utf8_spanish_ci NOT NULL,
							  `email` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
							  `telefonos` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
							  `logo` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
							  `iva` int(11) NOT NULL
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

							CREATE TABLE `cot2_cotizacion` (
							  `id` int(11) NOT NULL,
							  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
							  `apellidos` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
							  `telefono` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
							  `email` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
							  `fecha` datetime NOT NULL,
							  `descuentoporcentaje` double NOT NULL,
							  `descuento` float NOT NULL,
							  `total` float NOT NULL,
							  `usuario` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
							  `estatus` varchar(100) COLLATE utf8_spanish_ci NOT NULL
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

							CREATE TABLE `cot2_cotizaciondet` (
							  `id` int(11) NOT NULL,
							  `idcot` int(11) NOT NULL,
							  `codigo` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
							  `descripcion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
							  `cantidad` float NOT NULL,
							  `colores` int(11) NOT NULL,
							  `precio` float NOT NULL,
							  `total` float NOT NULL
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

							CREATE TABLE `cot2_observaciones` (
							  `id` int(11) NOT NULL,
							  `idcotizacion` int(11) NOT NULL,
							  `observacion` text COLLATE utf8_spanish_ci NOT NULL,
							  `fecha` datetime NOT NULL,
							  `tipo` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
							  `id_user` int(11) NOT NULL
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

							CREATE TABLE `cot2_productos` (
							  `id` int(11) NOT NULL,
							  `codigo` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
							  `rubro` int(11) NOT NULL,
							  `subrubro` int(11) NOT NULL,
							  `descripcion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
							  `medida` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
							  `espesor` float NOT NULL,
							  `packaging` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
							  `preciounitario` float NOT NULL,
							  `cantidades100` float NOT NULL,
							  `cantidades200` float NOT NULL,
							  `cantidades500` float NOT NULL,
							  `cantidades1000` float NOT NULL,
							  `cantidades5000` float NOT NULL,
							  `cantidades10000` float NOT NULL,
							  `cantidadminima` int(11) NOT NULL
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

							CREATE TABLE `cot2_rubros` (
							  `id` int(11) NOT NULL,
							  `descripcion` varchar(255) COLLATE utf8_spanish_ci NOT NULL
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

							CREATE TABLE `cot2_subrubros` (
							  `id` int(11) NOT NULL,
							  `idrubro` int(11) NOT NULL,
							  `descripcion` varchar(255) COLLATE utf8_spanish_ci NOT NULL
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

							CREATE TABLE `cot2_tareas` (
							  `id` int(11) NOT NULL,
							  `fecha` date NOT NULL,
							  `funcion` varchar(100) CHARACTER SET utf8 NOT NULL,
							  `arg` varchar(1000) CHARACTER SET utf8 NOT NULL,
							  `id_cotizacion` int(11) NOT NULL,
							  `id_accion` int(11) NOT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=latin1;

							CREATE TABLE `cot2_usuarios` (
							  `id` int(4) NOT NULL,
							  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
							  `user` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
							  `pass` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
							  `correo` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
							  `permisos` varchar(150) COLLATE utf8_spanish_ci NOT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

							--
							-- Índices para tablas volcadas
							--
							ALTER TABLE `cot2_catalytics`
							  ADD PRIMARY KEY (`cat_id`),
							  ADD KEY `idx_ctr_id` (`ctr_id`),
							  ADD KEY `idx_bnd_id` (`bnd_id`),
							  ADD KEY `idx_aux_id` (`aux_id`),
							  ADD KEY `idx_cat_code` (`cat_code`),
							  ADD KEY `idx_cur_id` (`cur_id`);
							ALTER TABLE `cot2_catalytics_images`
							  ADD PRIMARY KEY (`cai_id`),
							  ADD UNIQUE KEY `cat_id` (`cat_id`),
							  ADD KEY `idx_cat_id` (`cat_id`),
							  ADD KEY `cai_path_2` (`cai_path`);
							ALTER TABLE `cot2_colores`
							  ADD PRIMARY KEY (`id`);
							ALTER TABLE `cot2_configuraciones`
							  ADD PRIMARY KEY (`id`);
							ALTER TABLE `cot2_cotizacion`
							  ADD PRIMARY KEY (`id`);
							ALTER TABLE `cot2_cotizaciondet`
							  ADD PRIMARY KEY (`id`);
							ALTER TABLE `cot2_observaciones`
							  ADD PRIMARY KEY (`id`);
							ALTER TABLE `cot2_productos`
							  ADD PRIMARY KEY (`id`),
							  ADD UNIQUE KEY `codigo` (`codigo`);
							ALTER TABLE `cot2_rubros`
							  ADD PRIMARY KEY (`id`);
							ALTER TABLE `cot2_subrubros`
							  ADD PRIMARY KEY (`id`);
							ALTER TABLE `cot2_tareas`
							  ADD PRIMARY KEY (`id`);
							ALTER TABLE `cot2_usuarios`
							  ADD UNIQUE KEY `id` (`id`);

							--
							-- AUTO_INCREMENT de las tablas volcadas
							--
							ALTER TABLE `cot2_catalytics`
							  MODIFY `cat_id` mediumint(9) NOT NULL AUTO_INCREMENT;
							ALTER TABLE `cot2_catalytics_images`
							  MODIFY `cai_id` bigint(20) NOT NULL AUTO_INCREMENT;
							ALTER TABLE `cot2_colores`
							  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
							ALTER TABLE `cot2_configuraciones`
							  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
							ALTER TABLE `cot2_cotizacion`
							  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
							ALTER TABLE `cot2_cotizaciondet`
							  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
							ALTER TABLE `cot2_observaciones`
							  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
							ALTER TABLE `cot2_productos`
							  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
							ALTER TABLE `cot2_rubros`
							  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
							ALTER TABLE `cot2_subrubros`
							  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
							ALTER TABLE `cot2_tareas`
							  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
							ALTER TABLE `cot2_usuarios`
							  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
							ALTER TABLE `cot2_catalytics_images`
							  ADD CONSTRAINT `fky_cai_cat_id` FOREIGN KEY (`cat_id`) REFERENCES `cot2_catalytics` (`cat_id`) ON UPDATE CASCADE;
							COMMIT;

							INSERT INTO `cot2_configuraciones` (`id`, `emailconf`, `mensajeprincipal`, `mensajefinal`, `mensajecotizador`, `asuntoemail`, `mail_logo`, `descuento`, `activardescuento`, `reenvio_remitente_nombre`, `reenvio_remitente_email`, `reenvio_datos_json`, `habilitar_impresion`, `empresa`, `web`, `direccion`, `email`, `telefonos`, `logo`, `iva`) VALUES
							(1, '', '', '', '', '', '', 0, 0, '', '', '[]', 0, '', '', '', '', '', '', 0);

							INSERT INTO `cot2_colores` (`id`, `preciounitario`, `cantidades100`, `cantidades200`, `cantidades500`, `cantidades1000`, `cantidades5000`, `cantidades10000`) VALUES
							(1, 15, 12, 8, 6, 5, 4, 4);
							",



		"fichada"		=> "CREATE TABLE `fich_asistencia` (
							  `id` int(10) UNSIGNED NOT NULL,
							  `n_identificacion` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
							  `tipo` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
							  `tipo_historico` int(11) NOT NULL,
							  `fecha` date NOT NULL,
							  `hora` time NOT NULL,
							  `ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
							  `created_at` timestamp NULL DEFAULT NULL,
							  `updated_at` timestamp NULL DEFAULT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

							CREATE TABLE `fich_asistencia_fotos` (
							  `id` int(10) UNSIGNED NOT NULL,
							  `id_asistencia` int(11) NOT NULL,
							  `n_identificacion` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
							  `foto` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
							  `fecha` date NOT NULL,
							  `hora` time NOT NULL,
							  `created_at` timestamp NULL DEFAULT NULL,
							  `updated_at` timestamp NULL DEFAULT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

							CREATE TABLE `fich_conf_anticipos` (
							  `id` int(10) UNSIGNED NOT NULL,
							  `id_usuario` int(11) NOT NULL,
							  `fecha` date NOT NULL,
							  `id_empleado` int(11) NOT NULL,
							  `importe` double(8,2) NOT NULL,
							  `observacion` text COLLATE utf8_unicode_ci,
							  `estatus` int(11) NOT NULL,
							  `created_at` timestamp NULL DEFAULT NULL,
							  `updated_at` timestamp NULL DEFAULT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

							CREATE TABLE `fich_conf_cargos` (
							  `id` int(10) UNSIGNED NOT NULL,
							  `id_usuario` int(11) NOT NULL,
							  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
							  `estatus` int(11) NOT NULL,
							  `created_at` timestamp NULL DEFAULT NULL,
							  `updated_at` timestamp NULL DEFAULT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

							CREATE TABLE `fich_conf_departamento` (
							  `id` int(10) UNSIGNED NOT NULL,
							  `id_usuario` int(11) NOT NULL,
							  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
							  `estatus` int(11) NOT NULL,
							  `created_at` timestamp NULL DEFAULT NULL,
							  `updated_at` timestamp NULL DEFAULT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

							CREATE TABLE `fich_conf_dias_no_laborables` (
							  `id` int(10) UNSIGNED NOT NULL,
							  `fecha_inicio` date NOT NULL,
							  `fecha_fin` date NOT NULL,
							  `descripcion` text COLLATE utf8_unicode_ci NOT NULL,
							  `tipo` int(11) NOT NULL,
							  `estatus` int(11) NOT NULL,
							  `created_at` timestamp NULL DEFAULT NULL,
							  `updated_at` timestamp NULL DEFAULT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

							CREATE TABLE `fich_conf_horarios` (
							  `id` int(10) UNSIGNED NOT NULL,
							  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
							  `hora_entrada` time NOT NULL,
							  `hora_salida` time NOT NULL,
							  `tolerancia_entrada` int(11) NOT NULL,
							  `tolerancia_salida` int(11) NOT NULL,
							  `inicio_entrada` time NOT NULL,
							  `final_entrada` time NOT NULL,
							  `inicio_salida` time NOT NULL,
							  `final_salida` time NOT NULL,
							  `dias` text COLLATE utf8_unicode_ci NOT NULL,
							  `created_at` timestamp NULL DEFAULT NULL,
							  `updated_at` timestamp NULL DEFAULT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

							CREATE TABLE `fich_conf_vacaciones` (
							  `id` int(10) UNSIGNED NOT NULL,
							  `id_empleado` int(11) NOT NULL,
							  `fecha_inicio` date NOT NULL,
							  `fecha_fin` date NOT NULL,
							  `descripcion` text COLLATE utf8_unicode_ci NOT NULL,
							  `estatus` int(11) NOT NULL,
							  `created_at` timestamp NULL DEFAULT NULL,
							  `updated_at` timestamp NULL DEFAULT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

							CREATE TABLE `fich_empleados` (
							  `id` int(10) UNSIGNED NOT NULL,
							  `id_usuario` int(11) NOT NULL,
							  `codigo` text COLLATE utf8_unicode_ci NOT NULL,
							  `n_identificacion` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
							  `n_tarjeta` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
							  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
							  `telefono` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
							  `correo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
							  `direccion` text COLLATE utf8_unicode_ci,
							  `id_cargo` int(11) NOT NULL,
							  `id_departamento` int(11) NOT NULL,
							  `id_horario` int(11) NOT NULL,
							  `fecha_ingreso` date NOT NULL,
							  `foto` text COLLATE utf8_unicode_ci NOT NULL,
							  `estatus` int(11) NOT NULL,
							  `created_at` timestamp NULL DEFAULT NULL,
							  `updated_at` timestamp NULL DEFAULT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

							CREATE TABLE `fich_empresa_datos` (
							  `id` int(10) UNSIGNED NOT NULL,
							  `id_usuario` int(11) NOT NULL,
							  `rif` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
							  `razon_social` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
							  `direccion` text COLLATE utf8_unicode_ci NOT NULL,
							  `telefono` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
							  `correo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
							  `representante_legal` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
							  `rif_representante_legal` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
							  `sitio_web` text COLLATE utf8_unicode_ci NOT NULL,
							  `logo` text COLLATE utf8_unicode_ci NOT NULL,
							  `estatus` int(11) NOT NULL,
							  `created_at` timestamp NULL DEFAULT NULL,
							  `updated_at` timestamp NULL DEFAULT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

							CREATE TABLE `fich_mensajes` (
							  `id` int(10) UNSIGNED NOT NULL,
							  `id_usuario` int(11) NOT NULL,
							  `id_empleado` int(11) NOT NULL,
							  `comentario` text COLLATE utf8_unicode_ci NOT NULL,
							  `envia` int(11) NOT NULL,
							  `estatus` int(11) NOT NULL,
							  `created_at` timestamp NULL DEFAULT NULL,
							  `updated_at` timestamp NULL DEFAULT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

							CREATE TABLE `fich_migrations` (
							  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
							  `batch` int(11) NOT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

							CREATE TABLE `fich_password_resets` (
							  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
							  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
							  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

							CREATE TABLE `fich_users` (
							  `id` int(10) UNSIGNED NOT NULL,
							  `id_usuario` int(11) NOT NULL,
							  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
							  `telefono` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
							  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
							  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
							  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
							  `avatar` text COLLATE utf8_unicode_ci NOT NULL,
							  `nivel` int(11) NOT NULL,
							  `tema` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
							  `estatus` int(11) NOT NULL,
							  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
							  `created_at` timestamp NULL DEFAULT NULL,
							  `updated_at` timestamp NULL DEFAULT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

							CREATE TABLE `fich_usr_recuperar1` (
							  `id` int(10) UNSIGNED NOT NULL,
							  `id_usuario` int(11) NOT NULL,
							  `pregunta` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
							  `respuesta` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
							  `estatus` int(11) NOT NULL,
							  `created_at` timestamp NULL DEFAULT NULL,
							  `updated_at` timestamp NULL DEFAULT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

							CREATE TABLE `fich_usr_recuperar2` (
							  `id` int(10) UNSIGNED NOT NULL,
							  `id_usuario` int(11) NOT NULL,
							  `token` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
							  `correo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
							  `estatus` int(11) NOT NULL,
							  `created_at` timestamp NULL DEFAULT NULL,
							  `updated_at` timestamp NULL DEFAULT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

							--
							-- Índices para tablas volcadas
							--
							ALTER TABLE `fich_asistencia`
							  ADD PRIMARY KEY (`id`);
							ALTER TABLE `fich_asistencia_fotos`
							  ADD PRIMARY KEY (`id`);
							ALTER TABLE `fich_conf_anticipos`
							  ADD PRIMARY KEY (`id`);
							ALTER TABLE `fich_conf_cargos`
							  ADD PRIMARY KEY (`id`),
							  ADD UNIQUE KEY `nombre` (`nombre`);
							ALTER TABLE `fich_conf_departamento`
							  ADD PRIMARY KEY (`id`),
							  ADD UNIQUE KEY `nombre` (`nombre`);
							ALTER TABLE `fich_conf_dias_no_laborables`
							  ADD PRIMARY KEY (`id`);
							ALTER TABLE `fich_conf_horarios`
							  ADD PRIMARY KEY (`id`);
							ALTER TABLE `fich_conf_vacaciones`
							  ADD PRIMARY KEY (`id`);
							ALTER TABLE `fich_empleados`
							  ADD PRIMARY KEY (`id`);
							ALTER TABLE `fich_empresa_datos`
							  ADD PRIMARY KEY (`id`);
							ALTER TABLE `fich_mensajes`
							  ADD PRIMARY KEY (`id`);
							ALTER TABLE `fich_password_resets`
							  ADD KEY `password_resets_username_index` (`username`),
							  ADD KEY `password_resets_token_index` (`token`);
							ALTER TABLE `fich_users`
							  ADD PRIMARY KEY (`id`),
							  ADD UNIQUE KEY `users_email_unique` (`email`),
							  ADD UNIQUE KEY `users_username_unique` (`username`);
							ALTER TABLE `fich_usr_recuperar1`
							  ADD PRIMARY KEY (`id`);
							ALTER TABLE `fich_usr_recuperar2`
							  ADD PRIMARY KEY (`id`);

							--
							-- AUTO_INCREMENT de las tablas volcadas
							--
							ALTER TABLE `fich_asistencia`
							  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
							ALTER TABLE `fich_asistencia_fotos`
							  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
							ALTER TABLE `fich_conf_anticipos`
							  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
							ALTER TABLE `fich_conf_cargos`
							  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
							ALTER TABLE `fich_conf_departamento`
							  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
							ALTER TABLE `fich_conf_dias_no_laborables`
							  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
							ALTER TABLE `fich_conf_horarios`
							  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
							ALTER TABLE `fich_conf_vacaciones`
							  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
							ALTER TABLE `fich_empleados`
							  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
							ALTER TABLE `fich_empresa_datos`
							  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
							ALTER TABLE `fich_mensajes`
							  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
							ALTER TABLE `fich_users`
							  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
							ALTER TABLE `fich_usr_recuperar1`
							  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
							ALTER TABLE `fich_usr_recuperar2`
							  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
							COMMIT;",
	];