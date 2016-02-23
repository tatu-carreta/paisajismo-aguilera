/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function borrarData(url, id) {
    if (confirm("¿Está seguro que desea continuar con la eliminación? Advertencia: no podrá volver las acciones atrás."))
        $.post($.trim(url), {id: id}, function (data) {
            alert(data.mensaje);
            if (!data.error)
            {
                window.location.reload();
            }
        }, "json");
}

function borrarItemSeccion(url, seccion_id, item_id) {
    if (confirm("¿Está seguro que desea eliminar este producto?"))
    {
        $.post($.trim(url), {item_id: item_id, seccion_id: seccion_id}, function (data) {
            alert(data.mensaje);
            if (!data.error)
            {
                window.location.reload();
            }
        }, "json");
    }
}

function borrarImagen(url, id, item_id, load) {
    $.post($.trim(url), {id: id}, function (data) {
        alert(data.mensaje);
        if (!data.error)
        {
            $(".modal").load(load + item_id);
        }
    }, "json");
}

function borrarImagenReload(url, id) {
    if (confirm("¿Está seguro que desea eliminar la imagen seleccionada?"))
    {
        $.post($.trim(url), {id: id}, function (data) {
            alert(data.mensaje);
            if (!data.error)
            {
                window.location.reload();
            }
        }, "json");
    }
}

function borrarVideoReload(url, id) {
    if (confirm("¿Está seguro que desea eliminar el video seleccionado?"))
    {
        $.post($.trim(url), {id: id}, function (data) {
            alert(data.mensaje);
            if (!data.error)
            {
                window.location.reload();
            }
        }, "json");
    }
}

function destacarItemSeccion(url, seccion_id, item_id) {
    $.post($.trim(url), {item_id: item_id, seccion_id: seccion_id}, function (data) {
        //alert(data.mensaje);
        if (!data.error)
        {
            window.location.reload();
        }
    }, "json");
}

function pasarSeccionesCategoria(url, menu_id) {
    if (confirm("Usted está a punto de convertir todas las secciones de esta categoría en subcategorías. ¿Está seguro que desea continuar?"))
    {
        if (confirm("¿Recontra seguro?"))
        {
            console.log($.post($.trim(url), {menu_id: menu_id}, function (data) {
                alert(data.mensaje);
                if (!data.error)
                {
                    //window.location.reload();
                    window.location.replace(data.url);
                }
            }, "json"));
        }
    }
}

$(function () {
    console.log($(".editarContenidoAdmin").click(function () {
        var url = $(this).attr("data");
        $(".cargaContenidoAdmin").load($.trim(url));
    }));
});

$(function () {
    console.log($(".volver").click(function () {
        window.history.back();
    }));
});

function cancelarPopup(modal, seccion_id) {

    if (typeof seccion_id != 'undefined') {
        console.log($('#' + modal + seccion_id).dialog("close"));
    }
    else
    {
        console.log($('#' + modal).dialog("close"));
    }

}

$(function () {
    $('.cant_prod_carrito').change(function () {
        var cantidad = $(this).val();
        var id = $(this).attr('data');
        var producto_id = $(this).attr('id');

        $.post('carrito/editar/' + producto_id + '/' + id, {cantidad: cantidad}, function (data) {
            window.location.reload();
        }, "json");
    });
});

/*
 * VALIDAR CON JQUERY EL TAMAÑO DEL ARCHIVO
 */


function validar(elem) {
    var tipos = ['.jpg', '.png', '.gif'];
    var archivo = $(elem);
    var nombre = archivo.val();
    var extension = nombre.substring(nombre.lastIndexOf(".")).toLowerCase();

    if (jQuery.inArray(extension, tipos)) {
        $("#error").html("<img src='images/cross.png'> El tipo del archivo es incorrecto...");
        $("#correcto").html("");
        $("#" + archivo.attr('id')).css("color", "red");
        archivo.val("");
        $(".jcrop-holder").remove();
        //LimpiarInputFile(archivo.attr('id'));
    }
    else {
        //Es mayor a 2MB
        if (archivo['0'].files['0'].size > (1024 * 1024) + (1024 * 1024))
        {
            alert("El archivo que intenta subir supera el tamaño indicado. Vuelva a intentar.");
            archivo.val("");
            $(".jcrop-holder").remove();
        }
        $("#error").html("");
        $("#" + archivo.attr('id')).css("color", "black");
        //ajaxFileUpload(archivo.attr('id'), archivo.attr('name'), tamanio_val);
    }
}
/*
 function LimpiarInputFile(id) {
 id1 = "#" + id;
 var aux = $(id1).clone();
 aux.css('display', 'none');
 aux.val("");
 $(id1).after(aux);
 $(id1).remove();
 aux.attr('id', id)
 aux.show();
 }
 */
/*
 function submit_form(form_name)
 {
 var form = $("#" + form_name);
 var post_url = form.attr("action");
 var post_data = form.serialize();
 
 console.log($.ajax({
 type: 'POST',
 url: post_url,
 data: post_data,
 dataType: "json",
 success: function (registro) {
 if (registro.error)
 {
 alert(registro.mensaje);
 }
 else
 {
 window.location.reload();
 }
 }
 }));
 return false;
 }
 */

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
$(function () {
    $('#ancla').click(function () {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var $target = $(this.hash);
            $target = $target.length && $target || $('[name=' + this.hash.slice(1) + ']');
            if ($target.length) {
                var targetOffset = $target.offset().top - 130;
                $('html,body').animate({scrollTop: targetOffset}, 1000);
                return false;
            }
        }
    });
});
$(document).ready(function () {
    $("#ancla").click();
});

$(document).ready(function () {
    setTimeout(function () {
        $(".divEmergente").fadeOut(1500);
    }, 10000);
});
$(function () {
    $(".cerrarEmergente").click(function () {
        $(".divEmergente").fadeOut(1500);
    });
});
$(document).ready(function () {
    setTimeout(function () {
        $(".divAlerta").fadeOut(1500);
    }, 10000);
});
$(function () {
    $(".cerrarDivAlerta").click(function () {
        $(".divAlerta").fadeOut(1500);
    });
});