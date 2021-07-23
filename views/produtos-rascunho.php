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
    
    <title>Produtos em Rascunho</title>
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
                    <h2>Busque os Produtos em Rascunho</h2>
                    <?php if(empty($products)): ?>
                        <div class="empty">
                            <span class="fa fa-folder-open"></span>
                            <h5>Sem produtos em rascunho no momento</h5>
                            <p>Você pode começar agora mesmo incluindo um novo produto.</p>
                            <a href="product/create" class="btn waves-effect">
                                Cadastrar novo produto
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="flex-end">
                            <a href="product/create" class="btn waves-effect">
                                Cadastrar novo produto
                            </a>
                            <button class="btn waves-effect publish">
                                Publicar
                            </button>
                        </div>
                        <table class="data-table-simple responsive-table display" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="center">ID</th>
                                    <th class="center">Imagem</th>
                                    <th class="center">Nome</th>
                                    <th class="center">SKU</th>
                                    <th class="center">Preço de Custo</th>
                                    <th></th>
                                    <th><input type="checkbox" name="all"></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($products as $item): ?>
                                    <tr>
                                        <td class="center"><?= $item->getId(); ?></td>
                                        <td class="center">
                                            <?php 
                                                $img = array_filter($image, function ($elem) use ($item){
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
                                        <td class="center"><?= $item->getTitulo(); ?></td>
                                        <td class="center"><?= $item->getSku(); ?></td>
                                        <td class="center"><?= $item->getValorCusto(); ?></td>
                                        <td class="center">
                                            <a href="product/edit/sketch/<?= $item->getId(); ?>">
                                                <span class="far fa-edit"></span>
                                            </a>
                                            <a class="delete" href="product/delete/<?= $item->getId() ?>">
                                                <span class="far fa-trash-alt"></span>
                                            </a>
                                        </td>
                                        <td class="center">
                                            <input type="checkbox" name="product[]" value="<?= $item->getId(); ?>">
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

    <aside class="row progres">
        <article>
            <figure>
                <img src="public/images/loading.gif" alt="">
                <figcaption></figcaption>
            </figure>
            <p>Aguarde enquanto processamos seus dados</p>
            <div class="complete">
                <div></div>
            </div>
            <small><span>0</span>%</small>
        </article>
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