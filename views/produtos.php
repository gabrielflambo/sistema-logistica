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
                <?php if(!empty($product)): ?>
                <!--start container-->
                <div class="product">
                <?php if(!isset($variations)): ?>
                    <h2>Controle de Produto</h2>
                    <form method="POST" action="product/persist">
                        <?php if(isset($product)): ?>
                            <input type="hidden" name="id" value="<?= $product->id; ?>">
                            <input type="hidden" name="_method" value="PUT">
                        <?php endif; ?>

                        <p><span class="fa fa-exclamation-circle"></span> Campos obrigatórios</p>

                        <label class="col s12">
                            Nome do produto
                            <span class="fa fa-exclamation-circle"></span>
                            <input type="text" name="titulo" value="<?= (isset($product)) ? $product->titulo : ''; ?>" required>
                        </label>

                    <?php elseif(isset($variations)): ?>
                        <a class="back" href="product/edit/<?= $id; ?>#variations">
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
                            <input disabled type="text" name="skuVariacao" value="<?= (isset($variations)) ? $variations->skuVariacao : ''; ?>" required>
                        </label>
                    <?php endif; ?>

                        <div class="col s12">
                            <?php if(!isset($variations)): ?>
                                <ul class="tabs tab-demo">
                                    <li class="tab">
                                        <a class="active" href="#data-general">Dados Gerais</a>
                                    </li>
                                    <li class="tab <?= (!isset($product)) ? 'hidden' : '' ?>">
                                        <a href="#stock">Estoque</a>
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
                                        <a class="active" href="#stock">Estoque</a>
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
                                        <input type="text" name="valorVenda" value="<?= (isset($product)) ? $product->valorVenda : ''; ?>" required>
                                    </div>
                                </label>
                                <label for="promotion" class="col s12 m4">
                                    Preço de Custo
                                    <div class="flex">
                                        <span>R$</span>
                                        <input type="text" name="valorCusto" value="<?= (isset($product)) ? $product->valorCusto : ''; ?>">
                                    </div>
                                </label>
                                <label for="sku" class="col s12 m4">
                                    Código do produto (SKU)
                                    <a class="tooltipped" data-position="top" data-delay="50" data-tooltip="O termo Stock Keeping Unit, em português Unidade de Manutenção de Estoque está ligado à logística de armazém e designa os diferentes itens do estoque, estando normalmente associado a um código identificador.">
                                        <span class="fa fa-question-circle"></span>
                                    </a>
                                    <div class="flex">
                                        <span class="fa fa-barcode"></span>
                                        <input type="text" name="sku" value="<?= (isset($product)) ? $product->sku : ''; ?>">
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
                                        <input type="text" name="peso" value="<?= (isset($product)) ? $product->peso : ''; ?>" required>
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
                                        <input type="text" name="altura" value="<?= (isset($product)) ? $product->altura : ''; ?>" required>
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
                                        <input type="text" name="largura" value="<?= (isset($product)) ? $product->largura : ''; ?>" required>
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
                                        <input type="text" name="comprimento" value="<?= (isset($product)) ? $product->comprimento : ''; ?>" required>
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
                                        <input type="text" name="pesoEmbalagem" value="<?= (isset($product)) ? $product->pesoEmbalagem : ''; ?>" required>
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
                                        <input type="text" name="alturaEmbalagem" value="<?= (isset($product)) ? $product->alturaEmbalagem : ''; ?>" required>
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
                                        <input type="text" name="larguraEmbalagem" value="<?= (isset($product)) ? $product->larguraEmbalagem : ''; ?>" required>
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
                                        <input type="text" name="comprimentoEmbalagem" value="<?= (isset($product)) ? $product->comprimentoEmbalagem : ''; ?>" required>
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
                                            <option <?= ($product->categoriaId == $item->id) ? 'selected' : ''; ?> value="<?= $item->id ?>"><?= $item->descricao ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                                <label class="col l3 m6 s12">
                                    Sub categoria
                                    <select name="subCategoriaIdIderis" class="browser-default">
                                        <option value="" selected disabled>Selecione uma opção</option>
                                        <?php foreach ($subcategory->result as $item): ?>
                                            <option <?= ($product->subCategoriaId == $item->id) ? 'selected' : ''; ?> value="<?= $item->id ?>"><?= $item->descricao ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                                <label class="col l3 m6 s12">
                                    Marca
                                    <select name="marcaIdIderis" class="browser-default">
                                        <option value="" selected disabled>Selecione uma opção</option>
                                        <?php foreach ($brand->result as $item): ?>
                                            <option <?= ($product->marcaId == $item->id) ? 'selected' : ''; ?> value="<?= $item->id ?>"><?= $item->descricao ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                                <label class="col l3 m6 s12">
                                    Departamento
                                    <select name="departamentoIdIderis" class="browser-default">
                                        <option value="" selected disabled>Selecione uma opção</option>
                                        <?php foreach ($department->result as $item): ?>
                                            <option <?= ($product->departamentoId == $item->id) ? 'selected' : ''; ?> value="<?= $item->id ?>"><?= $item->descricao ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>

                                <!-- Dados para mensuração -->
                                <h3>Grupo de Produtos</h3>
                                <div id="card-alert" class="card orange">
                                    <div class="card-content white-text">
                                        <p><i class="mdi-action-info-outline"></i> Lembrete: É importante colocar o devido grupo que este produto pertence, pois assim que chegar um pedido, o mesmo será direcionado para os setores vinculados a aquele grupo.</p>
                                    </div>
                                    <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <label class="col l3 m6 s12">
                                    Grupo
                                    <select name="team" class="browser-default">
                                        <option value="" selected disabled>Não vinculado</option>
                                        <?php foreach ($groups as $item): ?>
                                            <option <?= (!empty($bond) && $item->getId() == $bond->getTeam()) ? 'selected' : ''; ?> value="<?= $item->getId(); ?>"><?= $item->getName(); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>

                                <div class="col s12 center">
                                    <button type="submit" class="btn waves-effect">Salvar</button>
                                </div>
                                </div>
                                </form>

                                <!-- Controle de Estoque -->
                                <div id="stock" class="col s12">

                                    <!-- Modal de Inclusão -->
                                    <aside class="modalStock">
                                        <fieldset>
                                            <button type="button" class="close">
                                                <span class="fa fa-times"></span>
                                            </button>
                                            <h4>Novo lançamento</h4>
                                            <form method="POST" action="stock/persist">
                                                <input type="hidden" name="product[]" value="<?= $product->sku; ?>">
                                                <label for="type" class="col s12 m6">
                                                    Tipo
                                                    <select name="type" class="browser-default">
                                                        <option value="1" selected="">Entrada</option>
                                                        <option value="2">Saída</option>
                                                    </select>
                                                </label>
                                                <label for="amount" class="col s6 m3">
                                                    Quantidade
                                                    <span class="fa fa-exclamation-circle"></span>
                                                    <input type="number" name="amount" min="1" required>
                                                </label>
                                                <label for="price" class="col s6 m3">
                                                    Preço un.
                                                    <span class="fa fa-exclamation-circle"></span>
                                                    <input class="mask" type="text" name="price" placeholder="0,00" required>
                                                </label>
                                                <label for="note" class="col s12">
                                                    Observação
                                                    <textarea name="note" data-length="120"></textarea>   
                                                </label>
                                                <div class="center col s12">
                                                    <button type="submit" class="btn waves-effect">Incluir</button>
                                                    <a class="close btn waves-effect">Cancelar</a>
                                                </div>
                                            </form>
                                        </fieldset>
                                    </aside>

                                    <!-- Contagem atual -->
                                    <ul>
                                        <!-- Abrir modal de inclusão -->
                                        <button type="button" class="btn waves-effect stock">
                                            <span class="fa fa-plus"></span>
                                            Incluir Lançamento
                                        </button>
                                        <div>
                                            <li>
                                                <small>Entradas</small>
                                                <p><?= count($entrace); ?></p>
                                            </li>
                                            <li>
                                                <small>Saídas</small>
                                                <p><?= count($out); ?></p>
                                            </li>
                                            <li>
                                                <small>Estoque Atual</small>
                                                <p><?= $product->quantidadeEstoquePrincipal; ?></p>
                                            </li>
                                        </div>
                                    </ul>

                                    <h3>Entradas</h3>

                                    <?php if(empty($entrace)): ?>
                                        <div class="empty">
                                            <span class="fa fa-folder-open"></span>
                                            <h5>Sem lançamentos de entrada no estoque</h5>
                                            <p>Você pode começar agora mesmo incluindo um lançamento.</p>
                                        </div>
                                    <?php else: ?>
                                        <table class="data-table-simple responsive-table display" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th class="center">ID</th>
                                                    <th class="center">Data de Publicação</th>
                                                    <th class="center">Quantidade</th>
                                                    <th class="center">Preço un.</th>
                                                    <th class="center">Total</th>
                                                    <th class="center">Observação</th>
                                                    <th></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach ($entrace as $item): ?>
                                                    <tr>
                                                        <td class="center"><?= $item->getId(); ?></td>
                                                        <td class="center"><?= $item->getDate()->format('d/m/Y'); ?></td>
                                                        <td class="center"><?= $item->getAmount(); ?></td>
                                                        <td class="center">
                                                            R$ <?= $item->getPrice(); ?>
                                                        </td>
                                                        <td class="center">
                                                            R$ <?php 
                                                                $fmt = new NumberFormatter( 'de_DE', NumberFormatter::DECIMAL );
                                                                $value = $item->getAmount() * $fmt->parse($item->getPrice());
                                                                echo number_format($value, 2, ',', ' ');
                                                                ?>
                                                        </td>
                                                        <td class="center">
                                                            <?php if($item->getNote() == ''): ?>
                                                                Sem observações
                                                            <?php else: ?>
                                                                <a class="tooltipped" data-position="top" data-delay="50" data-tooltip="<?= $item->getNote(); ?>">
                                                                    <span class="fa fa-question-circle"></span>
                                                                </a>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="center">
                                                            <a class="delete" href="stock/delete/<?= $item->getId() ?>/<?= $product->id; ?>">
                                                                <span class="far fa-trash-alt"></span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php endif; ?>

                                    <h3>Retiradas</h3>

                                    <?php if(empty($out)): ?>
                                        <div class="empty">
                                            <span class="fa fa-folder-open"></span>
                                            <h5>Sem lançamentos de saída no estoque</h5>
                                            <p>Ainda não houve nenhuma compra registrada para esse produto.</p>
                                        </div>
                                    <?php else: ?>
                                        <table class="data-table-simple responsive-table display" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th class="center">ID</th>
                                                    <th class="center">Data de Publicação</th>
                                                    <th class="center">Quantidade</th>
                                                    <th class="center">Preço un.</th>
                                                    <th class="center">Total</th>
                                                    <th class="center">Observação</th>
                                                    <th></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach ($out as $item): ?>
                                                    <tr>
                                                        <td class="center"><?= $item->getId(); ?></td>
                                                        <td class="center"><?= $item->getDate()->format('d/m/Y'); ?></td>
                                                        <td class="center"><?= $item->getAmount(); ?></td>
                                                        <td class="center">
                                                            R$ <?= $item->getPrice(); ?>
                                                        </td>
                                                        <td class="center">
                                                            R$ <?php 
                                                                $fmt = new NumberFormatter( 'de_DE', NumberFormatter::DECIMAL );
                                                                $value = $item->getAmount() * $fmt->parse($item->getPrice());
                                                                echo number_format($value, 2, ',', ' ');
                                                                ?>
                                                        </td>
                                                        <td class="center">
                                                            <?php if($item->getNote() == ''): ?>
                                                                Sem observações
                                                            <?php else: ?>
                                                                <a class="tooltipped" data-position="top" data-delay="50" data-tooltip="<?= $item->getNote(); ?>">
                                                                    <span class="fa fa-question-circle"></span>
                                                                </a>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="center">
                                                            <a class="delete" href="stock/delete/<?= $item->getId() ?>/<?= $product->id; ?>">
                                                                <span class="far fa-trash-alt"></span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php endif; ?>
                                </div>

                                <!-- Controle de VAriações -->
                                <div id="variations" class="col s12">

                                    <?php if(empty($product->Variacao)): ?>
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
                                                <?php foreach ($product->Variacao as $item): ?>
                                                    <tr>
                                                        <td class="center"><?= $item->variacaoId; ?></td>
                                                        <td class="center">
                                                            <img src="<?= $item->caminhoImagemVariacao; ?>" alt="">
                                                        </td>
                                                        <td class="center"><?= $item->skuVariacao; ?></td>
                                                        <td class="center"><?= $item->quantidadeVariacao; ?></td>
                                                        <td class="center">
                                                            <a href="variations/view/<?= $product->id; ?>/<?= $item->variacaoId; ?>">
                                                                <span class="far fa-edit"></span>
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
                                    <form method="POST" action="product/persist">
                                        <?php if(isset($product)): ?>
                                            <input type="hidden" name="id" value="<?= $product->id; ?>">
                                            <input type="hidden" name="_method" value="PUT">
                                        <?php endif; ?>
                                        <div class="col s12">
                                            <label for="descricaoLonga">
                                                Descrição completa
                                                <span class="fa fa-exclamation-circle"></span>
                                            </label>
                                            <textarea name="descricaoLonga" class="editor" required>
                                                <?= (isset($product)) ? $product->descricaoLonga : '<p>Insira uma observação no seu produto<p/>'; ?>
                                            </textarea>
                                        </div>
                                        <div class="col s12 center">
                                            <button type="submit" class="btn waves-effect">Salvar</button>
                                        </div>
                                    </form>
                                </div>

                    <?php elseif(isset($variations)): ?>
                         
                            <!-- Controle de Estoque -->
                            <div id="stock" class="col s12">

                                <!-- Modal de Inclusão -->
                                <aside class="modalStock">
                                    <fieldset>
                                        <button type="button" class="close">
                                            <span class="fa fa-times"></span>
                                        </button>
                                        <h4>Novo lançamento</h4>
                                        <form method="POST" action="stock/persist">
                                            <input type="hidden" name="id" value="<?= $id; ?>">
                                            <input type="hidden" name="product" value="<?= $variations->variacaoId; ?>">
                                            <label for="type" class="col s12 m6">
                                                Tipo
                                                <select name="type" class="browser-default">
                                                    <option value="1" selected="">Entrada</option>
                                                    <option value="2">Saída</option>
                                                </select>
                                            </label>
                                            <label for="amount" class="col s6 m3">
                                                Quantidade
                                                <span class="fa fa-exclamation-circle"></span>
                                                <input type="number" name="amount" min="1" required>
                                            </label>
                                            <label for="price" class="col s6 m3">
                                                Preço un.
                                                <span class="fa fa-exclamation-circle"></span>
                                                <input class="mask" type="text" name="price" placeholder="0,00" required>
                                            </label>
                                            <label for="note" class="col s12">
                                                Observação
                                                <textarea name="note" data-length="120"></textarea>   
                                            </label>
                                            <div class="center col s12">
                                                <button type="submit" class="btn waves-effect">Incluir</button>
                                                <a class="close btn waves-effect">Cancelar</a>
                                            </div>
                                        </form>
                                    </fieldset>
                                </aside>

                                <!-- Contagem atual -->
                                <ul>
                                    <!-- Abrir modal de inclusão -->
                                    <button type="button" class="btn waves-effect stock">
                                        <span class="fa fa-plus"></span>
                                        Incluir Lançamento
                                    </button>
                                    <div>
                                        <li>
                                            <small>Entradas</small>
                                            <p><?= count($entrace); ?></p>
                                        </li>
                                        <li>
                                            <small>Saídas</small>
                                            <p><?= count($out); ?></p>
                                        </li>
                                        <li>
                                            <small>Estoque Atual</small>
                                            <p><?= $variations->quantidadeVariacao; ?></p>
                                        </li>
                                    </div>
                                </ul>

                                <h3>Entradas</h3>

                                <?php if(empty($entrace)): ?>
                                    <div class="empty">
                                        <span class="fa fa-folder-open"></span>
                                        <h5>Sem lançamentos de entrada no estoque</h5>
                                        <p>Você pode começar agora mesmo incluindo um lançamento.</p>
                                    </div>
                                <?php else: ?>
                                    <table class="data-table-simple responsive-table display" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th class="center">ID</th>
                                                <th class="center">Data de Publicação</th>
                                                <th class="center">Quantidade</th>
                                                <th class="center">Preço un.</th>
                                                <th class="center">Total</th>
                                                <th class="center">Observação</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php foreach ($entrace as $item): ?>
                                                <tr>
                                                    <td class="center"><?= $item->getId(); ?></td>
                                                    <td class="center"><?= $item->getDate(); ?></td>
                                                    <td class="center"><?= $item->getAmount(); ?></td>
                                                    <td class="center">
                                                        R$ <?= $item->getPrice(); ?>
                                                    </td>
                                                    <td class="center">
                                                        R$ <?php 
                                                            $fmt = new NumberFormatter( 'de_DE', NumberFormatter::DECIMAL );
                                                            $value = $item->getAmount() * $fmt->parse($item->getPrice());
                                                            echo number_format($value, 2, ',', ' ');
                                                            ?>
                                                    </td>
                                                    <td class="center">
                                                        <?php if($item->getNote() == ''): ?>
                                                            Sem observações
                                                        <?php else: ?>
                                                            <a class="tooltipped" data-position="top" data-delay="50" data-tooltip="<?= $item->getNote(); ?>">
                                                                <span class="fa fa-question-circle"></span>
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="center">
                                                        <a class="delete" href="stock/delete/<?= $item->getId() ?>/<?= $id; ?>">
                                                            <span class="far fa-trash-alt"></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>

                                <h3>Retiradas</h3>

                                <?php if(empty($out)): ?>
                                    <div class="empty">
                                        <span class="fa fa-folder-open"></span>
                                        <h5>Sem lançamentos de saída no estoque</h5>
                                        <p>Ainda não houve nenhuma compra registrada para esse produto.</p>
                                    </div>
                                <?php else: ?>
                                    <table class="data-table-simple responsive-table display" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th class="center">ID</th>
                                                <th class="center">Data de Publicação</th>
                                                <th class="center">Quantidade</th>
                                                <th class="center">Preço un.</th>
                                                <th class="center">Total</th>
                                                <th class="center">Observação</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php foreach ($out as $item): ?>
                                                <tr>
                                                    <td class="center"><?= $item->getId(); ?></td>
                                                    <td class="center"><?= $item->getDate(); ?></td>
                                                    <td class="center"><?= $item->getAmount(); ?></td>
                                                    <td class="center">
                                                        R$ <?= $item->getPrice(); ?>
                                                    </td>
                                                    <td class="center">
                                                        R$ <?php 
                                                            $fmt = new NumberFormatter( 'de_DE', NumberFormatter::DECIMAL );
                                                            $value = $item->getAmount() * $fmt->parse($item->getPrice());
                                                            echo number_format($value, 2, ',', ' ');
                                                            ?>
                                                    </td>
                                                    <td class="center">
                                                        <?php if($item->getNote() == ''): ?>
                                                            Sem observações
                                                        <?php else: ?>
                                                            <a class="tooltipped" data-position="top" data-delay="50" data-tooltip="<?= $item->getNote(); ?>">
                                                                <span class="fa fa-question-circle"></span>
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="center">
                                                        <a class="delete" href="stock/delete/<?= $item->getId() ?>/<?= $id; ?>">
                                                            <span class="far fa-trash-alt"></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                </div>
                <!--end container-->
                <?php else: ?>
                    <div class="empty-product">
                        <div>
                            <span class="fa fa-exclamation-triangle"></span>
                            <h2>Ooops não existe informações desse produto!</h2>
                            <a href="<?= $_SERVER['HTTP_REFERER']; ?>">
                                <span class="fa fa-long-arrow-alt-left"></span>
                                Voltar para a página anterior
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
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