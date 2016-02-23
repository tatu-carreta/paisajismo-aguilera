
$(document).ready(function () {
    if ($('.precioDisabled').attr('checked'))
    {
        $('.precioAble').removeAttr('disabled');
        $('.precioAble').attr('required', 'true');
    }
    $(".imagenEditarNone").removeAttr('required');
    $(".destacadoEditarNone").removeAttr('checked');
});

$(function () {
    $('.class_checkbox').click(function (e) {
        e.preventDefault();
        if (!$('.precioDisabled').attr('checked'))
        {
            $(this).children(":first").addClass('tocado');
            $(this).children(":first").removeClass('noTocado');
            $('.precioAble').removeAttr('disabled');
            $('.precioAble').attr('required', 'true');
            $('.precioDisabled').attr('checked', 'true');
            $('.precioAble').focus();
        }
        else
        {
            $(this).children(":first").addClass('noTocado');
            $(this).children(":first").removeClass('tocado');
            $('.precioAble').attr('disabled', 'disabled');
            $('.precioAble').removeAttr('required');
            $('.precioDisabled').removeAttr('checked');
            $('.precioAble').focusout();
        }
    });
/*
    $(".selectMarca").change(function () {
        var id = $(".selectMarca option:selected").val();
        if (id != "")
        {
            $.post("{{URL::to('admin/marca/imagen')}}", {'marca_id': id}, function (data) {
                $(".marca_imagen_preview").html(data);
            });
        }
        else
        {
            $(".marca_imagen_preview").html("");
        }
    });
    */
});

$(document).ready(function () {
    var oferta = $(".precioDisabled");

    $(".precioAble1").keyup(function () {
        if ($(this).val() != "") {
            oferta.prop("checked", true);
            $(".precioAble1").prop('required', true);
        } else {

            oferta.removeProp('checked');
            $(".precioAble1").removeProp('required');
        }
    });
});

$(document).ready(function () {
    $(".precio-number").keydown(function (e) {
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

    if ($(form).find("input[name='imagen_portada_crop']").length)
    {
        if ($(form).find("input[name='imagen_portada_crop']").val() == "")
        {
            ok = false;
            alert('Falta cargar o guardar la imagen recortada.');
        }
    }
    if ($(form).find("input[name='item_destacado']:checked").val() == 'O')
    {
        var precio_antes = parseInt($(form).find("input[name='precio_antes']").val());
        var precio_ahora = parseInt($(form).find("input[name='precio_actual']").val());
        //var precio_antes = $(form).find("input[name='precio_antes']").val();
        //var precio_ahora = $(form).find("input[name='precio_actual']").val();

        if (precio_antes <= precio_ahora)
        {
            ok = false;
            alert('El precio oferta es mayor que el precio anterior.');
        }
    }

    return ok;
}