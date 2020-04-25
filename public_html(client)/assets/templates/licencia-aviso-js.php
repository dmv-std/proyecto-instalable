<script type="text/javascript">
<?php if($license_key && $license_server): ?>
    $(document).ready(function() {
        $.ajax({
            dataType: 'jsonp',
            data: {license: '<?php echo $license_key ?>'},
            url: '<?php echo $license_server ?>/validar-licencia',
            success : function(r) {
                if (r.actived&&!r.error)
                    $('.license-alert').hide()
                else $('.license-alert').show()
            },
            error : function(xhr, status) {
                alert('Disculpe, ocurrió un problema');
            },
        })
    })
<?php elseif(!$license_key): ?>
    $(document).ready(function() {
        $('.license-alert').show()
    })
<?php endif ?>   
</script>
