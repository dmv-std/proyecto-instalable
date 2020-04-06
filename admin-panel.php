<?php

die("Página no operativa.");

require_once("config.php");

$conexion = new mysqli();
$conexion->connect($dbserver, $dbuser, $dbpass, $dbname);
if($conexion->connect_error){
    die("No hubo conexión: ".$conexion->connect_error);
}

session_start();
error_reporting(0);

$nombre = $_SESSION['nombrePersona'];
$rolPag = $_SESSION['rolPagPersona'];



    if($nombre == null || $nombre=='' ){
    header ("Location: /program/pagos/admin.php");
    die();
    
}

?>
<!DOCTYPE html>
<!--[if IE 8]><html class="ie8"><![endif]-->
<!--[if IE 9]><html class="ie9 gt-ie8"><![endif]-->
<!--[if gt IE 9]><!--> <html class="gt-ie8 gt-ie9 not-ie"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Portal de Notificación de Pagos - <?php echo $sitename ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <!-- Open Sans font from Google CDN -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.1.0/css/buttons.dataTables.min.css">

    <!-- Pixel Admin's stylesheets -->
    <link href="assets/stylesheets/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/stylesheets/pixel-admin.min.css" rel="stylesheet" type="text/css">
    <link href="assets/stylesheets/widgets.min.css" rel="stylesheet" type="text/css">
    <link href="assets/stylesheets/rtl.min.css" rel="stylesheet" type="text/css">
    <link href="assets/stylesheets/themes.min.css" rel="stylesheet" type="text/css">
    <link href="modal.css" rel="stylesheet" type="text/css">
    <!--[if lt IE 9]>
    <script src="assets/javascripts/ie.min.js"></script>
    <![endif]-->
               <link rel="stylesheet" href="http://code.|com/ui/1.10.1/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
<script type="text/javascript" src="js/dialogo-panel.js"></script>
</head>
<body class="theme-frost no-main-menu">
<div id="main-wrapper">
<nav class="navbar navbar-default navbar-fixed-top">
                          <div class="container-fluid">
                              <!-- Ultra Admin and toggle get grouped for better mobile display -->
                              <div class="navbar-header">
                                
                                  <a class="navbar-brand" href="../">Hola <?php echo $nombre; ?></a>
                              </div>
                              <div class="collapse navbar-collapse pull-right" id="bs-example-navbar-collapse-3">
                                  <a href="../#as"><button type="button" class="btn btn-default navbar-btn">Vista Principal</button></a>
                              </div>

                              <!-- Collect the nav links, forms, and other content for toggling -->
                              <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                  <ul class="nav navbar-nav">
                                     <li><a href="/program/pagos">Subir Pago</a></li>
                                    <li><a href="#">Administrar Pagos</a></li>
                                    <?php if($rolPag == 1) { echo "<li><a href='admin-users.php'>Administrar Usuarios</a></li>"; } else {} ?>
                                    <?php if($rolPag == 1) { echo "<li><a href='editar_info.php'>Configuracion</a></li>"; } else {} ?>
                                    <li><a href="cerrarSesion.php">Cerrar Sesion</a></li>
                                  </ul><!-- / .navbar-nav -->
                              </div><!-- /.navbar-collapse -->
                          </div><!-- /.container-fluid -->
 </nav>

    <div id="content-wrapper">
        <div class="page-header">

            <div class="row">
                <!-- Page header, center on small screens -->
                <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-dashboard page-header-icon"></i>&nbsp;&nbsp;Lista de Pagos</h1>
            </div>
        </div> <!-- / .page-header -->


        <div class="row">
            <div class="row">
           <!-- / Javascript -->
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">Lista de Pagos</span>
                    </div>
                    <div class="panel-body">
                        <div class="table-primary">
                        <div id="ocultar">
                    <a href="http://pagos.evamagic.com.ar">
                        <button class="btn btn-primary">Cargar Nuevo Pago</button>
                    </a>
                    <button class="btn btn-success" onclick="printPage()">Imprimir</button>
                    </div>

