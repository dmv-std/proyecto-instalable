<?php if (file_exists("config.php"))
        include("config.php");
    else { header("location: instalador"); exit(); }
?>
<?php session_start();
    if(empty($_SESSION['nombrePersona'])){
        header("location: login");
    } else {
        
        $bannerpath = $basepath."/assets/banners";
        $banner_url = $basehttp."/assets/banners";

        $resources = [
            [
                "logo" => "cot2-logo",    "uri" => "/cotizador2",
                "desc" => "Banner de la página cotizador2/index.php"
            ], [
                "logo" => "login-logo",   "uri" => "/login",
                "desc" => "Logo en la página de Login"
            ], [
                "logo" => "login-bg",   "uri" => "/login",
                "desc" => "Imagen de fondo para la página de Login"
            ],
        ];

        foreach ($resources as $resource) {
            $logo[$resource['logo']]="";
            foreach (explode("|", "jpg|jpeg|png|gif") as $ext)
                if (file_exists($bannerpath."/".$resource['logo'].".".$ext))
                    $logo[$resource['logo']]=$banner_url."/".$resource['logo'].".".$ext;
        }
?>
<!DOCTYPE html>
<!--[if IE 8]><html class="ie8"><![endif]-->
<!--[if IE 9]><html class="ie9 gt-ie8"><![endif]-->
<!--[if gt IE 9]><!--> <html class="gt-ie8 gt-ie9 not-ie"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Herramientas de Control y Administración - <?php echo $sitename ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <!-- Open Sans font from Google CDN -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin" rel="stylesheet" type="text/css">
    <!-- Pixel Admin's stylesheets -->
    <link href="<?php echo $styles_url ?>/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $styles_url ?>/pixel-admin.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $styles_url ?>/widgets.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $styles_url ?>/rtl.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $styles_url ?>/themes.min.css" rel="stylesheet" type="text/css">
    <!--[if lt IE 9]>
    <script src="<?php echo $js_url ?>/ie.min.js"></script>
    <![endif]-->
    <style type="text/css">
    section.custom-logo-space {
        height: 200px;
        background: #dbdfec;
        border-bottom: 1px solid #ced1d9;
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        align-items: center;
        margin-bottom: 18px;
    }
    section.custom-logo-space h3{
        color: #666;
        font-weight: bold;
        text-shadow: 1px 1px 0 rgba(255, 255, 255, 0.25);
        width: 300px;
    }
    section.custom-logo-space > a{
        width: 130px;
        height: 100px;
        border: 6px dashed #666;
        color: #666;
        text-align: center;
        line-height: 96px;
        text-shadow: 1px 1px 0 rgba(255, 255, 255, 0.25);
    }
    section.custom-logo-space div.no-logo{
        width: 270px;
        height: 150px;
        background: #bbc0d0;
        color: #fff;
        font-size: 2.2rem;
        text-align: center;
        line-height: 150px;
        font-weight: bold;
        border-bottom: 1px solid #a7abb9;
        border-right: 1px solid #a7abb9;
        position: relative;
    }
    form{display: none}
    .progress{
        width: 50%;
        margin: auto;
        position: relative;
        top: 50%;
        transform: translateY(-50%);
        height: 25px;
    }
    .no-logo img{
        max-width: 100%;
        max-height: 100%;
    }
    .no-logo a{
        border: none;
        background: #b14545;
        padding: 0px 4px;
        color: black;
        border-radius: 4px;
        position: absolute;
        display: block;
        bottom: 5px;
        right: 5px;
        box-shadow: 2px 2px 4px 0 rgba(0,0,0,.3);
        line-height: initial;
        width: initial;
        height: initial;
    }
    </style>
</head>
<body class="theme-frost no-main-menu">
    <script>var init = [];</script>
    <div id="main-wrapper">

    <?php include("header.php");?>


        <div>
            <div class="page-header">

                <div class="row">
                    <!-- Page header, center on small screens -->
                    <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-picture-o page-header-icon"></i>&nbsp;&nbsp;Personalizar banners</h1>
                    
                </div>
            </div> <!-- / .page-header -->

            <?php foreach($resources as $resource): ?>
                <section class="custom-logo-space">
                    <div>
                        <h3><a href="<?php echo $basehttp.$resource['uri'] ?>"><?php echo $resource['uri']?></a></h3>
                        <p>(<?php echo $resource['desc'] ?>)</p>
                    </div>
                    <a href="#" data-toggle="tooltip" data-placement="bottom" title="Subir logo"><i class="fa fa-picture-o fa-2x"></i></a>
                    <div class="no-logo">
                        <?php if ($logo[$resource['logo']]):?>
                            <img src="<?php echo $logo[$resource['logo']]?>"/>
                            <a href="#" class="btn-remover-logo" data-toggle="tooltip" data-placement="bottom" title="Remover logo"><i class="fa fa-trash-o"></i></a>
                        <?php else: ?>
                            Sin banner
                        <?php endif ?>
                    </div>
                    <form>
                        <input type="file" name="<?php echo $resource['logo']?>"/>
                        <input type="hidden" name="logo" value="<?php echo $resource['logo']?>"/>
                        <input type="hidden" name="action" value="subir"/>
                    </form>
                </section>
            <?php endforeach ?>

        </div> <!-- / #content-wrapper -->
        <div id="main-menu-bg"></div>
    </div> <!-- / #main-wrapper -->

    <?php if ($load_resources_locally): ?>
        <script src="<?php echo $js_url?>/jquery-2.0.3.min.js"></script>
    <?php else: ?>
    <!-- Get jQuery from Google CDN -->
    <!--[if !IE]> -->
    <script type="text/javascript"> window.jQuery || document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js">'+"<"+"/script>"); </script>
    <!-- <![endif]-->
    <!--[if lte IE 9]>
    <script type="text/javascript"> window.jQuery || document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js">'+"<"+"/script>"); </script>
    <![endif]-->
    <?php endif ?>


    <!-- Pixel Admin's javascripts -->
    <script src="<?php echo $js_url ?>/bootstrap.min.js"></script>
    <script src="<?php echo $js_url ?>/pixel-admin.min.js"></script>
    <script type="text/javascript">
        init.push(function () {
            // Javascript code here
        })
        window.PixelAdmin.start(init);

        Array.prototype.last = function(){
            return this[this.length - 1];
            //return this.slice(-1); // alternative!
        };

        $("section.custom-logo-space>a").on( "click", function(e) {
            e.preventDefault()
            $(this).closest('section').find('input').click()
        })
        $('input').change(function() {
            var file = this.files[0];
            var $thisInput = $(this);
            if (file.type == "image/jpeg" || file.type == "image/png" || file.type == "image/jpg" || file.type == "image/gif") {
                $.ajax({
                    url: "subir-banner.php",
                    xhr: function () { // custom xhr (is the best)
                        var xhr = new XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = ((evt.loaded / evt.total) * 100);
                                $thisInput.closest('section').find('.progress-bar').css( "width", percentComplete.toFixed()+'%' )
                            }
                        }, false);
                        return xhr;
                    },
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    data: new FormData(document.querySelector('input[name='+$(this).prop('name')+']').closest('form')),
                    beforeSend: function(){
                        $thisInput.closest('section').find('.no-logo').html( `
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                    <span class="sr-only">45% Complete</span>
                                </div>
                            </div>` )
                    },
                    success: function (respuesta) {
                        if (respuesta != "ERROR") {
                            $thisInput.closest('section').find('.no-logo').html(`
                                <img src="${respuesta}"/>
                                <a href="#" class="btn-remover-logo" data-toggle="tooltip" data-placement="bottom" title="Remover logo"><i class="fa fa-trash-o"></i></a>
                            `)
                            $('.btn-remover-logo').unbind()
                            $('.btn-remover-logo').on('click', removerLogo)
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + ', ' + thrownError + '\n');
                    }
                });
            } else {
                alert('ERROR: solo son admitidos los archivos jpg/jpeg, png y gif.');
                return false;
            }
        })
        $('.btn-remover-logo').on('click', removerLogo)

        function removerLogo(e) {
            e.preventDefault()
            var $thisButton = $(this)
            $.ajax({
                url: "subir-banner.php",
                data: {
                    action: "remover", logo: "<?php echo $bannerpath."/" ?>"+$(this).closest('.no-logo').find('img').attr('src').split('/').last(),
                },
                type: 'POST',
                dataType: 'html',
                success: function() {
                    $thisButton.closest('.no-logo').text('Sin banner');
                },
                error: function(xhr, status) {
                    alert('Un error inesperado ha ocurrido.');
                    console.log({xhr:xhr, status:status})
                },
            })
        }
      
        $('[data-toggle="tooltip"]').tooltip()
    </script>

</body>
</html>
<?php } ?>