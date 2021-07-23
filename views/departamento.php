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
    
    <title>Todos os Departamentos</title>
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
                    <h2>Busque Todos os Departamentos</h2>
                    <div class="filter">
                        <form method="GET" action="department/query">
                            <label class="col l4 m6 s6">
                                Busca
                                <input type="text" name="query" value="<?= (isset($_SESSION['search']['query'])) ? $_SESSION['search']['query'] : '' ?>">
                            </label>
                            <div class="col l2 m4 s6">
                                <button type="submit" class="btn waves-effect">Pesquisar</button>
                            </div>
                        </form>
                        <button type="button" class="btn waves-effect create">
                            Criar Departamento
                        </button>
                    </div>
                    <div class="clearfix"></div>
                    <div class="flex">
                        <a href="department/filters/clean" class="btn waves-effect">
                            Limpar Filtros
                        </a>
                        <small>
                            <?= $paginator->offset() + 1; ?> - <?= $paginator->offset() + 10; ?> 
                            | 
                            Registros encontrados: 
                            <?= (!isset($_SESSION['search']['query'])) ? $department->paging->total : $department->paging->count; ?>
                        </small>
                    </div>
                    <table class="responsive-table">
                        <thead>
                            <tr>
                                <th class="center">ID</th>
                                <th class="center">Descrição</th>
                            </tr>   
                        </thead>
                        <tbody>
                            <?php
                            foreach ($department->result as $item): ?>
                                <tr>
                                    <td class="center"><?= $item->id ?></td>
                                    <td class="center"><?= $item->descricao ?></td>
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

    <aside class="row modalCreate">
        <fieldset>
            <a href="javascript:void(0)" class="close">
                <span class="fa fa-times"></span>
            </a>
            <form method="POST" action="department/create">
                <h2>Criar um departamento</h2>
                <label for="descricao">
                    <input type="text" name="descricao" required placeholder="Insira o nome aqui...">
                </label>
                <button type="submit" class="btn waves-effect">
                    Cadastrar departamento
                </button>
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