<!-- MODALES-->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
          <div class="modal-content">      
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel<?php echo $row['id'];?>" >Editar Informacion de Pagos</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">DATOS GENERALES DEL PAGO.</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="control-group">
                                <div class="col-md-2">
                                    <label for="id">#</label>        
        <input type="text" <?php if($row['estado'] != 'PENDIENTE') { echo "disabled='disabled'";}?> class="infoPagos form-control" id="id">
                                </div>
                                <div class="col-md-4">
                                 <label for="rs">Razon Social</label>
        <input type="text"  class="infoPagos form-control" id="rsup" >
                                </div>
                                <div class="col-md-6">
                                <label for="fecha">Email</label>
        <input type="text"  class="infoPagos form-control" id="emailup">
                                </div>                              
                            </div>
                        </div>
                        <div class="row">
                            <div class="control-group">
                            <div class="col-md-4">  
        <label for="num">Fecha (AAAA-MM-DD)</label>
        <input type="text"  class="infoPagos form-control" id="fechup">       
        </div>
        <div class="col-md-4">
        <label for="entidad"># Operacion</label>
        <input type="text"  class="infoPagos form-control" id="numup">       
        </div>
        <div class="col-md-4">
        <label for="importe">Banco/Entidad</label>
        <select class="infoPagos form-control"  id="bancup">                                    
                                    <?php
                                    $tipo = "banco";                                    
                                    $rst_categorias = mysql_query("SELECT nombre FROM info_adicional WHERE tipo='$tipo'",$conexion);
                                    while($fila=mysql_fetch_array($rst_categorias)){
                                        echo "<option value='". $fila["nombre"] ."'>" . $fila["nombre"] . "</option>";
                                    }                            
                                    ?>
        </select>       
        </div>
                            </div>
                        </div>
                        <div class="row">
        <div class="col-md-3">
        <label for="importe">Importe Total:</label>
        <input type="text"  class="infoPagos form-control" id="impup" >
        </div>
        <div class="col-md-3">       
        <label for="importe"># Factura</label>
        <input type="text"  class="infoPagos form-control" id="facup" >        
        </div>
        <div class="col-md-6">
        <label for="tipo_d">Tipo de Depósito:</label>
         <select class="infoPagos form-control"  id="statup">
             <option value="DEPOSITO ADELANTADO">DEPÓSITO ADELANTADO</option>
             <option value="DEPOSITO A CUENTA CORRIENTE">DEPÓSITO A CUENTA CORRIENTE</option>
        </select>
        </div>
        
                        </div>
                        <div class="row">
                        <label for="importe">Observaciones</label>
        <textarea  class="infoPagos form-control" id="obsup"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
        <button type="button" onclick="updatedata('<?php echo $row['id'];?>')" class="btn btn-primary">APLICAR CAMBIOS</button>
            </div>
          </div>
    </div>
</div>
<!-- MODALES -->
<!-- MODALES -->
<!-- MODALEs -->


                              <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
                                 <thead>
                                <tr>               
