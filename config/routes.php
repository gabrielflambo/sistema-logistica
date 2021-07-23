<?php

require __DIR__ . '/config.php';

use Template\Helper\Util;
use CoffeeCode\Router\Router;
use Template\Controller\Admin;
use Template\Controller\Brand;
use Template\Controller\Category;
use Template\Controller\Stocks;
use Template\Controller\Products;
use Template\Controller\Contributors;
use Template\Controller\Creator;
use Template\Controller\Department;
use Template\Controller\Expedition;
use Template\Controller\Finishing;
use Template\Controller\Frame;
use Template\Controller\Groups;
use Template\Controller\Images;
use Template\Controller\Infos;
use Template\Controller\Order;
use Template\Controller\Prepress;
use Template\Controller\Press;
use Template\Controller\Prints;
use Template\Controller\Records;
use Template\Controller\Subcategory;
use Template\Controller\Variations;

$router = new Router(URL_BASE);

// Injetor de Dependencia Doctrine
$container = require __DIR__ . '/dependencies.php';

// Controladores de Requisição
$admin = new Admin($container);
$contributor = new Contributors($container);
$product = new Products($container);
$stock = new Stocks($container);
$variation = new Variations($container);
$image = new Images($container);
$info = new Infos($container);
$record = new Records($container);
$group = new Groups($container);
$order = new Order();
$creator = new Creator($container);
$prepress = new Prepress($container);
$press = new Press($container);
$frames = new Frame($container);
$finishing = new Finishing($container);
$expedition = new Expedition($container);
$category = new Category();
$subcategory = new Subcategory();
$brand = new Brand();
$department = new Department();
$helper = new Util;
$print = new Prints();

$router->namespace('src/Controller');

// Rotas de login
$router->group('/');
$router->get('/', function () use ($admin){$admin->index();});
$router->post('/login', function ($data) use ($admin){$admin->login($data);});

// Rotas do Painel Administrativo
$router->group('/dashboard');
$router->get('/', function () use ($admin){$admin->painel();});
$router->get('/logout', function () use ($admin){$admin->logout();});

// Rotas para Colaboradores
$router->group('/contributors');
$router->get('/create', function () use ($contributor){$contributor->create();});
$router->get('/edit/{id}', function ($data) use ($contributor){$contributor->edit($data);});
$router->get('/search', function () use ($contributor){$contributor->search();});
$router->post('/persist', function ($data) use ($contributor){$contributor->persist($data);});
$router->put('/persist', function ($data) use ($contributor){$contributor->persist($data);});
$router->delete('/delete', function ($data) use ($contributor){$contributor->delete($data);});

// Rotas para Categorias
$router->group('/category');
$router->get('/', function () use ($category){$category->index(["page" => 1]);});
$router->get('/{page}', function ($data) use ($category){$category->index($data);});
$router->post('/create', function ($data) use ($category){$category->create($data);});
$router->get('/query', function ($data) use ($category){$category->query(["page" => 1]);});
$router->get('/query/{page}', function ($data) use ($category){$category->query($data);});
$router->get('/filters/clean', function () use ($category){$category->clean();});

// Rotas para Sub Categorias
$router->group('/subcategory');
$router->get('/', function () use ($subcategory){$subcategory->index(["page" => 1]);});
$router->get('/{page}', function ($data) use ($subcategory){$subcategory->index($data);});
$router->post('/create', function ($data) use ($subcategory){$subcategory->create($data);});
$router->get('/query', function ($data) use ($subcategory){$subcategory->query(["page" => 1]);});
$router->get('/query/{page}', function ($data) use ($subcategory){$subcategory->query($data);});
$router->get('/filters/clean', function () use ($subcategory){$subcategory->clean();});

// Rotas para Categorias
$router->group('/brand');
$router->get('/', function () use ($brand){$brand->index(["page" => 1]);});
$router->get('/{page}', function ($data) use ($brand){$brand->index($data);});
$router->post('/create', function ($data) use ($brand){$brand->create($data);});
$router->get('/query', function ($data) use ($brand){$brand->query(["page" => 1]);});
$router->get('/query/{page}', function ($data) use ($brand){$brand->query($data);});
$router->get('/filters/clean', function () use ($brand){$brand->clean();});

// Rotas para Departamentos
$router->group('/department');
$router->get('/', function () use ($department){$department->index(["page" => 1]);});
$router->get('/{page}', function ($data) use ($department){$department->index($data);});
$router->post('/create', function ($data) use ($department){$department->create($data);});
$router->get('/query', function ($data) use ($department){$department->query(["page" => 1]);});
$router->get('/query/{page}', function ($data) use ($department){$department->query($data);});
$router->get('/filters/clean', function () use ($department){$department->clean();});

