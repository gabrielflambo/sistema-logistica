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
    
    <title>Controle de Pedidos</title>
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
                    <h2>Busque os Pedidos</h2>
                    <div class="filter">
                        <form method="GET" action="<?= $url; ?>/search">
                            <label class="col l2 m4 s6">
                                Data Inicial
                                <input type="date" name="dataInicial" value="<?= (isset($_SESSION['search'])) ? $_SESSION['search']['dataInicial'] : '' ?>">
                            </label>
                            <label class="col l2 m4 s6">
                                Data Final
                                <input type="date" name="dataFinal" value="<?= (isset($_SESSION['search'])) ? $_SESSION['search']['dataFinal'] : '' ?>">
                            </label>
                            <?php if(isset($status)): ?>
                                <label class="col l2 m4 s6">
                                    Status
                                    <select name="status" class="browser-default">
                                        <option value="" selected disabled>Selecione uma opção</option>
                                        <?php foreach ($status->result as $item): ?>
                                            <option <?= (isset($_SESSION['search']) && $_SESSION['search']['status'] == $item->descricao) ? 'selected' : ''; ?> value="<?= $item->descricao ?>"><?= $item->descricao ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                            <?php endif; ?>
                            <label class="col l2 m4 s6">
                                Origem
                                <select name="marketplace" class="browser-default">
                                    <option value="" selected disabled>Selecione uma opção</option>
                                    <?php foreach ($marketplaces->result as $item): ?>
                                        <option <?= (isset($_SESSION['search']) && $_SESSION['search']['marketplace'] == $item->descricao) ? 'selected' : ''; ?> value="<?= $item->descricao ?>"><?= $item->descricao ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            <label class="col l2 m4 s6">
                                Código Carrinho de Compras
                                <input type="text" name="codigoCarrinhoCompras" value="<?= (isset($_SESSION['search'])) ? $_SESSION['search']['codigoCarrinhoCompras'] : '' ?>">
                            </label>
                            <div class="col l2 m4 s6">
                                <button type="submit" class="btn waves-effect">Pesquisar</button>
                            </div>
                        </form>
                    </div>
                    <div class="clearfix"></div>
                    <div class="flex">
                        <a href="<?= $url; ?>/filters/clean" class="btn waves-effect">
                            Limpar Filtros
                        </a>
                        <small><?= $paginator->offset() + 1; ?> - <?= $paginator->offset() + 10; ?> | Registros encontrados: <?= $orders->paging->total; ?></small>
                    </div>
                    <?php if(!empty($orders->result)): ?>
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th class="center">Código</th>
                                    <th class="center">Imagem</th>
                                    <th class="center">Marketplace</th>
                                    <th class="center">Nome da Conta</th>
                                    <th class="center">Status</th>
                                    <th class="center">Carrinho de Compras</th>
                                    <th class="center">Data</th>
                                    <th class="center"></th>
                                </tr>   
                            </thead>
                            <tbody>
                                <?php
                                foreach ($orders->result as $item): ?>
                                    <tr>
                                        <td class="center"><?= $item->codigo ?></td>
                                        <td class="center"><img src="<?= $item->imagemPedidoItem ?>" alt=""></td>
                                        <td class="center"><?= $item->marketplace ?></td>
                                        <td class="center"><?= $item->nomeContaMarketplace ?></td>
                                        <td class="center"><?= $item->status ?></td>
                                        <td class="center"><?= $item->codigoCarrinhoCompras ?></td>
                                        <td class="center">
                                            <?php $date = new DateTime($item->data);
                                            echo $date->format('d/m/Y'); ?>
                                        </td>
                                        <td class="center">
                                            <a href="<?= $url; ?>/view/<?= $item->id ?>">
                                                <span class="far fa-eye"></span>
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
                            <p>Tente outra forma de pesquisa ou aguarde para mais resultados...</p>
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