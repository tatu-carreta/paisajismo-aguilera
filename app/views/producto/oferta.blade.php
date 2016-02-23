@extends($project_name.'-master')

@section('head')@stop
@section('header')@stop

@section('contenido')
<script>
$(document).ready(function () {
    $(".valid-number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl+A
                        (e.keyCode == 65 && e.ctrlKey === true) ||
                        // Allow: home, end, left, right
                                (e.keyCode >= 35 && e.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
});

function validatePrecioProd(form)
{
    var ok = true;

    var precio_antes = parseInt($(form).find("input[name='precio_antes']").val());
    var precio_ahora = parseInt($(form).find("input[name='precio_actual']").val());

    if (precio_antes <= precio_ahora)
    {
        ok = false;
        alert('El precio oferta es mayor que el precio anterior.');
    }
    
    return ok;
}
</script>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Producto en oferta</h4>
    </div>
    {{ Form::open(array('url' => 'admin/producto/oferta', 'onsubmit' => 'return validatePrecioProd(this);')) }}
        <div class="modal-body">
            <p>Precio anterior</p>
            <div class="form-group marginBottom2">
                <input class="form-control valid-number" type="text" name="precio_antes" required="true">
            </div>
            <p>Precio oferta</p>
            <div class="form-group marginBottom2">
                <input class="form-control valid-number" type="text" name="precio_actual" required="true">
            </div>
            
            {{Form::hidden('continue', $continue)}}
            {{Form::hidden('producto_id', $producto->id)}}
            {{Form::hidden('seccion_id', $seccion_id)}}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    {{Form::close()}}
@stop

@section('footer')@stop