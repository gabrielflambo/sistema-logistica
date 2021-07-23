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
    
    <title>Todos os Produtos</title>
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
                    <h2>Busque Todos os Produtos</h2>
                    <div class="filter">
                        <form method="GET" action="product/query">
                            <label class="col l4 m6 s6">
                                SKU
                                <input type="text" name="sku" value="<?= (isset($_SESSION['search']['sku'])) ? $_SESSION['search']['sku'] : '' ?>">
                            </label>
                            <div class="col l2 m4 s6">
                                <button type="submit" class="btn waves-effect">Pesquisar</button>
                            </div>
                        </form>
                    </div>
                    <div class="clearfix"></div>
                    <div class="flex">
                        <a href="product/filters/clean" class="btn waves-effect">
                            Limpar Filtros
                        </a>
                        <small>
                            <?= $paginator->offset() + 1; ?> - <?= $paginator->offset() + 10; ?> 
                            | 
                            Registros encontrados: 
                            <?= ($products->paging->total != 0) ? $products->paging->total : $products->paging->limit; ?>
                        </small>
                    </div>
                    <table class="responsive-table">
                        <thead>
                            <tr>
                                <th class="center">ID</th>
                                <th class="center">Imagem</th>
                                <th class="center">SKU</th>
                                <th class="center">Título</th>
                                <th class="center">Departamento</th>
                                <th class="center">Grupo de Produtos</th>
                                <th class="center"></th>
                            </tr>   
                        </thead>
                        <tbody>
                            <?php
                            foreach ($products->result as $item): ?>
                                <tr>
                                    <td class="center"><?= $item->id ?></td>
                                    <td class="center"><img src="<?= $item->caminhoImagem ?>" alt=""></td>
                                    <td class="center"><?= $item->sku ?></td>
                                    <td class="center"><?= $item->titulo ?></td>
                                    <td class="center"><?= $item->departamento ?></td>
                                    <?php 
                                    $tie = array_filter($bond, function ($elem) use ($item){
                                        return $elem->getSku() == $item->sku;
                                    });
                                    if (!empty($tie)):
                                        $tie = current($tie);
                                        $group = array_filter($groups, function ($elem) use ($tie){
                                            return $elem->getId() == $tie->getTeam();
                                        });
                                        $group = current($group);
                                    ?>
                                        <td class="center"><?= $group->getName(); ?></td>
                                    <?php else: ?>
                                        <td class="center">Não vinculado</td>
                                    <?php endif; ?>
                                    <td class="center">
                                        <a href="product/edit/<?= $item->id ?>">
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