// Rotas para Produtos
$router->group('/product');
$router->get('/create', function () use ($product){$product->create();});
$router->get('/saved', function () use ($product){$product->search();});
$router->get('/edit/{id}', function ($data) use ($product){$product->view($data);});
$router->get('/edit/sketch/{id}', function ($data) use ($product){$product->sketch($data);});
$router->get('/view/sku/{sku}', function ($data) use ($product){$product->sku($data);});
$router->get('/search', function () use ($product){$product->index(["page" => 1]);});
$router->get('/search/{page}', function ($data) use ($product){$product->index($data);});
$router->get('/query', function ($data) use ($product){$product->query(["page" => 1]);});
$router->get('/query/{page}', function ($data) use ($product){$product->query($data);});
$router->get('/filters/clean', function () use ($product){$product->clean();});
$router->post('/availability/product/', function ($data) use ($product){$product->availability($data);});
$router->post('/persistDB', function ($data) use ($product){$product->persistDB($data);});
$router->post('/publish', function ($data) use ($product){$product->publish($data);});
$router->put('/persistDB', function ($data) use ($product){$product->persistDB($data);});
$router->put('/persist', function ($data) use ($product){$product->persist($data);});
$router->delete('/delete', function ($data) use ($product){$product->delete($data);});

// Rotas para Variações de Produtos
$router->group('/variations');
$router->get('/view/{productId}/{variationId}', function ($data) use ($variation){$variation->view($data);});
$router->get('/edit/{id}', function ($data) use ($variation){$variation->edit($data);});
$router->post('/persist', function ($data) use ($variation){$variation->persist($data);});
$router->put('/persist', function ($data) use ($variation){$variation->persist($data);});
$router->delete('/delete', function ($data) use ($variation){$variation->delete($data);});

// Rotas de Images
$router->group('/image');
$router->post('/persist', function ($data) use ($image){$image->persist($data);});
$router->put('/persist', function ($data) use ($image){$image->persist($data);});
$router->delete('/delete', function ($data) use ($image){$image->delete($data);});

// Rotas para Informações Adicionais
$router->group('/info');
$router->post('/persist', function ($data) use ($info){$info->persist($data);});
$router->put('/persist', function ($data) use ($info){$info->persist($data);});

// Rotas para Grupo de Produtos
$router->group('/group');
$router->get('/', function () use ($group){$group->index();});
$router->get('/{id}', function ($data) use ($group){$group->group($data);});
$router->post('/persist', function ($data) use ($group){$group->persist($data);});
$router->post('/complete', function ($data) use ($group){$group->complete($data);});
$router->post('/bond', function ($data) use ($group){$group->bond($data);});
$router->put('/persist', function ($data) use ($group){$group->persist($data);});
$router->delete('/delete', function ($data) use ($group){$group->delete($data);});
$router->delete('/product', function ($data) use ($group){$group->remove($data);});

// Rotas para Controle de Stock
$router->group('/stock');
$router->get('/', function () use ($stock){$stock->index(["page" => 1]);});
$router->get('/{page}', function ($data) use ($stock){$stock->index($data);});
$router->get('/query', function ($data) use ($stock){$stock->query(["page" => 1]);});
$router->get('/query/{page}', function ($data) use ($stock){$stock->query($data);});
$router->get('/filters/clean', function () use ($stock){$stock->clean();});
$router->post('/persist', function ($data) use ($stock){$stock->persist($data);});
$router->delete('/delete', function ($data) use ($stock){$stock->delete($data);});

// Rotas para Registrar Ocorrências
$router->group('/record');
$router->post('/persist', function ($data) use ($record){$record->persist($data);});

// Rotas para Pedidos
$router->group('/orders');
$router->get('/', function () use ($order){$order->index(["page" => 1]);});
$router->get('/{page}', function ($data) use ($order){$order->index($data);});
$router->get('/search', function ($data) use ($order){$order->search(["page" => 1]);});
$router->get('/search/{page}', function ($data) use ($order){$order->search($data);});
$router->get('/view/{id}', function ($data) use ($order){$order->view($data);});
$router->get('/filters/clean', function () use ($order){$order->clean();});
$router->put('/persist', function ($data) use ($order){$order->persist($data);});

// Rotas para Pedidos
$router->group('/print');
$router->get('/nota/{id}', function ($data) use ($print){$print->nota($data);});
$router->get('/conteudo/{id}', function ($data) use ($print){$print->conteudo($data);});

