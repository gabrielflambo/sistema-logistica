$(document).ready(function() {

    let base = $('base').attr('href');

    // $('.create').click(function(e) {
    //     e.preventDefault();
    //     $('.modalCreate').addClass('active');
    // });

    // $('.modalCreate .close').click(function(e) {
    //     e.preventDefault();
    //     $('.modalCreate').removeClass('active');
    // });

    $('.add').click(function(e) {
        e.preventDefault();
        $('.modalGroup').addClass('active');
    });

    $('.modalGroup .close').click(function(e) {
        e.preventDefault();
        $('.modalGroup').removeClass('active');
    });

    $('.bond').click(function(e) {
        e.preventDefault();
        $('.modalBond').addClass('active');
    });

    $('.modalBond .close').click(function(e) {
        e.preventDefault();
        $('.modalBond').removeClass('active');
    });

    $('.new').click(function(e) {
        e.preventDefault();
        $('.modalRecord').addClass('active');
    });

    $('.modalRecord .close').click(function(e) {
        e.preventDefault();
        $('.modalRecord').removeClass('active');
    });

    $('.modalBond input[name="sku"]').bind('input', function(e) {

        let sku = $(this).val();

        $('.modalBond .complete').show();
        $('.modalBond .complete').html(`
            <figure>
                <img src="public/images/complete.gif" class="img" />
            </figure>
        `);

        if (sku == '') {
            $('.modalBond .complete').html(``);
            $('.modalBond .complete').hide();
        }

        $.ajax({
            type: "POST",
            datatype: 'json',
            url: `${base}group/complete`,
            data: {
                sku: sku
            },
            success: function(response) {
                if (response === '') {
                    $('.modalBond .complete').html('<p class="center">Produto não encontrado...</p>');

                } else {
                    response = JSON.parse(response);
                    response = response[0];

                    $('.modalBond .complete').html(`
                        <li>
                            <figure>
                                <img src="${response.caminhoImagem}" alt="">
                            </figure>
                            <p>${response.sku}</p>
                        </li>
                    `);

                    $('.modalBond .complete li').click(function(e) {
                        e.preventDefault();
                        let image = $(this).find('img').attr('src');
                        let sku = $(this).find('p').text();

                        $('.modalBond .btn').removeClass('disabled');
                        $('.modalBond .complete').html(``);
                        $('.modalBond .complete').hide();
                        $('.modalBond input[name="sku"]').val('');

                        $('.modalBond .group').append(`
                            <li>
                                <button type="button" class="remove">
                                    <span class="fa fa-times"></span>
                                </button>
                                <figure>
                                    <img src="${image}" alt="">
                                </figure>
                                <p>${sku}</p>
                            </li>
                        `);

                        $('.modalBond .group .remove').click(function(e) {
                            e.preventDefault();
                            $(this).parent().remove();
                        });
                    });
                }
            }
        });
    });

    $('.modalBond .btn').click(function(e) {
        e.preventDefault();
        let count = $('.modalBond .group li').length;

        if (count != 0) {
            $('.modalBond .group li').each(function(index, element) {
                let group = $('.modalBond .group').data('id');
                let image = $(this).find('img').attr('src');
                let sku = $(this).find('p').text();

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: `${base}group/bond`,
                    data: {
                        group: group,
                        image: image,
                        sku: sku
                    }
                });
            });

            swal({
                title: "Tudo Certo!",
                text: "Produtos vinculados com sucesso",
                type: "success",
                showCancelButton: false,
                confirmButtonColor: "#77dd77",
                confirmButtonText: "Tudo bem!",
                closeOnConfirm: true
            });

            setTimeout(() => {
                location.reload();
            }, 1000);
        }
    });

    $('.create .item .flex-item').click(function(e) {
        e.preventDefault();
        $('.modalProduct').addClass('active');

        let id = $(this).attr('href');
        id = id.split('/');
        id = id[id.length - 1];

        $.ajax({
            type: "POST",
            datatype: 'json',
            url: `${base}product/availability/product/`,
            data: {
                sku: id
            },
            success: function(response) {
                response = JSON.parse(response);
                if (response === '') {
                    $('.alert').append(`
                        <div id="card-alert" class="card orange">
                            <div class="card-content white-text">
                                <p><i class="mdi-action-info-outline"></i> Aviso: Ainda não existe dados cadastrados em nosso sistema sobre esse produto. Comece agora mesmo a salvar as informações desse produto!</p>
                            </div>
                            <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    `);

                    $('input[name="titulo"]').val($('.flex-item h4').text());
                    $('input[name="sku"]').val(id);
                    $('form').attr('action', 'product/persistDB');

                } else if (response[0] !== undefined) {
                    response = response[0];

                    $('input[name="titulo"]').val(response.titulo);
                    $('input[name="sku"]').val(response.sku);
                    $('input[name="peso"]').val(response.peso);
                    $('input[name="altura"]').val(response.altura);
                    $('input[name="largura"]').val(response.largura);
                    $('input[name="comprimento"]').val(response.comprimento);

                } else {

                    $('form').attr('action', 'product/persistDB');
                    $('.modalProduct form').append(`
                        <input type="hidden" name="id" value="${response.id}">
                        <input type="hidden" name="_method" value="PUT">
                    `);

                    $('.alert').append(`
                        <div id="card-alert" class="card orange">
                            <div class="card-content white-text">
                                <p><i class="mdi-action-info-outline"></i> Aviso: Esse produto ainda está em desenvolvimento em nosso, então algumas informações ainda podem estar faltando.</p>
                            </div>
                            <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    `);

                    $('input[name="titulo"]').val(response.titulo);
                    $('input[name="sku"]').val(response.sku);
                    $('input[name="peso"]').val(response.peso);
                    $('input[name="altura"]').val(response.altura);
                    $('input[name="largura"]').val(response.largura);
                    $('input[name="comprimento"]').val(response.comprimento);
                }
            }
        });
    });

    $('.modalProduct .close').click(function(e) {
        e.preventDefault();
        $('.modalProduct').removeClass('active');
        $('.alert').html('');
        $('input[name="titulo"]').val('');
        $('input[name="sku"]').val('');
        $('input[name="peso"]').val('');
        $('input[name="altura"]').val('');
        $('input[name="largura"]').val('');
        $('input[name="comprimento"]').val('');
    });

    $('input[name="transfer"]').change(function(e) {
        if ($('input[name="transfer"]:checked').length > 0) {
            $('.modalRecord select').show();
        } else {
            $('.modalRecord select').hide();
        }
    });

    $('.search-data .delete').click(function(e) {
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

    $('.mask').mask('#.##0,00', { reverse: true });

    $('button.stock').click(function(e) {
        e.preventDefault();
        $('.modalStock').addClass('active');
    });

    $('.modalStock .close').click(function(e) {
        e.preventDefault();
        $('.modalStock').removeClass('active');
    });

    $('.modalStock input[name="sku"]').bind('input', function(e) {

        let sku = $(this).val();

        $('.modalStock .complete').show();
        $('.modalStock .complete').html(`
            <figure>
                <img src="public/images/complete.gif" class="img" />
            </figure>
        `);

        if (sku == '') {
            $('.modalStock .complete').html(``);
            $('.modalStock .complete').hide();
        }

        $.ajax({
            type: "POST",
            datatype: 'json',
            url: `${base}group/complete`,
            data: {
                sku: sku
            },
            success: function(response) {
                if (response === '') {
                    $('.modalStock .complete').html('<p class="center">Produto não encontrado...</p>');

                } else {
                    response = JSON.parse(response);
                    response = response[0];

                    $('.modalStock .complete').html(`
                        <li>
                            <figure>
                                <img src="${response.caminhoImagem}" alt="">
                            </figure>
                            <p>${response.sku}</p>
                        </li>
                    `);

                    $('.modalStock .complete li').click(function(e) {
                        e.preventDefault();
                        let image = $(this).find('img').attr('src');
                        let sku = $(this).find('p').text();

                        $('.modalStock .btn').removeClass('disabled');
                        $('.modalStock .complete').html(``);
                        $('.modalStock .complete').hide();
                        $('.modalStock input[name="sku"]').val('');

                        $('.modalStock .group').append(`
                            <li>
                                <input type="hidden" name="product[]" value="${sku}">
                                <button type="button" class="remove">
                                    <span class="fa fa-times"></span>
                                </button>
                                <figure>
                                    <img src="${image}" alt="">
                                </figure>
                                <p>${sku}</p>
                            </li>
                        `);

                        $('.modalStock .group .remove').click(function(e) {
                            e.preventDefault();
                            $(this).parent().remove();
                        });
                    });
                }
            }
        });
    });

});