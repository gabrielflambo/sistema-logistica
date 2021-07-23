<!-- START LEFT SIDEBAR NAV-->
<aside id="left-sidebar-nav">
    <ul id="slide-out" class="side-nav fixed leftside-navigation">
    <li class="user-details cyan darken-2">
    <?php 
    $office = [
        '1' => 'Criação',
        '2' => 'Pré-Impressão',
        '3' => 'Impressão',
        '4' => 'Quadros',
        '5' => 'Acabamento',
        '6' => 'Expedição'
    ];
    if(!isset($_SESSION['permission'])): ?>
        <div class="row">
            <div class="col col s4 m4 l4">
                <img src="public/images/avatar.jpg" alt="" class="circle responsive-img valign profile-image">
            </div>
            <div class="col col s8 m8 l8">
                <a class="btn-flat waves-effect waves-light white-text profile-btn" href="#">Decora Mais</a>
                <p class="user-roal">Administrator</p>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col col s4 m4 l4">
                <img src="<?= $_SESSION['permission'][2]; ?>" alt="" class="circle responsive-img valign profile-image">
            </div>
            <div class="col col s8 m8 l8">
                <a class="btn-flat waves-effect waves-light white-text profile-btn" href="#"><?= $_SESSION['permission'][0]; ?></a>
                <p class="user-roal"><?= $office[$_SESSION['permission'][1]]; ?></p>
            </div>
        </div>
    <?php endif; ?>
    </li>
    <li class="bold">
        <a href="dashboard" class="waves-effect waves-cyan">
            <i class="mdi-action-dashboard"></i> Painel
        </a>
    </li>
    <?php if($_SESSION['type'] == 0 || in_array('1', $_SESSION['type'])): ?>
        <li class="no-padding">
            <ul class="collapsible collapsible-accordion">
                <li class="bold">
                    <a class="collapsible-header waves-effect waves-cyan">
                        <i class="mdi-action-assignment-ind"></i> Colaboradores
                    </a>
                    <div class="collapsible-body">
                        <ul>
                            <li>
                                <a href="contributors/create">Criar um novo</a>
                            </li>
                            <li>
                                <a href="contributors/search">Procurar</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </li>
    <?php endif; ?>
    <?php if($_SESSION['type'] == 0 || in_array('2', $_SESSION['type'])): ?>
        <li class="no-padding">
            <ul class="collapsible collapsible-accordion">
                <li class="bold">
                    <a class="collapsible-header waves-effect waves-cyan">
                        <i class="mdi-av-my-library-add"></i> Controle de Produtos
                    </a>
                    <div class="collapsible-body">
                        <ul>
                            <li>
                                <a href="product/create">Criar um Produto</a>
                            </li>
                            <li>
                                <a href="product/saved">Produtos em Rascunho</a>
                            </li>
                            <li>
                                <a href="group">Grupo de Produtos</a>
                            </li>
                            <li>
                                <a href="stock">Controle de Estoque</a>
                            </li>
                            <li>
                                <a href="product/search">Todos os Produtos</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </li>
    <?php endif; ?>
    <?php if($_SESSION['type'] == 0 || in_array('3', $_SESSION['type']) || in_array('4', $_SESSION['type']) || in_array('5', $_SESSION['type']) || in_array('6', $_SESSION['type']) || in_array('7', $_SESSION['type']) || in_array('8', $_SESSION['type'])): ?>
        <li class="no-padding">
            <ul class="collapsible collapsible-accordion">
                <li class="bold">
                    <a class="collapsible-header waves-effect waves-cyan">
                        <i class="mdi-action-dns"></i> Setores
                    </a>
                    <div class="collapsible-body">
                        <ul>
                            <?php if($_SESSION['type'] == 0 || in_array('3', $_SESSION['type'])): ?>
                                <li>
                                    <a href="create">Criação</a>
                                </li>
                            <?php endif; ?>
                            <?php if($_SESSION['type'] == 0 || in_array('4', $_SESSION['type'])): ?>
                                <li>
                                    <a href="prepress">Pré-Impressão</a>
                                </li>
                            <?php endif; ?>
                            <?php if($_SESSION['type'] == 0 || in_array('5', $_SESSION['type'])): ?>
                                <li>
                                    <a href="press">Impressão</a>
                                </li>
                            <?php endif; ?>
                            <?php if($_SESSION['type'] == 0 || in_array('6', $_SESSION['type'])): ?>
                                <li>
                                    <a href="frames">Quadros</a>
                                </li>
                            <?php endif; ?>
                            <?php if($_SESSION['type'] == 0 || in_array('7', $_SESSION['type'])): ?>
                                <li>
                                    <a href="finishing">Acabamento</a>
                                </li>
                            <?php endif; ?>
                            <?php if($_SESSION['type'] == 0 || in_array('8', $_SESSION['type'])): ?>
                                <li>
                                    <a href="expedition">Expedição</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
            </ul>
        </li>
    <?php endif; ?>
    <?php if($_SESSION['type'] == 0 || in_array('9', $_SESSION['type'])): ?>
        <li class="bold">
            <a href="orders" class="waves-effect waves-cyan">
                <i class="mdi-action-store"></i> Pedidos
            </a>
        </li>
    <?php endif; ?>
    <?php if($_SESSION['type'] == 0 || in_array('10', $_SESSION['type'])): ?>
        <li class="bold">
            <a href="#" class="waves-effect waves-cyan">
                <i class="mdi-communication-quick-contacts-mail"></i> Postagem
            </a>
        </li>
    <?php endif; ?>
    <?php if($_SESSION['type'] == 0 || in_array('11', $_SESSION['type'])): ?>
        <li class="bold">
            <a href="#" class="waves-effect waves-cyan">
                <i class="mdi-maps-local-shipping"></i> Consultar Frete
            </a>
        </li>
    <?php endif; ?>
    <li class="bold">
        <a href="dashboard/logout" class="waves-effect waves-cyan">
            <i class="mdi-hardware-keyboard-tab"></i> Sair
        </a>
    </li>
    <a href="#" data-activates="slide-out" class="sidebar-collapse btn-floating btn-medium waves-effect waves-light hide-on-large-only cyan"><i class="mdi-navigation-menu"></i></a>
</aside>
<!-- END LEFT SIDEBAR NAV-->