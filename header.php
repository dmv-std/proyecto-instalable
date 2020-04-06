<nav class="navbar navbar-default" role="navigation">
  <!-- El logotipo y el icono que despliega el menú se agrupan
       para mostrarlos mejor en los dispositivos móviles -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse"
            data-target=".navbar-ex1-collapse">
      <span class="sr-only">Desplegar navegación</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="<?php echo $basehttp ?>">
    Bienvenido <?php echo $_SESSION['nombrePersona']; ?>
    </a>
  </div>
  
  
 
  <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
  <div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav">
      <p class="navbar-text pull-left">
      
      </p>
	  <?php if($_SESSION['permisosPersona'] != "externo") { ?><li><a href="enlaces">Enlaces</a></li> <?php } ?>
      <?php if($_SESSION['permisosPersona'] == "admin") { ?><li><a href="usuarios">Administrar Usuarios</a></li> <?php } ?>
      <?php if($_SESSION['permisosPersona'] == "admin") { ?><li><a href="acceso">Registro Accesos</a></li> <?php } ?>
      <?php /*if($_SESSION['permisosPersona'] == "admin") { ?><li><a href="http://sistemas.evamagic.com.ar/formularios/cron.php" target="_blank">CRON</a></li> <?php }*/ ?>
      <li><a href="salir">Cerrar Sesión.</a></li>
    </ul>
 
  </div>
</nav>