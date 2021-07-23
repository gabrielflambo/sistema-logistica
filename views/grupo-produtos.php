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
    
    <title>Grupo de Produtos</title>
    <?php require 'links.php'; ?>
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
                <div class="search-data saved container">
                    <h2>Grupos de Produtos</h2>
                    <?php if(empty($groups)): ?>
                        <div class="empty">
                            <span class="fa fa-folder-open"></span>
                            <h5>Sem grupos de produtos cadastrados no momento</h5>
                            <p>Você pode começar agora mesmo incluindo um novo grupo.</p>
                            <button type="button" class="btn waves-effect add">
                                Adicionar novo grupo
                            </button>
                        </div>
                    <?php else: ?>
                        <button type="button" class="btn waves-effect add">
                            Adicionar novo grupo
                        </button>
                        <table class="data-table-simple responsive-table display" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="center">ID</th>
                                    <th class="center">Nome do Grupo</th>
                                    <th class="center">Setor Padrão</th>
                                    <th class="center">Setor com Estoque</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($groups as $item): ?>
                                    <tr>
                                        <td class="center"><?= $item->getId(); ?></td>
                                        <td class="center"><?= $item->getName(); ?></td>
                                        <td class="center"><?= $office[$item->getSectorP()]; ?></td>
                                        <td class="center"><?= $office[$item->getSectorS()]; ?></td>
                                        <td class="center">
                                            <a href="group/<?= $item->getId(); ?>">
                                                <span class="fa fa-eye"></span>
                                            </a>
                                            <a class="delete" href="group/delete/<?= $item->getId() ?>">
                                                <span class="far fa-trash-alt"></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
                <!--end container-->
            </section>
            <!-- END CONTENT -->
        </div>
        <!-- END WRAPPER -->
    </div>
    <!-- END MAIN -->

    <!-- Modal de Inclusão de Variação -->
    <aside class="row modalGroup">
        <fieldset>
            <button type="button" class="close">
                <span class="fa fa-times"></span>
            </button>
            <h4>Novo Grupo</h4>
            <form method="POST" action="group/persist">
                <label for="name" class="col s12">
                    Nome do Grupo
                    <input type="text" name="name">
                </label>
                <label for="sectorP" class="col s12 m6">
                    Setor Padrão
                    <select name="sectorP" class="browser-default">
                        <?php foreach ($office as $key => $item): ?>
                            <option value="<?= $key; ?>"><?= $item; ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label for="sectorS" class="col s12 m6">
                    Setor com Estoque
                    <select name="sectorS" class="browser-default">
                        <?php foreach ($office as $key => $item): ?>
                            <option value="<?= $key; ?>"><?= $item; ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <div class="col s12 center">
                    <button type="submit" class="btn waves-effect">Salvar</button>
                </div>
            </form>
        </fieldset>
    </aside>

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