<th>ID</th>    
                                    <th>RAZON SOCIAL</th>
                                    <th>EMAIL</th>
                                    <th>FECHAFECHA</th>
                                    <th>OPERACION</th>
                                    <th>ENTIDAD</th>
                                    <th>IMPORTE</th>
                                    <th>FACTURA</th>
                                    <th>DEPOSITO</th>
                                    <th>OBSERVACIONES</th>
                                    <th>ESTADO</th>
                                    <th>SUBIDO POR</th>
                                    <th>OPCIONES</th>
                                </tr>
                                <tr id="filterrow">
              <th>ID</th>
                                    <th>RAZON SOCIAL</th>
                                    <th>EMAIL</th>
                                    <th>FECHA</th>
                                    <th>OPERACION</th>
                                    <th>ENTIDAD</th>
                                    <th>IMPORTE</th>
                                    <th>FACTURA</th>
                                    <th>DEPOSITO</th>
                                    <th>OBSERVACIONES</th>
                                    <th>ESTADO</th>
                                    <th>SUBIDO POR</th>
                                    <th>OPCIONES</th>                                    
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                include("conexion_db.php");
                                $rst_pagos = mysql_query("SELECT * FROM pagos ORDER BY fecha DESC ",$conexion);
            while ($row=mysql_fetch_array($rst_pagos)){
                    echo '<tr data-id="'.$row['id'].'"> ';
            ?>                                 
                                <td <?php if($row['estado'] == "PENDIENTE" ){ echo 'style="background-color:#ff0033;"'; } else if($row['estado'] == "VERIFICADO" ) { echo "style=background-color:#ffff00;";} else if($row['estado'] == "APLICADO") { echo "style=background-color:#00ff33;"; }?> > <?php echo $row['id'];?></td>
                                <td <?php if($row['estado'] == "PENDIENTE" ){ echo 'style="background-color:#ff0033;"'; } else if($row['estado'] == "VERIFICADO" ) { echo "style=background-color:#ffff00;";} else if($row['estado'] == "APLICADO") { echo "style=background-color:#00ff33;"; }?> > <?php echo $row['razon'];?></td>
                                <td <?php if($row['estado'] == "PENDIENTE" ){ echo 'style="background-color:#ff0033;"'; } else if($row['estado'] == "VERIFICADO" ) { echo "style=background-color:#ffff00;";} else if($row['estado'] == "APLICADO") { echo "style=background-color:#00ff33;"; }?> ><?php echo "<a href='mailto:" . $row['email'] . "'>" . $row['email'] . "</a>"; ?> </td>
                                <td <?php if($row['estado'] == "PENDIENTE" ){ echo 'style="background-color:#ff0033;"'; } else if($row['estado'] == "VERIFICADO" ) { echo "style=background-color:#ffff00;";} else if($row['estado'] == "APLICADO") { echo "style=background-color:#00ff33;"; }?> ><?php echo $row['fecha'];?></td>
                                <td <?php if($row['estado'] == "PENDIENTE" ){ echo 'style="background-color:#ff0033;"'; } else if($row['estado'] == "VERIFICADO" ) { echo "style=background-color:#ffff00;";} else if($row['estado'] == "APLICADO") { echo "style=background-color:#00ff33;"; }?> ><?php echo $row['num'];?></td>
                                <td <?php if($row['estado'] == "PENDIENTE" ){ echo 'style="background-color:#ff0033;"'; } else if($row['estado'] == "VERIFICADO" ) { echo "style=background-color:#ffff00;";} else if($row['estado'] == "APLICADO") { echo "style=background-color:#00ff33;"; }?> ><?php echo $row['banco'];?></td>
                                <td <?php if($row['estado'] == "PENDIENTE" ){ echo 'style="background-color:#ff0033;"'; } else if($row['estado'] == "VERIFICADO" ) { echo "style=background-color:#ffff00;";} else if($row['estado'] == "APLICADO") { echo "style=background-color:#00ff33;"; }?> ><?php echo "$" . number_format($row['importe'], 2, ',', '');?></td>
                                <td <?php if($row['estado'] == "PENDIENTE" ){ echo 'style="background-color:#ff0033;"'; } else if($row['estado'] == "VERIFICADO" ) { echo "style=background-color:#ffff00;";} else if($row['estado'] == "APLICADO") { echo "style=background-color:#00ff33;"; }?> ><?php echo $row['nfact'];?></td>
                                <td <?php if($row['estado'] == "PENDIENTE" ){ echo 'style="background-color:#ff0033;"'; } else if($row['estado'] == "VERIFICADO" ) { echo "style=background-color:#ffff00;";} else if($row['estado'] == "APLICADO") { echo "style=background-color:#00ff33;"; }?> ><?php echo $row['tipo_d'];?></td>
                                <td <?php if($row['estado'] == "PENDIENTE" ){ echo 'style="background-color:#ff0033;"'; } else if($row['estado'] == "VERIFICADO" ) { echo "style=background-color:#ffff00;";} else if($row['estado'] == "APLICADO") { echo "style=background-color:#00ff33;"; }?> ><?php echo $row['observ'];?></td>
                                <td>
                                 <?php if ($row['estado'] == "PENDIENTE"){
                                    echo "<button class='btn btn-danger btn-lg' id='btn-aplicado' disabled='disabled' >PENDIENTE</button><br />";
                                    if ($rolPag == 1){ 
                                    echo "<a href='javascript:;' onclick=modalV('". $row['id'] ."'); ><button class='btn btn-warning' id='btn' ><i class=\"fa fa-check\"></i></button></a><a href='javascript:;' onclick=modalA('". $row['id'] ."'); ><button class='btn btn-success ' id='btn-aplicado' ><i class=\"fa fa-check\"></i></button></a>";}
                                    else if($rolPag == 0){ 
                                echo "<a href='javascript:;' onclick=modalSV('". $row['id'] ."'); ><button class='btn btn-warning' id='btn' >SOLICITAR VERIF.</button></a><a href='javascript:;' onclick=modalSA('". $row['id'] ."');><button class='btn btn-success ' id='btn-aplicado' >SOLICITAR APLIC.</button></a>";} }
                                    else if ($row['estado'] == "VERIFICADO") {
                                        echo "<button class='btn btn-warning btn-lg' id='btn-aplicado' disabled='disabled' >VERIFICADO</button><br />";
                                    if ($rolPag == 1){
                                    
                                    echo "<a href='javascript:;' onclick=modalA('". $row['id'] ."'); return false;><button class='btn btn-success ' id='btn-aplicado' ><i class=\"fa fa-check\"></i></button></a>";} 
                                    else if ($rolPag == 0){
                                        
                                        echo "<a href='javascript:;' onclick=modalSA('". $row['id'] ."'); return false;><button class='btn btn-success ' id='btn-aplicado' >SOLICITAR APLIC.</button></a>";}
                                    } 
                                    else if($row['estado'] == "APLICADO"){ 
                                    echo "<button class='btn btn-success btn-lg' id='btn-aplicado' disabled='disabled' >APLICADO</button>";}
                                    
                                    ?>
                                </td>
                                <td <?php if($row['estado'] == "PENDIENTE" ){ echo 'style="background-color:#ff0033;"'; } else if($row['estado'] == "VERIFICADO" ) { echo "style=background-color:#ffff00;";} else if($row['estado'] == "APLICADO") { echo "style=background-color:#00ff33;"; }?> ><?php echo $row['upby'];?></td>
                                <td style="background-color:<?php if($row['estado'] == "PENDIENTE") { echo '#FF0000"'; } else if($row['estado'] == "VERIFICADO"){ echo '#FF9900"';} else if($row['estado'] == "APLICADO"){ echo '#006600"';} ?>"> 
                                <button type="button" class="btn btn-warning" onclick="actualizar(<?php echo $row['id']; ?>) " ><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                <?php if($rolPag == 1) { echo "<button tpye='button' class='btn btn-danger' onclick=eliminar('". $row['id'] ."')> <span class='glyphicon glyphicon-trash' aria-hidden='true'></span></button>"; } ?></td>
                                    </tr>
                                    <?php } ?>
                                 </tbody>
                            </table>
              </div>
                    </div>
                </div>


            </div>
        </div> <!-- / #content-wrapper -->
        <div id="main-menu-bg"></div>
    </div> <!-- / #main-wrapper -->

    <!-- Get jQuery from Google CDN -->
    <!--[if !IE]> -->
    <script type="text/javascript"> window.jQuery || document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js">'+"<"+"/script>"); </script>
    <!-- <![endif]-->
    <!--[if lte IE 9]>
    <script type="text/javascript"> window.jQuery || document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js">'+"<"+"/script>"); </script>
    <![endif]-->
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>

    <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.11.3.min.js">
    </script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js">
    </script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.1.0/js/dataTables.buttons.min.js">
    </script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.1.0/js/buttons.flash.min.js">
    </script>
    <script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js">
    </script>
    <script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js">
    </script>
    <script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js">
    </script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.1.0/js/buttons.html5.min.js">
    </script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.1.0/js/buttons.print.min.js">
    </script>
  
    <!-- Pixel Admin's javascripts -->
    <script src="assets/javascripts/bootstrap.min.js"></script>


    <script type="text/javascript">
    function modalV(str){
        var id = str;
        $("#modalVerificar"+str).modal("show");
    }
    function modalSV(str){
        var id = str;
        $("#modalSolVer"+str).modal("show");
    }
    function modalA(str){
        var id = str;
        $("#modalAplicar"+str).modal("show");
    }
    function modalSA(str){
        var id = str;
        $("#modalSolApl"+str).modal("show");
    }
    function verificar(str){
        var paramVal = str;
        $.ajax({
             type: "POST",
              url: "upst_v.php?id="+paramVal,
              data: "id"+paramVal,
          success: function () {
            location.reload();                            
              }              
          });
    }
    function aplicar(str){
        var paramVal = str;
        $.ajax({
             type: "POST",
              url: "upst_p.php?id="+paramVal,
              data: "id"+paramVal,
          success: function () {
            location.reload();
                    $('.success').show();              
              }              
          });
    }
    function solverif(str){
        var paramVal = str;
         $.ajax({
             type: "POST",
              url: "sol_v.php?id="+paramVal,
              data: "id"+paramVal,
          success: function () {
            location.reload();
                    $('.success').show();
              
              }
              
          });
    }
    function solaplicar(str){
        var paramVal = str;
        $.ajax({
             type: "POST",
              url: "sol_p.php?id="+paramVal,
              data: "id"+paramVal,
          success: function () {
            location.reload();
                    $('.success').show();
              
              }
              
          });
    }
    function printPage() {    
    document.getElementById("ocultar").style.display = "none";
    window.print();
    }
        // Setup - add a text input to each footer cell
    $('#example thead tr#filterrow th').each( function () {
        var title = $('#example thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" class="form-control" onclick="stopPropagation(event);" placeholder="Buscar"/>' );
    } );
    // Apply the filter
    $("#example thead input").on( 'keyup change', function () {
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
    } );
 
    // DataTable