// Rotas para Setor de Criação
$router->group('/create');
$router->get('/', function () use ($creator){$creator->index(["page" => 1]);});
$router->get('/{page}', function ($data) use ($creator){$creator->index($data);});
$router->get('/search', function ($data) use ($creator){$creator->search(["page" => 1]);});
$router->get('/search/{page}', function ($data) use ($creator){$creator->search($data);});
$router->get('/view/{id}', function ($data) use ($creator){$creator->view($data);});
$router->get('/filters/clean', function () use ($creator){$creator->clean();});
$router->put('/persist', function ($data) use ($creator){$creator->persist($data);});

// Rotas para Setor de Pré-Impressão
$router->group('/prepress');
$router->get('/', function () use ($prepress){$prepress->index(["page" => 1]);});
$router->get('/{page}', function ($data) use ($prepress){$prepress->index($data);});
$router->get('/search', function ($data) use ($prepress){$prepress->search(["page" => 1]);});
$router->get('/search/{page}', function ($data) use ($prepress){$prepress->search($data);});
$router->get('/view/{id}', function ($data) use ($prepress){$prepress->view($data);});
$router->get('/filters/clean', function () use ($prepress){$prepress->clean();});
$router->put('/persist', function ($data) use ($prepress){$prepress->persist($data);});

// Rotas para Setor de Impressão
$router->group('/press');
$router->get('/', function () use ($press){$press->index(["page" => 1]);});
$router->get('/{page}', function ($data) use ($press){$press->index($data);});
$router->get('/search', function ($data) use ($press){$press->search(["page" => 1]);});
$router->get('/search/{page}', function ($data) use ($press){$press->search($data);});
$router->get('/view/{id}', function ($data) use ($press){$press->view($data);});
$router->get('/filters/clean', function () use ($press){$press->clean();});
$router->put('/persist', function ($data) use ($press){$press->persist($data);});

// Rotas para Setor de Quadros
$router->group('/frames');
$router->get('/', function () use ($frames){$frames->index(["page" => 1]);});
$router->get('/{page}', function ($data) use ($frames){$frames->index($data);});
$router->get('/search', function ($data) use ($frames){$frames->search(["page" => 1]);});
$router->get('/search/{page}', function ($data) use ($frames){$frames->search($data);});
$router->get('/view/{id}', function ($data) use ($frames){$frames->view($data);});
$router->get('/filters/clean', function () use ($frames){$frames->clean();});
$router->put('/persist', function ($data) use ($frames){$frames->persist($data);});

// Rotas para Setor de Acabamento
$router->group('/finishing');
$router->get('/', function () use ($finishing){$finishing->index(["page" => 1]);});
$router->get('/{page}', function ($data) use ($finishing){$finishing->index($data);});
$router->get('/search', function ($data) use ($finishing){$finishing->search(["page" => 1]);});
$router->get('/search/{page}', function ($data) use ($finishing){$finishing->search($data);});
$router->get('/view/{id}', function ($data) use ($finishing){$finishing->view($data);});
$router->get('/filters/clean', function () use ($finishing){$finishing->clean();});
$router->put('/persist', function ($data) use ($finishing){$finishing->persist($data);});

// Rotas para Setor de Expedição
$router->group('/expedition');
$router->get('/', function () use ($expedition){$expedition->index(["page" => 1]);});
$router->get('/{page}', function ($data) use ($expedition){$expedition->index($data);});
$router->get('/search', function ($data) use ($expedition){$expedition->search(["page" => 1]);});
$router->get('/search/{page}', function ($data) use ($expedition){$expedition->search($data);});
$router->get('/view/{id}', function ($data) use ($expedition){$expedition->view($data);});
$router->get('/filters/clean', function () use ($expedition){$expedition->clean();});
$router->put('/persist', function ($data) use ($expedition){$expedition->persist($data);});

// Rotas de Helper
$router->group('/helper');
$router->post('/sanitizar', function ($data) use ($helper){$helper->caracteres_especiais($data);});

// Redirect de segurança, caso não tenha sessão ativa
$path = $_SERVER['QUERY_STRING'];
$admin = stripos($path, 'dashboard');
if (!isset($_SESSION['logado']) && $admin > 1) {
    $router->redirect("/admin");
    exit();
}

// Rotas para Tratamento de Erros
$router->group('/error');
$router->get('/{errcode}', function ($data){
    echo "Erro: {$data['errcode']}";
});

$router->dispatch();

// Redirect de todos os erros de rotas
if ($router->error()) {
    $router->redirect("/error/{$router->error()}");
}