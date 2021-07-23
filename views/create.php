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
    
    <title>Detalhes do Pedido</title>
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
                <div class="order container create">
                    <a class="back" href="<?= $url ?>/">
                        <span class="fa fa-long-arrow-alt-left"></span>
                        Voltar para os pedidos
                    </a>
                    <h2>Detalhes do Pedido</h2>
                    <div class="col m6 s12 item">
                        <div class="box">
                            <h3>Encomenda</h3>
                            <small>*Clique no produto para mais informações</small>
                        </div>
                        <?php foreach ($order->Item as $item): ?>
                            <a href="product/view/sku/<?= $item->skuProdutoItem; ?>" class="box flex-item">
                                <figure>
                                    <img src="<?= $item->caminhoImagemItem; ?>" alt="<?= $item->tituloProdutoItem; ?>">
                                </figure>
                                <hgroup>
                                    <h4><?= $item->tituloProdutoItem; ?></h4>
                                    <p>Quantidade Solicitada: <?= $item->quantidadeItem ?></p>
                                    <h5>SKU: <?= $item->skuProdutoItem; ?></h5>
                                    <?php if (!is_null($item->variacaoProdutoItem)): ?>
                                        <h6><?= $item->variacaoProdutoItem; ?></h6>
                                    <?php endif; ?>
                                </hgroup>
                            </a>
                        <?php endforeach; ?>
                        <div class="box">
                            <h3>Dados do Comprador</h3>
                            <p class="name">
                                <strong>
                                    <?= $order->compradorPrimeiroNome; ?> 
                                    <?= $order->compradorSobrenome; ?>
                                </strong>
                            </p>
                            <p>
                                <strong>E-mail:</strong>
                                <?= $order->compradorEmail; ?>
                            </p>
                            <p>
                                <strong><?= $order->compradorTipoDocumento; ?>:</strong>
                                <?= $order->compradorDocumento; ?>
                            </p>
                            <p>
                                <strong>Telefone:</strong>
                                <?= (!empty($order->compradorCodigoAreaTelefone)) ? '(' . $order->compradorCodigoAreaTelefone . ') ' : ''; ?>
                                <?= (!empty($order->compradorTelefone)) ? $order->compradorTelefone : 'Telefone não informado'; ?>
                            </p>
                        </div>
                        <div class="box">
                            <h3>Informações de entrega</h3>
                            <p>
                                <strong>Forma de Envio:</strong>
                                <?= $order->tipoEntrega; ?>
                            </p>
                            <p>
                                <strong>Endereço Completo:</strong>
                                <?= $order->enderecoEntregaCompleto; ?>
                            </p>
                            <p>
                                <strong>Quem retira:</strong>
                                <?= $order->enderecoEntrega_NomeResponsavelRecebimento; ?>
                                (<?= $order->enderecoEntrega_TelefoneResponsavelRecebimento; ?>)
                            </p>
                            <p>
                                <strong>Código de rastreamento:</strong>
                                <?= $order->numeroRastreio; ?>
                            </p>
                        </div>
                    </div>
                    <div class="col m6 s12 box history">
                        <h3>
                            Histórico de Registros
                            <button class="btn waves-effect new">
                                Novo Registro
                                <span class="fa fa-plus"></span>
                            </button>
                        </h3>
                        <ul>
                            <?php foreach ($record as $item): ?>
                                <?php if(!empty($item->getNote())): ?>
                                    <li>
                                        <span class="fa fa-user-edit"></span>
                                        <hgroup>
                                            <datalist>
                                                <time datetime="<?= $item->getDate()->format('d/m/Y'); ?>"><?= $item->getDate()->format('d/m/Y'); ?></time>
                                                <time datetime="<?= $item->getDate()->format('H:i'); ?>"><?= $item->getDate()->format('H:i'); ?></time>
                                            </datalist>
                                            <p><?= $item->getNote(); ?></p>
                                            <?php
                                            $user = array_filter($contributors, function ($elem) use ($item){
                                                return $elem->getId() === $item->getUser();
                                            }); 
                                            $user = current($user);
                                            ?>
                                            <cite>Por <strong><?= $user->getName(); ?></strong></cite>
                                        </hgroup>
                                    </li>
                                <?php endif; ?>
                                <?php if(!is_null($item->getTransferredSector())): ?>
                                    <li>
                                        <span class="fa fa-dolly"></span>
                                        <hgroup>
                                            <datalist>
                                            <time datetime="<?= $item->getDate()->format('d/m/Y'); ?>"><?= $item->getDate()->format('d/m/Y'); ?></time>
                                                <time datetime="<?= $item->getDate()->format('H:i'); ?>"><?= $item->getDate()->format('H:i'); ?></time>
                                            </datalist>
                                            <p>
                                                Produto transferido de 
                                                <?php 
                                                $currentSector = array_filter($status, function ($elem) use ($item){
                                                    return $elem['id'] === $item->getCurrentSector();
                                                }); 
                                                $currentSector = current($currentSector);
                                                ?>
                                                <strong><?= $currentSector['descricao'] ?></strong> 
                                                para 
                                                <?php 
                                                $transferredSector = array_filter($status, function ($elem) use ($item){
                                                    return $elem['id'] === $item->getTransferredSector();
                                                }); 
                                                $transferredSector = current($transferredSector);
                                                ?>
                                                <strong><?= $transferredSector['descricao'] ?></strong> 
                                            </p>
                                            <?php
                                            $user = array_filter($contributors, function ($elem) use ($item){
                                                return $elem->getId() === $item->getUser();
                                            }); 
                                            $user = current($user);
                                            ?>
                                            <cite>Por <strong><?= $user->getName(); ?></strong></cite>
                                        </hgroup>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <!--end container-->
            </section>
            <!-- END CONTENT -->
        </div>
        <!-- END WRAPPER -->
    </div>
    <!-- END MAIN -->

    <!-- Modal de Inclusão de Variação -->
    <aside class="row modalRecord">
        <fieldset>
            <button type="button" class="close">
                <span class="fa fa-times"></span>
            </button>
            <h4>Novo Registro</h4>
            <form method="POST" action="record/persist">
                <input type="hidden" name="order" value="<?= $order->id; ?>">
                <input type="hidden" name="status" value="<?= $order->status; ?>">
                <label for="note">
                    Observação
                    <textarea name="note"></textarea>
                </label>

                <!-- Transferência de Setor -->
                <h3>Transferência de Setor</h3>
                <label for="transfer" class="col s12 m6">
                    <input type="checkbox" name="transfer" value="1">
                    <span>Transferir para o setor</span>
                </label>
                <label for="sector" class="col s12 m6">
                    <select name="sector" class="browser-default">
                        <?php foreach ($status as $item): ?>
                            <option value="<?= $item['id']; ?>"><?= $item['descricao']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <div class="col s12 center">
                    <button type="submit" class="btn waves-effect">Salvar</button>
                </div>
            </form>
        </fieldset>
    </aside>

    <!-- Modal de Visualização de Produto -->
    <aside class="row modalProduct">
        <fieldset>
            <button type="button" class="close">
                <span class="fa fa-times"></span>
            </button>
            <h4>Visualização do Produto</h4>
            <form method="POST" action="product/persist">
                <p><span class="fa fa-exclamation-circle"></span> Campos obrigatórios</p>

                <div class="alert"></div>

                <label class="col m6 s12">
                    Nome do produto
                    <span class="fa fa-exclamation-circle"></span>
                    <input type="text" name="titulo" value="" required>
                </label>
                <label for="sku" class="col s12 m6">
                    Código do produto (SKU)
                    <a class="tooltipped" data-position="top" data-delay="50" data-tooltip="O termo Stock Keeping Unit, em português Unidade de Manutenção de Estoque está ligado à logística de armazém e designa os diferentes itens do estoque, estando normalmente associado a um código identificador.">
                        <span class="fa fa-question-circle"></span>
                    </a>
                    <div class="flex">
                        <span class="fa fa-barcode"></span>
                        <input type="text" name="sku" value="">
                    </div>
                </label>

                <!-- Dados para o transporte -->
                <h3>Peso e dimensões</h3>
                <label for="weight" class="col s12 m6 l3">
                    Peso
                    <span class="fa fa-exclamation-circle"></span>
                    <a class="tooltipped" data-position="top" data-delay="50" data-tooltip="Utilize ponto. Ex: 0.5">
                        <span class="fa fa-question-circle"></span>
                    </a>
                    <div class="flex">
                        <span>gramas</span>
                        <input type="text" name="peso" value="">
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
                        <input type="text" name="altura" value="">
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
                        <input type="text" name="largura" value="">
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
                        <input type="text" name="comprimento" value="">
                    </div>
                </label>
                <div class="col s12 center">
                    <button type="submit" class="btn waves-effect">Salvar</button>
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