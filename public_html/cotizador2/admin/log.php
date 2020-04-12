<?php
  //Se incluye el archivo de BD
  require_once("myDBC.php");
  //Se crea un objeto
  $consultas = new myDBC();
  //Se reciben los datos del formulario del index.php
  //Se les aplica trim para quitar espacios en blanco
  $user = trim($_GET['mail']);
  $pass = trim($_GET['pass']);
  //Se usa el método logueo de la clase y éste se encarga
  //de mostrar la información necesaria
  $log = $consultas->logueo($user, $pass);
?>