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
    
    <title>Gestão de Colaboradores</title>
    <?php require 'links.php'; ?>
    <!--dropify-->
    <link href="public/js/plugins/dropify/css/dropify.min.css" type="text/css" rel="stylesheet" media="screen,projection">

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
                <div class="cadastro container">
                    <fieldset>
                        <form method="POST" action="contributors/persist" enctype="multipart/form-data">
                            <?php if(isset($contributors)): ?>
                                <input type="hidden" name="id" value="<?= $contributors->getId(); ?>">
                                <input type="hidden" name="_method" value="PUT">
                            <?php endif; ?>
                            <div class="col m6 s12">
                                <h2>Gestão de Colaboradores</h2>
                                <div class="flex">
                                    <figure class="col m6 s12">
                                        <?php if(!isset($contributors) || empty($contributors->getImage())): ?>
                                            <input type="file" id="input-file" name="input-file" class="dropify" />
                                        <?php else: ?>
                                            <input type="file" id="input-file" name="input-file" class="dropify" data-default-file="<?= $contributors->getImage(); ?>" />
                                        <?php endif; ?>
                                    </figure>
                                    <label for="name" class="col m6 s12">
                                        Nome do colaborador
                                        <input type="text" name="name" value="<?= (isset($contributors)) ? $contributors->getName() : ''; ?>" required>
                                    </label>
                                </div>
                                <div class="clearfix"></div>
                                <label for="office" class="col m6 s12">
                                    Qual é o setor de atuação?
                                    <select name="office" required class="browser-default">
                                        <option value="" selected disabled>Selecione um setor</option>
                                        <option value="1" <?= (isset($contributors) && $contributors->getOffice() == 1) ? 'selected' : ''; ?>>Criação</option>
                                        <option value="2" <?= (isset($contributors) && $contributors->getOffice() == 2) ? 'selected' : ''; ?>>Pré-Impressão</option>
                                        <option value="3" <?= (isset($contributors) && $contributors->getOffice() == 3) ? 'selected' : ''; ?>>Impressão</option>
                                        <option value="4" <?= (isset($contributors) && $contributors->getOffice() == 4) ? 'selected' : ''; ?>>Quadros</option>
                                        <option value="5" <?= (isset($contributors) && $contributors->getOffice() == 5) ? 'selected' : ''; ?>>Acabamento</option>
                                        <option value="6" <?= (isset($contributors) && $contributors->getOffice() == 6) ? 'selected' : ''; ?>>Expedição</option>
                                        <option value="7" <?= (isset($contributors) && $contributors->getOffice() == 6) ? 'selected' : ''; ?>>Administração</option>
                                    </select>
                                </label>
                                <label for="user" class="col m6 s12">
                                    Usuário
                                    <input type="text" name="user" value="<?= (isset($contributors)) ? $contributors->getUser() : ''; ?>" required>
                                </label>
                                <label for="password" class="col m6 s12">
                                    Senha
                                    <input type="password" name="password" <?= (!isset($contributors)) ? 'required' : ''; ?>>
                                </label>
                                <label for="confirmPassword" class="col m6 s12">
                                    Confirmar Senha
                                    <input type="password" name="confirmPassword" <?= (!isset($contributors)) ? 'required' : ''; ?>>
                                </label>
                            </div>
                            <div class="col m6 s12">
                                <h3>Escolha quais serão as permissões desse usuário:</h3>
                                <?php (isset($contributors)) ? $permission = explode(',', $contributors->getPermission()) : ''; ?>
                                <div class="switch">
                                    Cadastro de Colaboradores
                                    <label>
                                        <input type="checkbox" value="1" name="permission[]" <?= (isset($contributors) && in_array('1', $permission)) ? 'checked' : ''; ?>>
                                        <span class="lever"></span>
                                    </label>
                                </div>
                                <div class="switch">
                                    Controle de Produtos
                                    <label>
                                        <input type="checkbox" value="2" name="permission[]" <?= (isset($contributors) && in_array('2', $permission)) ? 'checked' : ''; ?>>
                                        <span class="lever"></span>
                                    </label>
                                </div>
                                <div class="switch">
                                    Setor de Criação
                                    <label>
                                        <input type="checkbox" value="3" name="permission[]" <?= (isset($contributors) && in_array('3', $permission)) ? 'checked' : ''; ?>>
                                        <span class="lever"></span>
                                    </label>
                                </div>
                                <div class="switch">
                                    Setor de Pré-Impressão
                                    <label>
                                        <input type="checkbox" value="4" name="permission[]" <?= (isset($contributors) && in_array('4', $permission)) ? 'checked' : ''; ?>>
                                        <span class="lever"></span>
                                    </label>
                                </div>
                                <div class="switch">
                                    Setor de Impressão
                                    <label>
                                        <input type="checkbox" value="5" name="permission[]" <?= (isset($contributors) && in_array('5', $permission)) ? 'checked' : ''; ?>>
                                        <span class="lever"></span>
                                    </label>
                                </div>
                                <div class="switch">
                                    Setor de Quadros
                                    <label>
                                        <input type="checkbox" value="6" name="permission[]" <?= (isset($contributors) && in_array('6', $permission)) ? 'checked' : ''; ?>>
                                        <span class="lever"></span>
                                    </label>
                                </div>
                                <div class="switch">
                                    Setor de Acabamento
                                    <label>
                                        <input type="checkbox" value="7" name="permission[]" <?= (isset($contributors) && in_array('7', $permission)) ? 'checked' : ''; ?>>
                                        <span class="lever"></span>
                                    </label>
                                </div>
                                <div class="switch">
                                    Setor de Expedição
                                    <label>
                                        <input type="checkbox" value="8" name="permission[]" <?= (isset($contributors) && in_array('8', $permission)) ? 'checked' : ''; ?>>
                                        <span class="lever"></span>
                                    </label>
                                </div>
                                <div class="switch">
                                    Visualização de Todos os Pedidos
                                    <label>
                                        <input type="checkbox" value="9" name="permission[]" <?= (isset($contributors) && in_array('9', $permission)) ? 'checked' : ''; ?>>
                                        <span class="lever"></span>
                                    </label>
                                </div>
                                <div class="switch">
                                    Gestão de Postagem
                                    <label>
                                        <input type="checkbox" value="10" name="permission[]" <?= (isset($contributors) && in_array('10', $permission)) ? 'checked' : ''; ?>>
                                        <span class="lever"></span>
                                    </label>
                                </div>
                                <div class="switch">
                                    Consultar Frete
                                    <label>
                                        <input type="checkbox" value="11" name="permission[]" <?= (isset($contributors) && in_array('11', $permission)) ? 'checked' : ''; ?>>
                                        <span class="lever"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col s12 center">
                                <button type="submit" class="btn waves-effect">Salvar</button>
                            </div>
                        </form>
                    </fieldset>

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
    <script type="text/javascript" src="public/js/image.js"></script>
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