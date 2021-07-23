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
                <div class="order container">
                    <a class="back" href="orders/">
                        <span class="fa fa-long-arrow-alt-left"></span>
                        Voltar para todos os pedidos
                    </a>
                    <h2>Detalhes do Pedido</h2>
                    <div class="etiqueta">
                        <?php if(is_null($nota)): ?>
                            <a class="btn waves-effect" href="orders/view/<?= $order->id; ?>">
                                <span class="fa fa-notes-medical"></span>
                                Criar nota
                            </a>
                        <?php else: ?>
                            <a target="_blank" class="btn waves-effect" href="<?= $nota->result[0]->urlDanfe; ?>">
                                <span class="fa fa-file-contract"></span>
                                Visualizar nota
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="box">
                        <div class="col m6 s12">
                            <h3>Status do pedido</h3>
                            <h6 class="status"><?= $order->status; ?></h6>
                            <h4>
                                <?php 
                                $date = new DateTime($order->data);
                                echo $date->format('d/m/Y H:i'); ?>
                            </h4>
                        </div>
                        <div class="col m6 s12">
                            <form method="POST" action="orders/persist">
                                <input type="hidden" name="idPedidos" value="<?= $order->id; ?>">
                                <input type="hidden" name="_method" value="PUT">
                                <label for="idNovoStatus">
                                    Alterar para
                                    <select name="idNovoStatus" class="browser-default">
                                        <option value="" selected disabled>Selecione uma opção</option>
                                        <?php foreach ($status->result as $item): ?>
                                            <option <?= ($order->status == $item->descricao) ? 'selected' : ''; ?> value="<?= $item->id ?>"><?= $item->descricao ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                                <button type="submit" class="btn waves-effect">Salvar</button>
                            </form>
                        </div>
                    </div>
                    <div class="col m6 s12">
                        <div class="box">
                            <h3>Detalhe de Venda</h3>
                            <ul>
                                <p>O seu comprador pagou</p>
                                <ol>
                                    <?php foreach ($order->Item as $item): ?>
                                        <li>
                                            <div class="flex">
                                                <span><?= $item->tituloProdutoItem ?></span>
                                                <span><?= $order->moeda ?> <?= number_format($item->precoUnitarioItem * $item->quantidadeItem, 2, ',', '.'); ?></span>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ol>
                                <li>
                                    <span>Envio</span>
                                    <span><?= $order->moeda ?> <?= number_format($order->freteComprador, 2, ',', '.'); ?></span>
                                </li>
                                <li>
                                    <span>Total</span>
                                    <span><?= $order->moeda ?> <?= number_format($order->valorTotalComFrete, 2, ',', '.'); ?></span>
                                </li>
                            </ul>
                            <ul>
                                <p>Foi debitado do que você recebeu</p>
                                <li>
                                    <span>Tarifa Envio</span>
                                    <span><?= $order->moeda ?> <?= number_format($order->tarifaEnvio, 2, ',', '.'); ?></span>
                                </li>
                                <li>
                                    <span>Tarifa de venda</span>
                                    <span><?= $order->moeda ?> <?= number_format($order->tarifaVenda, 2, ',', '.'); ?></span>
                                </li>
                                <li>
                                    <strong>Total debitado</strong>
                                    <strong><?= $order->moeda ?> <?= number_format($order->totalDebitado, 2, ',', '.'); ?></strong>
                                </li>
                                <li>
                                    <strong>Custo do Produto</strong>
                                    <strong><?= $order->moeda ?> <?= number_format($order->custoProduto, 2, ',', '.'); ?></strong>
                                </li>
                                <li>
                                    <strong>Sobrou</strong>
                                    <strong><?= $order->moeda ?> <?= number_format($order->valorSobrou, 2, ',', '.'); ?></strong>
                                </li>
                                <li>
                                    <strong>Total Líquido</strong>
                                    <strong><?= $order->moeda ?> <?= number_format($order->totalLiquido, 2, ',', '.'); ?></strong>
                                </li>
                            </ul>
                        </div>
                        <div class="box">
                            <h3>Detalhe do pagamento</h3>
                            <table class="centered">
                                <thead>
                                    <tr>
                                        <th>Aprovação</th>
                                        <th>Status</th>
                                        <th>Forma de Pagamento</th>
                                        <th>Parcelamento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($order->Pagamento as $item): ?>
                                        <tr>
                                            <td>
                                                <?php 
                                                $date = new DateTime($item->dataAprovacaoPagamento);
                                                echo $date->format('d/m/Y H:i') ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if ($item->statusPagamento == 'approved' || $item->statusPagamento == 'Approved') {
                                                        echo 'Aprovado';
                                                    } elseif ($item->statusPagamento == 'in_process') {
                                                        echo 'Em processamento';
                                                    } elseif ($item->statusPagamento == 'rejected') {
                                                        echo 'Rejeitado';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    if ($item->formaPagamento == 'credit_card') {
                                                        echo '<span class="far fa-credit-card"></span>';
                                                        echo 'Cartão de Crédito';
                                                    } elseif ($item->formaPagamento == 'ticket') {
                                                        echo '<span class="fa fa-file-invoice"></span>';
                                                        echo 'Boleto Bancário';
                                                    } elseif ($item->formaPagamento == 'pix') {
                                                        echo '<span class="fa fa-exchange-alt"></span>';
                                                        echo 'Pix';
                                                    } elseif ($item->formaPagamento == 'debit_account') {
                                                        echo '<span class="fa fa-credit-card"></span>';
                                                        echo 'Débito';
                                                    } elseif ($item->formaPagamento == 'other') {
                                                        echo 'Outro';
                                                    } elseif ($item->formaPagamento == 'account_money') {
                                                        echo '<span class="fa fa-exchange-alt"></span>';
                                                        echo 'Transferência Bancária';
                                                    }
                                                ?>
                                            </td>
                                            <td><?= $item->numeroParcelasPagamento ?>x</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
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
                                <strong>Status:</strong>
                                <?= $order->status; ?>
                            </p>
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
                                <strong>Custo de Envio:</strong>
                                <?= $order->moeda ?> <?= number_format($order->freteComprador, 2, ',', '.'); ?>
                            </p>
                            <p>
                                <strong>Código de rastreamento:</strong>
                                <?= $order->numeroRastreio; ?>
                            </p>
                        </div>
                    </div>
                    <div class="col m6 s12 item">
                        <div class="box">
                            <h3>Encomenda</h3>
                        </div>
                        <?php foreach ($order->Item as $item): ?>
                            <a href="product/view/sku/<?= $item->skuProdutoItem; ?>" class="box flex-item">
                                <figure>
                                    <img src="<?= $item->caminhoImagemItem; ?>" alt="<?= $item->tituloProdutoItem; ?>">
                                </figure>
                                <hgroup>
                                    <h4><?= $item->tituloProdutoItem; ?></h4>
                                    <p><?= $order->moeda ?> <?= number_format($item->precoUnitarioItem, 2, ',', '.'); ?> x <?= $item->quantidadeItem ?></p>
                                    <h5>SKU: <?= $item->skuProdutoItem; ?></h5>
                                    <?php if (!is_null($item->variacaoProdutoItem)): ?>
                                        <h6><?= $item->variacaoProdutoItem; ?></h6>
                                    <?php endif; ?>
                                </hgroup>
                            </a>
                        <?php endforeach; ?>
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