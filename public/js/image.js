$(document).ready(function() {
    // Used events
    var drEvent = $('.dropify').dropify({
        messages: {
            'default': 'Arraste e solte um arquivo aqui ou clique',
            'replace': 'Arraste e solte ou clique para substituir',
            'remove': 'Remover',
            'error': 'Ooops, algo errado aconteceu.'
        }
    });

    $('form').submit(function(e) {
        if ($('input[name="password"]').val() != $('input[name="confirmPassword"]').val()) {
            e.preventDefault();
            swal("Ooops tem um erro", "A senha não são compativeis, por favor tente novamente", "error");
        }
    });
});