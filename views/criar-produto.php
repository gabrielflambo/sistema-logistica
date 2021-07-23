<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?= URL_BASE; ?>">
    <meta charset="utf-8">
	<meta http-equiv="content-language" content="pt-br">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta http-equiv="cache-control" content="public"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    
    <title>Controle de Produto</title>
    <?php require 'links.php'; ?>
    <!--dropify-->
    <link href="public/js/plugins/dropify/css/dropify.min.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="public/js/plugins/data-tables/css/jquery.dataTables.min.css" type="text/css" rel="stylesheet" media="screen,projection">

</head>

<body>

    <?php require 'header.php'; ?>

    <!-- START MAIN -->
    <div id="main">
        <!-- START WRAPPER -->
        <div class="wrapper">
            <?php require 'menu.php' ?>
            <!-- START CONTENT -->
            <section class="row" id="content">
                <!--start container-->
                <div class="product">
                <?php if(!isset($variations)): ?>
                    <h2>Controle de Produto</h2>
                    <form method="POST" action="product/persistDB">
                        <?php if(isset($product)): ?>
                            <input type="hidden" name="id" value="<?= $product->getId(); ?>">
                            <input type="hidden" name="_method" value="PUT">
                        <?php endif; ?>

                        <p><span class="fa fa-exclamation-circle"></span> Campos obrigatórios</p>

                        <label class="col s12">
                            Nome do produto
                            <span class="fa fa-exclamation-circle"></span>
                            <input type="text" name="titulo" value="<?= (isset($product)) ? $product->getTitulo() : ''; ?>" required>
                        </label>

                    <?php elseif(isset($variations)): ?>
                    <form method="POST" action="variations/persist">
                        <input type="hidden" name="id" value="<?= $variations->getId(); ?>">
                        <input type="hidden" name="product" value="<?= $variations->getProduct(); ?>">
                        <input type="hidden" name="_method" value="PUT">
                        <a class="back" href="product/edit/sketch/<?= $variations->getProduct(); ?>#variations">
                            <span class="fa fa-long-arrow-alt-left"></span>
                            Voltar para o produto
                        </a>
                        <h2>Editando a Variação</h2>
                        <p><span class="fa fa-exclamation-circle"></span> Campos obrigatórios</p>

                        <label class="col s12">
                            Código do produto (SKU)
                            <a class="tooltipped" data-position="top" data-delay="50" data-tooltip="O termo Stock Keeping Unit, em português Unidade de Manutenção de Estoque está ligado à logística de armazém e designa os diferentes itens do estoque, estando normalmente associado a um código identificador.">
                                <span class="fa fa-question-circle"></span>
                            </a>
                            <input type="text" name="skuVariacao" value="<?= (isset($variations)) ? $variations->getSkuVariacao() : ''; ?>" required>
                        </label>
                    <?php endif; ?>

                        <div class="col s12">
                            <?php if(!isset($variations)): ?>
                                <ul class="tabs tab-demo">
                                    <li class="tab">
                                        <a class="active" href="#data-general">Dados Gerais</a>
                                    </li>
                                    <li class="tab <?= (!isset($product)) ? 'hidden' : '' ?>">
                                        <a href="#images">Arquivos e imagens</a>
                                    </li>
                                    <li class="tab <?= (!isset($product)) ? 'hidden' : '' ?>">
                                        <a href="#variations">Variações</a>
                                    </li>
                                    <li class="tab <?= (!isset($product)) ? 'hidden' : '' ?>">
                                        <a href="#information">Informações adicionais</a>
                                    </li>
                                </ul>
                            <?php elseif(isset($variations)): ?>
                                <ul class="tabs tab-demo">
                                    <li class="tab">
                                        <a class="active" href="#data-general">Dados Opcionais</a>
                                    </li>
                                    <li class="tab">
                                        <a href="#images">Arquivos e imagens</a>
                                    </li>
                                </ul>
                            <?php endif; ?>
                        </div>

                        <?php if(!isset($variations)): ?>

                        <!-- Dados Gerais -->
                        <div id="data-general" class="col s12">   
                            
                                <h3>Dados de Venda</h3>

                                <!-- Dados de venda -->
                                <label for="price" class="col s12 m4">
                                    Preço de venda
                                    <span class="fa fa-exclamation-circle"></span>
                                    <div class="flex">
                                        <span>R$</span>
                                        <input type="text" name="valorVenda" value="<?= (isset($product)) ? $product->getValorVenda() : ''; ?>">
                                    </div>
                                </label>
                                <label for="promotion" class="col s12 m4">
                                    Preço de Custo
                                    <div class="flex">
                                        <span>R$</span>
                                        <input type="text" name="valorCusto" value="<?= (isset($product)) ? $product->getValorCusto() : ''; ?>">
                                    </div>
                                </label>
                                <label for="sku" class="col s12 m4">
                                    Código do produto (SKU)
                                    <a class="tooltipped" data-position="top" data-delay="50" data-tooltip="O termo Stock Keeping Unit, em português Unidade de Manutenção de Estoque está ligado à logística de armazém e designa os diferentes itens do estoque, estando normalmente associado a um código identificador.">
                                        <span class="fa fa-question-circle"></span>
                                    </a>
                                    <div class="flex">
                                        <span class="fa fa-barcode"></span>
                                        <input type="text" name="sku" value="<?= (isset($product)) ? $product->getSku() : ''; ?>">
                                    </div>
                                </label>

                                <!-- Dados para o transporte -->
                                <h3>Peso e dimensões</h3>
                                <div id="card-alert" class="card orange">
                                    <div class="card-content white-text">
                                        <p><i class="mdi-action-info-outline"></i> Lembrete: Os valores devem ser preenchidos exatos, para a produção correta do produto.</p>
                                    </div>
                                    <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <label for="weight" class="col s12 m6 l3">
                                    Peso
                                    <span class="fa fa-exclamation-circle"></span>
                                    <a class="tooltipped" data-position="top" data-delay="50" data-tooltip="Utilize ponto. Ex: 0.5">
                                        <span class="fa fa-question-circle"></span>
                                    </a>
                                    <div class="flex">
                                        <span>gramas</span>
                                        <input type="text" name="peso" value="<?= (isset($product)) ? $product->getPeso() : ''; ?>">
                                    </div>
                                </label>
                                <label for="height" class="col s12 m6 l3">
                                    Altura
                                    <span class="fa fa-exclamation-circle"></span>
                                    <a class="tooltipped" data-position="top" data-delay="50" data-tooltip="Utilize ponto. Ex: 0.5">
                                        <span class="fa fa-question-circle"></span>
                                    </a>
                                    <div class="flex">
                                        <span>cm</span>
                                        <input type="text" name="altura" value="<?= (isset($product)) ? $product->getAltura() : ''; ?>">
                                    </div>
                                </label>
                                <label for="width" class="col s12 m6 l3">
                                    Largura
                                    <span class="fa fa-exclamation-circle"></span>
                                    <a class="tooltipped" data-position="top" data-delay="50" data-tooltip="Utilize ponto. Ex: 0.5">
                                        <span class="fa fa-question-circle"></span>
                                    </a>
                                    <div class="flex">
                                        <span>cm</span>
                                        <input type="text" name="largura" value="<?= (isset($product)) ? $product->getLargura() : ''; ?>">
                                    </div>
                                </label>
                                <label for="deep" class="col s12 m6 l3">
                                    Comprimento
                                    <span class="fa fa-exclamation-circle"></span>
                                    <a class="tooltipped" data-position="top" data-delay="50" data-tooltip="Utilize ponto. Ex: 0.5">
                                        <span class="fa fa-question-circle"></span>
                                    </a>
                                    <div class="flex">
                                        <span>cm</span>
                                        <input type="text" name="comprimento" value="<?= (isset($product)) ? $product->getComprimento() : ''; ?>">
                                    </div>
                                </label>

                                <!-- Dados para o transporte -->
                                <h3>Peso e dimensões (Com embalagem)</h3>
                                <div id="card-alert" class="card orange">
                                    <div class="card-content white-text">
                                        <p><i class="mdi-action-info-outline"></i> Lembrete: Os valores devem ser preenchidos considerando o pacote que será enviado, ou seja, embalagem com produto.</p>
                                    </div>
                                    <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <label for="weightPacking" class="col s12 m6 l3">
                                    Peso
                                    <span class="fa fa-exclamation-circle"></span>
                                    <a class="tooltipped" data-position="top" data-delay="50" data-tooltip="Utilize ponto. Ex: 0.5">
                                        <span class="fa fa-question-circle"></span>
                                    </a>
                                    <div class="flex">
                                        <span>Kg</span>
                                        <input type="text" name="pesoEmbalagem" value="<?= (isset($product)) ? $product->getPesoEmbalagem() : ''; ?>">
                                    </div>
                                </label>
                                <label for="heightPacking" class="col s12 m6 l3">
                                    Altura
                                    <span class="fa fa-exclamation-circle"></span>
                                    <a class="tooltipped" data-position="top" data-delay="50" data-tooltip="Utilize ponto. Ex: 0.5">
                                        <span class="fa fa-question-circle"></span>
                                    </a>
                                    <div class="flex">
                                        <span>mts</span>
                                        <input type="text" name="alturaEmbalagem" value="<?= (isset($product)) ? $product->getAlturaEmbalagem() : ''; ?>">
                                    </div>
                                </label>
                                <label for="widthPacking" class="col s12 m6 l3">
                                    Largura
                                    <span class="fa fa-exclamation-circle"></span>
                                    <a class="tooltipped" data-position="top" data-delay="50" data-tooltip="Utilize ponto. Ex: 0.5">
                                        <span class="fa fa-question-circle"></span>
                                    </a>
                                    <div class="flex">
                                        <span>mts</span>
                                        <input type="text" name="larguraEmbalagem" value="<?= (isset($product)) ? $product->getLarguraEmbalagem() : ''; ?>">
                                    </div>
                                </label>
                                <label for="deepPacking" class="col s12 m6 l3">
                                    Comprimento
                                    <span class="fa fa-exclamation-circle"></span>
                                    <a class="tooltipped" data-position="top" data-delay="50" data-tooltip="Utilize ponto. Ex: 0.5">
                                        <span class="fa fa-question-circle"></span>
                                    </a>
                                    <div class="flex">
                                        <span>mts</span>
                                        <input type="text" name="comprimentoEmbalagem" value="<?= (isset($product)) ? $product->getComprimentoEmbalagem() : ''; ?>">
                                    </div>
                                </label>

                                <!-- Dados para mensuração -->
                                <h3>Filtragem do produto</h3>
                                <div id="card-alert" class="card orange">
                                    <div class="card-content white-text">
                                        <p><i class="mdi-action-info-outline"></i> Lembrete: É importante colocar o devido departamento no produto, pois assim que chegar um pedido, o mesmo será direcionado para aquele setor.</p>
                                    </div>
                                    <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <label class="col l3 m6 s12">
                                    Categoria
                                    <select name="categoriaIdIderis" class="browser-default">
                                        <option value="" selected disabled>Selecione uma opção</option>
                                        <?php foreach ($category->result as $item): ?>
                                            <option <?= (isset($product) && $product->getCategoriaIdIderis() == $item->id) ? 'selected' : ''; ?> value="<?= $item->id ?>"><?= $item->descricao ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                                <label class="col l3 m6 s12">
                                    Sub categoria
                                    <select name="subCategoriaIdIderis" class="browser-default">
                                        <option value="" selected disabled>Selecione uma opção</option>
                                        <?php foreach ($subcategory->result as $item): ?>
                                            <option <?= (isset($product) && $product->getSubCategoriaIdIderis() == $item->id) ? 'selected' : ''; ?> value="<?= $item->id ?>"><?= $item->descricao ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                                <label class="col l3 m6 s12">
                                    Marca
                                    <select name="marcaIdIderis" class="browser-default">
                                        <option value="" selected disabled>Selecione uma opção</option>
                                        <?php foreach ($brand->result as $item): ?>
                                            <option <?= (isset($product) && $product->getMarcaIdIderis() == $item->id) ? 'selected' : ''; ?> value="<?= $item->id ?>"><?= $item->descricao ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                                <label class="col l3 m6 s12">
                                    Departamento
                                    <select name="departamentoIdIderis" class="browser-default">
                                        <option value="" selected disabled>Selecione uma opção</option>
                                        <?php foreach ($department->result as $item): ?>
                                            <option <?= (isset($product) && $product->getDepartamentoIdIderis() == $item->id) ? 'selected' : ''; ?> value="<?= $item->id ?>"><?= $item->descricao ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>

                                <div class="col s12 center">
                                    <button type="submit" class="btn waves-effect">Salvar</button>
                                </div>
                                </div>
                                </form>

                                <?php if(isset($product)): ?>

                                <!-- Inserção de Imagens -->
                                <div id="images" class="col s12">
                                        <form method="POST" action="image/persist" enctype="multipart/form-data">
                                            <div class="input">
                                                <p>
                                                    Coloque suas imagens abaixo para fazer o upload
                                                    <a class="tooltipped" data-position="top" data-delay="50" data-tooltip="Imagens são super importantes para demonstração de relevância dos seus produtos. Faça o upload de suas imagens, clicando ou arrastando seus arquivos até o campo abaixo">
                                                        <span class="fa fa-question-circle"></span>
                                                    </a>
                                                </p>
                                                <input type="file" name="files[]" class="dropify" multiple="multiple" />
                                                <input type="hidden" name="type" value="0" />
                                                <input type="hidden" name="product" value="<?= $product->getTitulo(); ?>" />
                                                <input type="hidden" name="product_id" value="<?= $product->getId(); ?>" />
                                            </div>
                                            <div class="start">
                                                <?php if(!empty($image)): ?>
                                                    <input type="hidden" name="_method" value="PUT">
                                                <?php 
                                                    foreach ($image as $item):
                                                ?>
                                                    <div class="col s12 m6 l3">
                                                        <input type="hidden" name="id[]" value="<?= $item->getId(); ?>">
                                                        <input type="file" id="<?= $item->getId(); ?>" name="image[]" class="dropify" data-default-file="<?= $item->getUrlImagem(); ?>" />
                                                        <input type="hidden" name="alt[]">
                                                    </div>
                                                <?php 
                                                endforeach;
                                                ?>
                                                    <div class="col s12 center">
                                                        <button type="submit" class="btn waves-effect">Salvar</button>
                                                    </div>
                                                <?php endif; ?>
                                            </div>                       
                                        </form>
                                    </div>

                                <!-- Controle de VAriações -->
                                <div id="variations" class="col s12">

                                    <!-- Modal de Inclusão de Variação -->
                                    <aside class="modalSize">
                                        <fieldset>
                                            <button type="button" class="close">
                                                <span class="fa fa-times"></span>
                                            </button>
                                            <h4>Nova Variação</h4>
                                            <form method="POST" action="variations/persist">
                                                <input type="hidden" name="product" value="<?= $product->getId(); ?>">
                                                <label for="size">
                                                    SKU
                                                    <input type="text" name="skuVariacao">
                                                </label>

                                                <!-- Dados Opcionais -->
                                                <h3>Dados Opcionais</h3>
                                                <label for="quantidadeVariacao" class="col s12 m4">
                                                    Quantidade
                                                    <input type="text" name="quantidadeVariacao" required>
                                                </label>
                                                <label for="quantidadeVariacao" class="col s12 m4">
                                                    Nome do Atributo
                                                    <input type="text" name="nomeAtributo" required>
                                                </label>
                                                <label for="valorAtributo" class="col s12 m4">
                                                    Valor do Atributo
                                                    <input type="text" name="valorAtributo" required>
                                                </label>
                                                <div class="col s12 center">
                                                    <button type="submit" class="btn waves-effect">Salvar</button>
                                                </div>
                                            </form>
                                        </fieldset>
                                    </aside>

                                    <h3>
                                        Variações
                                        <!-- Abrir modal de inclusão -->
                                        <button type="button" class="btn waves-effect size">
                                            <span class="fa fa-plus"></span>
                                            Incluir variação
                                        </button>
                                    </h3>

                                    <?php if(empty($variation)): ?>
                                        <div class="empty">
                                            <span class="fa fa-folder-open"></span>
                                            <h5>Sem variações cadastradas para esse produto</h5>
                                            <p>Você pode começar agora mesmo incluindo uma nova variação.</p>
                                        </div>
                                    <?php else: ?>
                                        <table class="data-table-simple responsive-table display" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th class="center">ID</th>
                                                    <th class="center">Imagem</th>
                                                    <th class="center">SKU</th>
                                                    <th class="center">Estoque</th>
                                                    <th></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach ($variation as $item): ?>
                                                    <tr>
                                                        <td class="center"><?= $item->getId(); ?></td>
                                                        <td class="center">
                                                            <?php 
                                                                $img = array_filter($imageV, function ($elem) use ($item){
                                                                    return $elem->getProduct() == $item->getId();
                                                                });
                                                                if (!empty($img)):
                                                                    $img = current($img);
                                                            ?>
                                                                <img src="<?= $img->getUrlImagem(); ?>" alt="">
                                                            <?php else: ?>
                                                                <img src="public/images/produto-sem-imagem.gif" alt="">
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="center"><?= $item->getSkuVariacao(); ?></td>
                                                        <td class="center"><?= $item->getQuantidadeVariacao(); ?></td>
                                                        <td class="center">
                                                            <a href="variations/edit/<?= $item->getId(); ?>">
                                                                <span class="far fa-edit"></span>
                                                            </a>
                                                            <a class="delete" href="variations/delete/<?= $item->getId() ?>/<?= $product->getId() ?>">
                                                                <span class="far fa-trash-alt"></span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php endif; ?>
                                </div>

                                <!-- Informações Adicionais -->
                                <div id="information" class="col s12">
                                    <form method="POST" action="info/persist">
                                        <?php if(isset($info)): ?>
                                            <input type="hidden" name="id" value="<?= $info->getId(); ?>">
                                            <input type="hidden" name="_method" value="PUT">
                                        <?php endif; ?>
                                        <input type="hidden" name="product" value="<?= $product->getId(); ?>">
                                        <div class="col s12">
                                            <label for="descricaoLonga">
                                                Descrição completa
                                                <span class="fa fa-exclamation-circle"></span>
                                            </label>
                                            <textarea name="descricaoLonga" class="editor" required>
                                                <?= (isset($info)) ? $info->getDescricaoLonga() : '<p>Insira uma observação no seu produto<p/>'; ?>
                                            </textarea>
                                        </div>
                                        <div class="col s12 center">
                                            <button type="submit" class="btn waves-effect">Salvar</button>
                                        </div>
                                    </form>
                                </div>
                            <?php endif; ?>

                    <?php elseif(isset($variations)): ?>

                        <!-- Dados Gerais -->
                        <div id="data-general" class="col s12">   

                            <!-- Dados Opcionais -->
                            <h3>Dados Opcionais</h3>
                            <label for="quantidadeVariacao" class="col s12 m4">
                                Quantidade
                                <input type="text" name="quantidadeVariacao" value="<?= $variations->getQuantidadeVariacao(); ?>" required>
                            </label>
                            <label for="quantidadeVariacao" class="col s12 m4">
                                Nome do Atributo
                                <input type="text" name="nomeAtributo" value="<?= $variations->getNomeAtributo(); ?>" required>
                            </label>
                            <label for="valorAtributo" class="col s12 m4">
                                Valor do Atributo
                                <input type="text" name="valorAtributo" value="<?= $variations->getValorAtributo(); ?>" required>
                            </label>

                            <div class="col s12 center">
                                <button type="submit" class="btn waves-effect">Salvar</button>
                            </div>
                        </div>
                        </form>
                         
                            <!-- Inserção de Imagens -->
                            <div id="images" class="col s12">
                                <form method="POST" action="image/persist" enctype="multipart/form-data">
                                    <div class="input">
                                        <p>
                                            Coloque suas imagens abaixo para fazer o upload
                                            <a class="tooltipped" data-position="top" data-delay="50" data-tooltip="Imagens são super importantes para demonstração de relevância dos seus produtos. Faça o upload de suas imagens, clicando ou arrastando seus arquivos até o campo abaixo">
                                                <span class="fa fa-question-circle"></span>
                                            </a>
                                        </p>
                                        <input type="file" name="files[]" class="dropify" multiple="multiple" />
                                        <input type="hidden" name="type" value="1" />
                                        <input type="hidden" name="product" value="<?= $variations->getSkuVariacao(); ?>" />
                                        <input type="hidden" name="product_id" value="<?= $variations->getId(); ?>" />
                                    </div>
                                    <div class="start">
                                        <?php if(!empty($image)): ?>
                                            <input type="hidden" name="_method" value="PUT">
                                        <?php 
                                            foreach ($image as $item):
                                        ?>
                                            <div class="col s12 m6 l3">
                                                <input type="hidden" name="id[]" value="<?= $item->getId(); ?>">
                                                <input type="file" id="<?= $item->getId(); ?>" name="image[]" class="dropify" data-default-file="<?= $item->getUrlImagem(); ?>" />
                                                <input type="hidden" name="alt[]">
                                            </div>
                                        <?php 
                                        endforeach;
                                        ?>
                                            <div class="col s12 center">
                                                <button type="submit" class="btn waves-effect">Salvar</button>
                                            </div>
                                        <?php endif; ?>
                                    </div>                       
                                </form>
                            </div>
                                
                    <?php endif; ?>
                </div>
                <!--end container-->
            </section>
            <!-- END CONTENT -->
        </div>
        <!-- END WRAPPER -->
    </div>
    <!-- END MAIN -->

    <?php require 'footer.php'; ?>
    <?php require 'scripts.php'; ?>
    <!-- dropify -->
    <script type="text/javascript" src="public/js/plugins/dropify/js/dropify.min.js"></script>
    <!-- data-tables -->
    <script type="text/javascript" src="public/js/plugins/data-tables/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="public/js/plugins/data-tables/data-tables-script.js"></script>
    <script type="text/javascript" src="public/ckeditor/build/ckeditor.js"></script>
    <script type="text/javascript" src="public/js/jquery.mask.min.js"></script>
    <script type="text/javascript" src="public/js/product.js"></script>
    <?php
    if(isset($_SESSION['mensagem'])): ?>
    <script>
        swal({
                title: "<?= ($_SESSION['tipo'] == 'success') ? 'Tudo Certo!' : 'Ooops tem um erro!'; ?>",
                text: "<?= $_SESSION['mensagem']; ?>",   
                type: "<?= $_SESSION['tipo']; ?>",   
                showCancelButton: false,   
                confirmButtonColor: "#77dd77",   
                confirmButtonText: "Tudo bem!",   
                closeOnConfirm: true
              });
    </script>
    <?php
        unset($_SESSION['tipo']);
        unset($_SESSION['mensagem']);
    endif;
    ?>
    
</body>

</html>