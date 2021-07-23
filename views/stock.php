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
    
    <title>Controle de Estoque</title>
    <?php require 'links.php'; ?>

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
                <div class="search-data container">
                    <h2>Controle de Estoque</h2>
                    <div class="filter">
                        <form method="GET" action="stock/query">
                            <div class="col l2 m4 s6">
                                <!-- Abrir modal de inclusão -->
                                <button type="button" class="btn waves-effect stock">
                                    <span class="fa fa-plus"></span>
                                    Incluir Lançamento
                                </button>
                            </div>
                            <label class="col l2 m4 s6">
                                Data Inicial
                                <input type="date" name="dataInicial" value="<?= (isset($_SESSION['search'])) ? $_SESSION['search']['dataInicial'] : '' ?>">
                            </label>
                            <label class="col l2 m4 s6">
                                Data Final
                                <input type="date" name="dataFinal" value="<?= (isset($_SESSION['search'])) ? $_SESSION['search']['dataFinal'] : '' ?>">
                            </label>
                            <label class="col l2 m4 s6">
                                SKU
                                <input type="text" name="sku" value="<?= (isset($_SESSION['search'])) ? $_SESSION['search']['sku'] : '' ?>">
                            </label>
                            <label class="col l2 m4 s6">
                                Tipo
                                <select name="tipo" class="browser-default">
                                    <option value="" selected disabled>Selecione uma opção</option>
                                    <option <?= (isset($_SESSION['search']) && $_SESSION['search']['tipo'] == 1) ? 'selected' : ''; ?> value="1">Entrada</option>
                                    <option <?= (isset($_SESSION['search']) && $_SESSION['search']['tipo'] == 2) ? 'selected' : ''; ?> value="2">Saída</option>
                                </select>
                            </label>
                            <div class="col l2 m4 s6">
                                <button type="submit" class="btn waves-effect">Pesquisar</button>
                            </div>
                        </form>
                    </div>
                    <div class="clearfix"></div>
                    <div class="flex">
                        <a href="stock/filters/clean" class="btn waves-effect">
                            Limpar Filtros
                        </a>
                        <small>
                            Visualizando registros de <?= $paginator->offset() + 1; ?> - <?= $paginator->offset() + 10; ?>
                        </small>
                    </div>
                    <?php if(!empty($stock)): ?>
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th class="center">ID</th>
                                    <th class="center">SKU</th>
                                    <th class="center">Tipo</th>
                                    <th class="center">Quantidade</th>
                                    <th class="center">Valor de Custo</th>
                                    <th class="center">Observações</th>
                                    <th class="center">Data de Registro</th>
                                    <th class="center"></th>
                                </tr>   
                            </thead>
                            <tbody>
                                <?php
                                foreach ($stock as $item): ?>
                                    <tr>
                                        <td class="center"><?= $item->getId() ?></td>
                                        <td class="center"><?= $item->getProduct() ?></td>
                                        <td class="center"><?= ($item->getType() == 1) ? 'Entrada' : 'Saída' ?></td>
                                        <td class="center"><?= $item->getAmount() ?></td>
                                        <td class="center">R$ <?= $item->getPrice() ?></td>
                                        <td class="center">
                                            <?php if($item->getNote() == ''): ?>
                                                Sem observações
                                            <?php else: ?>
                                                <a class="tooltipped" data-position="top" data-delay="50" data-tooltip="<?= $item->getNote(); ?>">
                                                    <span class="fa fa-question-circle"></span>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td class="center"><?= $item->getDate()->format('d/m/Y') ?></td>
                                        <td class="center">
                                            <a class="delete" href="stock/delete/<?= $item->getId() ?>">
                                                <span class="far fa-trash-alt"></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="col s12 center">
                            <?= $paginator->render(); ?>
                        </div>
                    <?php else: ?>
                        <div class="empty">
                            <span class="fa fa-folder-open"></span>
                            <h5>Sem registros encontrados nesse momento</h5>
                            <p>Tente outra forma de pesquisa</p>
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

    <!-- Modal de Inclusão -->
    <aside class="row modalStock">
        <fieldset>
            <button type="button" class="close">
                <span class="fa fa-times"></span>
            </button>
            <h4>Novo lançamento</h4>
            <form method="POST" action="stock/persist">
                <label for="sku" class="col s12">
                    Pesquisar Produto pelo SKU
                    <input type="text" name="sku">
                    <ul class="complete"></ul>
                </label>
                <ul class="group"></ul>
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
                    <button type="submit" class="btn waves-effect disabled">Incluir</button>
                    <a class="close btn waves-effect">Cancelar</a>
                </div>
            </form>
        </fieldset>
    </aside>

    <?php require 'footer.php'; ?>
    <?php require 'scripts.php'; ?>
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