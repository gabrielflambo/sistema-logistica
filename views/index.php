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
    
    <title>Login</title>
    <?php require 'links.php'; ?>
    <link href="public/js/plugins/jquery.nestable/nestable.css" type="text/css" rel="stylesheet" media="screen,projection">

</head>

<body>

    <main class="login">
        <div class="fundo"></div>
        <div class="box">
            <fieldset>
                <?php if(isset($_SESSION['mensagem'])): ?>
                    <div id="card-alert" class="card <?= ($_SESSION['tipo'] == 'danger') ? 'red' : 'green' ?>">
                        <div class="card-content white-text">
                        <?php if($_SESSION['tipo'] == 'danger'): ?>
                            <p><i class="mdi-alert-error"></i> Erro: <?= $_SESSION['mensagem']; ?></p>
                        <?php else: ?>
                            <p><i class="mdi-navigation-check"></i> Sucesso: <?= $_SESSION['mensagem']; ?></p>
                        <?php endif; ?>
                        </div>
                        <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                <?php
                    unset($_SESSION['tipo']);
                    unset($_SESSION['mensagem']);
                endif;
                ?>
                <div class="center">
                    <span class="far fa-user"></span>
                </div>
                <form method="POST" action="login">
                    <label for="user">
                        <span class="fa fa-user"></span>
                        <input autocomplete="off" type="text" name="user" placeholder="Usuário" required>
                    </label>
                    <label for="password">
                        <span class="fa fa-lock"></span>
                        <input type="password" name="password" placeholder="***********" required>
                    </label>
                    <!-- <a href="#">Esqueceu sua senha?</a> -->
                    <button type="submit" class="btn waves-effect">Entrar</button>
                </form>
            </fieldset>
        </div>
    </main>

    <?php require 'scripts.php'; ?>
    <!--nestable -->
    <link href="public/js/plugins/jquery.nestable/nestable.css" type="text/css" rel="stylesheet" media="screen,projection">
    
</body>

</html>