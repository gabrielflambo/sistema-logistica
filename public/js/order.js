$(document).ready(function() {

    let base = $('base').attr('href');

    $('.delete').click(function(e) {
        e.preventDefault();
        let id = $(this).attr('href').split('/');
        swal({
                title: "Você tem certeza?",
                text: `Que deseje excluir esse registro?`,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim, eu tenho",
                cancelButtonText: "Não, cancele",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: `${base}${id[id.length - 3]}/${id[id.length - 2]}`,
                        data: {
                            id: id[id.length - 1],
                        },
                        type: 'DELETE',
                        success: function(response) {
                            swal("Deletado!", `O registro foi deletado com sucesso!`, "success");
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }
                    });
                } else {
                    swal("Cancelado", "O registro não será deletado :)", "error");
                }
            }
        )
    });
});