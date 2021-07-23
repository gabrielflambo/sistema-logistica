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
    
    <title>Todos os Colaboradores</title>
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
                <div class="search container">
                    <h2>Busque Todos os Colaboradores</h2>
                    <table class="data-table-simple responsive-table display" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="center">ID</th>
                                <th class="center">Foto</th>
                                <th>Nome</th>
                                <th class="center">Setor</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($contributor as $item): ?>
                                <tr>
                                    <td class="center"><?= $item->getId(); ?></td>
                                    <td class="center">
                                        <figure>
                                            <img src="<?= $item->getImage(); ?>" alt="">
                                        </figure>
                                    </td>
                                    <td>
                                        <a href="contributors/edit/<?= $item->getId(); ?>">
                                            <?= $item->getName(); ?>
                                        </a>
                                    </td>
                                    <td class="center"><?= $office[$item->getOffice()]; ?></td>
                                    <td class="center">
                                        <a class="delete" href="contributors/delete/<?= $item->getId() ?>">
                                            <span class="far fa-trash-alt"></span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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
    <!-- data-tables -->
    <script type="text/javascript" src="public/js/plugins/data-tables/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="public/js/plugins/data-tables/data-tables-script.js"></script>
    <script type="text/javascript" src="public/js/contributor.js"></script>
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