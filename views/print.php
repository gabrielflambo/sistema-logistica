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
    
    <title>Nota Fiscal Simplificada</title>
    <?php require 'links.php'; ?>

</head>

<body class="print">

    <div class="main">
        <div class="row">
            <div class="col">
                <small>1 - Saida</small>
                <h2>Rastreio</h2>
                <?= $rastreio; ?>
                <h3><?= $order->numeroRastreio; ?></h3>
            </div>
            <div class="col">
                <small>Emissão: <?php $date = new DateTime($nota->dataEmissao); echo $date->format('d/m/Y'); ?></small>
                <h2>Nota Fiscal de Saída</h2>
                <?= $numero; ?>
                <h3><?= $nota->numeroNota; ?> / 1</h3>
            </div>
        </div>
        <h2>Chave de Acesso</h2>
        <?= $chave; ?>
        <h3><?= $nota->chaveNota; ?></h3>
        <h2>Pedido</h2>
        <?= $pedido; ?>
        <h3><?= $order->codigo; ?></h3>
    </div>

    <h1>Destinatario</h1>
    <div class="row">
        <div class="col">
            <p><?= $order->compradorPrimeiroNome; ?> <?= $order->compradorSobrenome; ?></p>
        </div>
        <div class="col">
            <p>Doc: <?= $order->compradorDocumento; ?></p>
        </div>
    </div>
    <p><?= $order->enderecoEntregaRua; ?>, <?= $order->enderecoEntregaNumero; ?></p>
    <p><?= $order->enderecoEntregaBairro; ?> - <?= $order->enderecoEntregaCidade; ?></p>
    <p><?= $order->enderecoEntregaCep; ?> - <?= $order->enderecoEntregaEstado; ?></p>

    <hr>

    <h4>Remetente</h4>
    <h5>MAISCOR IMPRESSÃO DIGITAL E GR .-</h5>
    <p>Rua Engenheiro Henrique Lussack 126, 126</p>
    <p>Edson Passos - Mesquita</p>
    <p>26553-500 - RJ</p>
    <div class="row">
        <div class="col">
            <p>Doc: 28.977.513-0001-76</p>
        </div>
        <div class="col">
            <p>Tel: 21986574647</p>
        </div>
    </div>

    <hr>

    <?php foreach ($order->Item as $item): ?>
        <p><?= $item->tituloProdutoItem; ?> |  <?= $item->quantidadeItem ?></p>
    <?php endforeach; ?>
    <h5><?= $order->compradorApelido; ?></h5>

    <?php require 'scripts.php'; ?>
    
</body>

</html>