$(document).ready(function() {

    let base = $('base').attr('href');

    $('.mask').mask('#.##0,00', { reverse: true });

    $('button.stock').click(function(e) {
        e.preventDefault();
        $('.modalStock').addClass('active');
    });

    $('.modalStock .close').click(function(e) {
        e.preventDefault();
        $('.modalStock').removeClass('active');
    });

    $('button.color').click(function(e) {
        e.preventDefault();
        $('.modalColor').addClass('active');
    });

    $('.modalColor .close').click(function(e) {
        e.preventDefault();
        $('.modalColor').removeClass('active');
    });

    $('button.size').click(function(e) {
        e.preventDefault();
        $('.modalSize').addClass('active');
    });

    $('.modalSize .close').click(function(e) {
        e.preventDefault();
        $('.modalSize').removeClass('active');
    });

    // Seleciona todos os produtos
    $('input[name="all"]').change(function(e) {
        if (this.checked == true) {
            $('.publish').show();
            $('input[name="product[]"]').each(function(index, element) {
                $(this).prop("checked", true);
            });
        } else {
            $('.publish').hide();
            $('input[name="product[]"]').each(function(index, element) {
                $(this).prop("checked", false);
            });
        }
    });

    // Seleciona cada produto por vez
    $('input[name="product[]"]').change(function(e) {
        if ($('input[name="product[]"]:checked').length > 0) {
            $('.publish').show();
        } else {
            $('.publish').hide();
        }
    });

    // Clique no publicar
    $('.publish').click(function(e) {
        e.preventDefault();
        swal({
                title: "Você tem certeza?",
                text: `Você estará publicando ${$('input[name="product[]"]:checked').length} produto`,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim, eu tenho",
                cancelButtonText: "Não, cancele",
                closeOnConfirm: true,
                closeOnCancel: false
            },
            function(isConfirm) {
                if (isConfirm) {
                    $('.progres').addClass('active');
                    let ids = [];
                    let idC = [];
                    let count = 0;
                    let width = 100 / parseInt($('input[name="product[]"]:checked').length);
                    let widthAtual = 0;
                    $('input[name="product[]"]:checked').each(function(index, element) {
                        ids.push(this.value);
                    });

                    stepTime();

                    function stepTime() {
                        var time = setTimeout(() => {
                            if (!idC.includes(ids[count])) {
                                $.post(`${base}product/publish`, { id: ids[count] }).done(function(data) {
                                    if (JSON.parse(data).result === undefined) {
                                        swal({
                                                title: "Ooops esta faltando algo!!!",
                                                text: `No produto de ID: ${ids[count]}, está com esses problemas: ${data}`,
                                                type: "warning",
                                                showCancelButton: false,
                                                confirmButtonColor: "#DD6B55",
                                                confirmButtonText: "Continuar",
                                                closeOnConfirm: true,
                                                closeOnCancel: true
                                            },
                                            function(isConfirm) {
                                                if (isConfirm) {
                                                    widthAtual = parseFloat(widthAtual) + parseFloat(width);
                                                    $('.complete div').css('width', `${widthAtual}%`);
                                                    $('.progres small span').html((widthAtual).toFixed(0));
                                                    idC.push(ids[count]);
                                                    count++;
                                                    if (count >= $('input[name="product[]"]:checked').length) {
                                                        count = 0;
                                                        setTimeout(() => {
                                                            $('.progres').removeClass('active');
                                                            location.reload();
                                                        }, 2000);
                                                    } else {
                                                        setTimeout(() => {
                                                            stepTime()
                                                        }, 2000);
                                                    }
                                                }
                                            }
                                        );
                                    } else {
                                        widthAtual = parseFloat(widthAtual) + parseFloat(width);
                                        $('.complete div').css('width', `${widthAtual}%`);
                                        $('.progres small span').html((widthAtual).toFixed(0));
                                        idC.push(ids[count]);
                                        count++;
                                        if (count >= $('input[name="product[]"]:checked').length) {
                                            count = 0;
                                            setTimeout(() => {
                                                $('.progres').removeClass('active');
                                                location.reload();
                                            }, 2000);
                                        } else {
                                            setTimeout(() => {
                                                stepTime()
                                            }, 2000);
                                        }
                                    }
                                });
                            }
                        }, 2000);
                    }
                } else {
                    swal("Cancelado", "Nenhum produto foi publicado :)", "error");
                }
            }
        )
    });

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
                        url: `${base}${id[id.length - 4]}/${id[id.length - 3]}`,
                        data: {
                            id: id[id.length - 2],
                            product: id[id.length - 1],
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

    $('.saved .delete').click(function(e) {
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

    // Used events
    var drEvent = $('.dropify').dropify({
        messages: {
            'default': 'Arraste e solte um arquivo aqui ou clique',
            'replace': 'Arraste e solte ou clique para substituir',
            'remove': 'Remover',
            'error': 'Ooops, algo errado aconteceu.'
        }
    });

    drEvent.on('dropify.beforeClear', function(event, element) {
        let elem = this;
        swal({
                title: "Você tem certeza?",
                text: `Que deseje excluir essa imagem: ${element.filename}`,
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
                        url: 'image/delete',
                        data: {
                            id: elem.id,
                            image: element.filename
                        },
                        type: 'DELETE',
                        success: function(response) {
                            $(elem).parent().parent().remove();
                            swal("Deletado!", `A imagem ${element.filename} foi deletada com sucesso!`, "success");
                            countImages('#images');
                        }
                    });
                } else {
                    swal("Cancelado", "A imagem não será deletado :)", "error");
                }
            }
        )
        return false;
    });

    function countImages(id) {
        let count = 0;
        $(`${id} .start .m6`).each(function(index, element) {
            count++;
        });
        if (count == 0) {
            location.reload();
        }
    }


    $('.input input[name="files[]"]').change(function(e) {
        e.preventDefault();
        $('.start').hide('fast');
        $('.input').hide('fast');
        let elem = $(this).parent().parent().parent();
        for (let i = 0; i < this.files.length; i++) {
            var file = new FileReader();
            file.onload = function(e) {
                $(elem).append(`
                    <div class="col s12 m6 l3">
                        <img src="${e.target.result}" />
                        <button type="button" class="remove">
                            Remover
                        </button>
                        <input type="hidden" name="alt[]">
                    </div>
                `);
            };
            file.readAsDataURL(this.files[i]);
        }

        setTimeout(() => {
            $(elem).append(`
                <div class="clearfix"></div>
                <div class="col s12 center">
                    <button type="submit" class="btn waves-effect">Salvar</button>
                </div>
            `);
            $('.start').remove();
            $('.remove').click(function(e) {
                e.preventDefault();
                $(this).parent().remove();
            });
            $('.tooltipped').tooltip();
        }, this.files.length * 200);
    });

    $(window).on('dragenter', function() {
        $(this).preventDefault();
    });
    $('#images').bind('dragover', function(event) {
        event.stopPropagation();
        $('#images .input').addClass('drag-over');
    });
    $('#images').bind('dragleave', function(event) {
        event.stopPropagation();
        $('#images .input').removeClass('drag-over');
    });

    ClassicEditor
        .create(document.querySelector('.editor'), {
            toolbar: {
                items: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'link',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'imageUpload',
                    'mediaEmbed',
                    'undo',
                    'redo',
                    '|'
                ]
            },
            language: 'pt-br',
            licenseKey: '',
        })
        .then(editor => {
            window.editor = editor;
        })
        .catch(error => {
            console.error('Oops, something went wrong!');
            console.error('Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:');
            console.warn('Build id: nofltlx4alsd-av3vandobeov');
            console.error(error);
        });

});