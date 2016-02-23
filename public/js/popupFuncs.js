$(function () {
    $('.nuevaSeccion').click(function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            type: 'GET',
            url: url,
            success: function (msg) {
                $("#agregar-seccion").show();
                $("#agregar-seccion").html(msg);
                $("#agregar-seccion").dialog({
                    closeText: "Cerrar",
                    modal: true,
                    //                        close: function() {
                    //                            window.location.reload();
                    //                        }
                });
            }
        });
        return false;
    });
});

$(function () {
    $('.popup-seccion').click(function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var data = $(this).attr('data');
        $('#seccion' + data).removeData('bs.modal');
        $('#seccion' + data).modal({remote: url});
        $('#seccion' + data).modal('show');
//        $.ajax({
//            type: 'GET',
//            url: url,
//            success: function (msg) {
//                $("#agregar-seccion").show();
//                $("#agregar-seccion").html(msg);
//                $("#agregar-seccion").dialog({
//                    closeText: "Cerrar",
//                    modal: true,
//                    //                        close: function() {
//                    //                            window.location.reload();
//                    //                        }
//                });
//            }
//        });
        return false;
    });
});

$(function () {
    $('.popup-nueva-seccion').click(function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('#nueva-seccion').removeData('bs.modal');
        $('#nueva-seccion').modal({remote: url});
        $('#nueva-seccion').modal('show');

        return false;
    });
});

$(function () {
    $('#nueva-seccion').on('hidden.bs.modal', function (e) {
        e.preventDefault();
        $(this).find('.modal-content').html('');
        
        return false;
    });
});

$(function () {
    $('.popup').click(function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var id = $(this).attr('data');
        $.ajax({
            type: 'GET',
            url: url,
            success: function (msg) {
                $("#agregar-item" + id).show();
                $("#agregar-item" + id).html(msg);
                $("#agregar-item" + id).dialog({
                    closeText: "Cerrar",
                    modal: true,
                    //                        close: function() {
                    //                            window.location.reload();
                    //                        }
                });
            }
        });
        return false;
    });
});

$(function () {
    $('.popupOrdenMenu').click(function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            type: 'GET',
            url: url,
            success: function (msg) {
                $("#orden").show();
                $("#orden").html(msg);
                $("#orden").dialog({
                    closeText: "Cerrar",
                    modal: true,
//                        close: function() {
//                            window.location.reload();
//                        }
                });
            }
        });
        return false;
    });
});

$(function () {
    $('.destacarProducto').click(function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            type: 'GET',
            url: url,
            success: function (msg) {
                $("#destacar-producto").show();
                $("#destacar-producto").html(msg);
                $("#destacar-producto").dialog({
                    closeText: "Cerrar",
                    modal: true,
                    //                        close: function() {
                    //                            window.location.reload();
                    //                        }
                });
            }
        });
        return false;
    });
});