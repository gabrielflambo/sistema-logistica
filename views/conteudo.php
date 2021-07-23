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
    
    <title>Declaração de Conteúdo</title>
    <?php require 'links.php'; ?>

</head>

<body class="print">

    <div class="main">
        <div class="row">
            <div class="col">
                <small>9 - Declaração de Conteúdo</small>
                <h2>Rastreio</h2>
                <?= $rastreio; ?>
                <h3><?= $order->numeroRastreio; ?></h3>
            </div>
            <div class="col">
                <small>Emissão: <?php $date = new DateTime($nota->dataEmissao); echo $date->format('d/m/Y'); ?></small>
            </div>
        </div>
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

    <hr>

    <p class="cite">Declaro que não me enquadro no conceito de contribuinte previsto no art. 4 da Lei Complementar n 87/1996, uma vez que não realizo, com habitualidade ou em volume que caracterize intuito comercial, operações de circulação de mercadoria, ainda que se iniciem no exterior, ou estou dispensando de emissão de nota fiscal por força da legislação tributária vigente, responsabilizando-me, nos termos da lei e a quem da direito, por informações inverídicas. Constitui crime contra a ordem tributária sumprimir ou reduzir tributo, ou contribuição social e qualquer acessório (Lei 8.137/90 Art. 1, V). De modo a facilitar a verificação pelos órgãos de fiscalização tributária, a nota fiscal ou a declaração de conteúdo deverá ser afixada externamente a encomenda.</p>

    <hr>

    <h6>Remetente</h6>

    <?php require 'scripts.php'; ?>
    
</body>

</html>