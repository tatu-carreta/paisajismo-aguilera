$(document).ready(function () {
    //Config Especial para Editar el Texto
    var config = {toolbar: [
            {name: 'clipboard', groups: ['clipboard', 'undo'], items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']},
            /*{name: 'editing', groups: ['find', 'selection', 'spellchecker'], items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt']},*/
            {name: 'basicstyles', groups: ['basicstyles', 'cleanup'], items: ['Bold', 'Italic', 'Underline', 'Subscript', 'Superscript', '-', 'RemoveFormat']},
            '/',
            {name: 'styles', items: ['Format']},
            {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote']},
            {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
        ]};
    $('textarea#texto').ckeditor(function () {
    }, config);

});