var table = $('#example').DataTable( {
     "language": {
        "decimal":        ",",
        "thousands":      ".",
        "emptyTable":     "No hay datos por ahora.",
        "info":           "Mostrando _START_ a _END_ de _TOTAL_ registros",
        "infoEmpty":      "Mostrando 0 a 0 de 0 registros",
        "infoFiltered":   "(filtered from _MAX_ total entries)",
        "infoPostFix":    "",
        "thousands":      ",",
        "lengthMenu":     "Mostrar _MENU_ registros",
        "loadingRecords": "Cargando...",
        "processing":     "Procesando...",
        "search":         "Buscar:",
        "zeroRecords":    "No existen registros con la búsqueda",
        "paginate": {
        "first":      "Primero",
        "last":       "Último",
        "next":       "Siguiente",
        "previous":   "Anterior"
    },

    },
    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todo"]],
    "order": [[ 3, "desc" ]],
  dom: 'Blfrtip',
                buttons: [
        {
            extend: 'excel',
            text: 'Exportar a Excel',
            exportOptions: {
                modifier: {
                    page: 'current'
                }
            }
        }
    ],
    orderCellsTop: true,
    scrollX: true,
    scrollColapse: true
} );
     

  function stopPropagation(evt) {
        if (evt.stopPropagation !== undefined) {
            evt.stopPropagation();
        } else {
            evt.cancelBubble = true;
        }
    }

    </script>

    <script type="text/javascript">
        function actualizar(str){
            var idPago = str;

            $.ajax({
                url : 'php/verPago.php',
                data : { 'id' : idPago },
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {                    
                    var obj = jQuery.parseJSON(respuesta);                    
                    $("#id").val(obj.id);
                    $("#rsup").val(obj.razon);
                    $("#emailup").val(obj.email);
                    $("#fechup").val(obj.nfact);
                    $("#facup").val(obj.fecha);
                    $("#numup").val(obj.num);
                    $("#impup").val(obj.importe);                    
                    $("#bancup").val(obj.banco);
                    $("#statup").val(obj.tipo_d);
                    $("textarea#obsup").val(obj.observ);                                        
                    if(obj.estado != "PENDIENTE"){
                    $("#rsup, #emailup, #fechup, #numup, #impup, #facup, #statup, #obsup, #bancup").prop('disabled', true);                        
                    }                    
                    $("#myModal").modal("show");
                }

        });
    }


       function updatea(){
            
            var id = $("#id").val();
            var nomup = $("#nomup").val();
            var tipup = $("#tipup").val();
            var deup = $("#deup").val();
            var asuup = $("#asuup").val();
            var menup = $("#menup").val();
            var pieup = $("#pieup").val();
            var obsup = $("#obsup").val();
            $.ajax({
             type: "POST",
              url: "updateinfo.php?id="+id+"&nomup="+nomup+"&tipup="+tipup+"&deup="+deup+"&asuup="+asuup+"&menup="+menup+"&pieup="+pieup+"&obsup="+obsup,
              data: "id"+id+"&nomup"+nomup,
          success: function () {
              alert("Datos actualizados correctamente");
              location.reload();
              }
              
          });
        }
    </script>


</body>
</html>