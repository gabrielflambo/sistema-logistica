<?php

namespace Template\Controller;

use DateTime;
use League\Plates\Engine;
use CoffeeCode\Router\Router;
use CoffeeCode\Paginator\Paginator;
use Template\Helper\FlashMessageTrait;
use Doctrine\ORM\EntityManagerInterface;
use Template\Controller\Variations as ControllerVariations;
use Template\Entity\Bond;
use Template\Entity\Image;
use Template\Entity\Info;
use Template\Entity\Product;
use Template\Entity\Stock;
use Template\Entity\Team;
use Template\Entity\Variations;

class Products 
{
    use FlashMessageTrait;
    
    private $view;
    private $router;
    private $curl;
    private $token;
    private $repository;
    private $repositoryTeam;
    private $repositoryBond;
    private $repositoryStock;
    private $repositoryImage;
    private $repositoryInfo;
    private $repositoryVariations;
    private $controller;
    private $bond;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->view = new Engine(__DIR__ . '/../../views/');
        $this->router = new Router(URL_BASE);
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Product::class);
        $this->repositoryTeam = $entityManager->getRepository(Team::class);
        $this->repositoryBond = $entityManager->getRepository(Bond::class);
        $this->repositoryStock = $entityManager->getRepository(Stock::class);
        $this->repositoryImage = $entityManager->getRepository(Image::class);
        $this->repositoryInfo = $entityManager->getRepository(Info::class);
        $this->repositoryVariations = $entityManager->getRepository(Variations::class);
        $this->controller = new ControllerVariations($entityManager);
        $this->bond = new Groups($entityManager);

        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => 'http://api.ideris.com.br/Login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "login_token": "28852fbb1bc269e81478093ec85b1c42fb8674b71927734a46c664f8a60c84cada01937e5af54625afb7a3807192cecd"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($this->curl);
        
        curl_close($this->curl);
        $this->token = json_decode($response);
    }

    public function create()
    {
        echo $this->view->render('criar-produto', [
            'category' => $this->category(),
            'subcategory' => $this->subcategory(),
            'brand' => $this->brand(),
            'department' => $this->department()
        ]);
    }
    
    public function index($data)
    {
        $page = filter_var(
            $data['page'],
            FILTER_SANITIZE_STRIPPED
        );
        
        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => "http://api.ideris.com.br/Produto/?limit=10",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: {$this->token}",
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($this->curl);
        $response = json_decode($response);

        if(is_null($response)){
            sleep(5);
            $this->index($data);
            exit();
        }
        
        curl_close($this->curl);
        
        $paginator = new Paginator(URL_BASE . "product/search/", '', ["Primeira Página", "Primeira"], ["Última Página", "Última"]);
        $paginator->pager($response->paging->total, 10, $page, 2);
        
        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => "http://api.ideris.com.br/Produto/?offset={$paginator->offset()}&limit={$paginator->limit()}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: {$this->token}",
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($this->curl);
        $response = json_decode($response);

        if(is_null($response)){
            sleep(5);
            $this->index($data);
            exit();
        }
        
        curl_close($this->curl);
        echo $this->view->render('todos-os-produtos', [
            'products' => $response,
            'paginator' => $paginator,
            'groups' => $this->repositoryTeam->findAll(),
            'bond' => $this->repositoryBond->findAll()
            ]
        );
    }

    public function query($data)
    {
        $page = filter_var(
            $data['page'],
            FILTER_SANITIZE_STRIPPED
        );
        
        $url = "http://api.ideris.com.br/Produto?limit=10";
        
        if (isset($_GET['sku'])) { 
            $_SESSION['search'] = [
                'sku' => $_GET['sku'],
            ];
            $url .= (!empty($_GET['sku'])) ? "&sku=" . htmlentities(urlencode($_GET['sku'])) : '';
        }
        
        if (isset($_SESSION['search']) && !isset($_GET['sku'])) { 
            $url .= (!empty($_SESSION['search']['sku'])) ? "&sku=" . htmlentities(urlencode($_SESSION['search']['sku'])) : '';
        }
        
        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: {$this->token}",
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($this->curl);
        $response = json_decode($response);

        if(is_null($response)){
            sleep(5);
            $this->query($data);
            exit();
        }
        
        curl_close($this->curl);
        
        $paginator = new Paginator(URL_BASE . "product/query/", '', ["Primeira Página", "Primeira"], ["Última Página", "Última"]);
        $paginator->pager($response->paging->total, 10, $page, 2);
        
        $url = "http://api.ideris.com.br/Produto?limit=10";
        
        if (isset($_GET['sku'])) { 
            $url .= (!empty($_GET['sku'])) ? "&sku=" . htmlentities(urlencode($_GET['sku'])) : '';
        }
        
        if (isset($_SESSION['search']) && !isset($_GET['sku'])) { 
            $url .= (!empty($_SESSION['search']['sku'])) ? "&sku=" . htmlentities(urlencode($_SESSION['search']['sku'])) : '';
        }
        
        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: {$this->token}",
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($this->curl);
        $response = json_decode($response);

        if(is_null($response)){
            sleep(5);
            $this->query($data);
            exit();
        }
        
        curl_close($this->curl);
        echo $this->view->render('todos-os-produtos', [
            'products' => $response,
            'paginator' => $paginator,
            'groups' => $this->repositoryTeam->findAll(),
            'bond' => $this->repositoryBond->findAll()
            ]
        );
    }

    public function clean()
    {
        unset($_SESSION['search']);
        $this->router->redirect("/product/search");
    }
    
    public function view($data)
    {
        $id = filter_var($data['id'], FILTER_VALIDATE_INT);
        
        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => "http://api.ideris.com.br/Produto/$id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: {$this->token}",
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($this->curl);
        $response = json_decode($response);

        if(is_null($response)){
            sleep(5);
            $this->view($data);
            exit();
        }
        
        curl_close($this->curl);
        echo $this->view->render('produtos', [
            'product' => $response->result[0],
            'entrace' => $this->repositoryStock->findBy(['product' => $response->result[0]->sku, 'type' => 1]),
            'out' => $this->repositoryStock->findBy(['product' => $response->result[0]->sku, 'type' => 2]),
            'category' => $this->category(),
            'subcategory' => $this->subcategory(),
            'brand' => $this->brand(),
            'department' => $this->department(),
            'groups' => $this->repositoryTeam->findAll(),
            'bond' => $this->repositoryBond->findOneBy(['sku' => $response->result[0]->sku])
            ]
        );
    }

    public function sku($data)
    {
        $sku = htmlentities(urlencode($data['sku']));
        
        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => "http://api.ideris.com.br/Produto?sku=$sku",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: {$this->token}",
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($this->curl);
        $response = json_decode($response);

        if(is_null($response)){
            sleep(5);
            $this->sku($data);
            exit();
        }
        
        curl_close($this->curl);

        if (!empty($response->result)) {
            echo $this->view->render('produtos', [
                'product' => $response->result[0],
                'entrace' => $this->repositoryStock->findBy(['product' => $response->result[0]->sku, 'type' => 1]),
                'out' => $this->repositoryStock->findBy(['product' => $response->result[0]->sku, 'type' => 2]),
                'category' => $this->category(),
                'subcategory' => $this->subcategory(),
                'brand' => $this->brand(),
                'department' => $this->department(),
                'groups' => $this->repositoryTeam->findAll(),
                'bond' => $this->repositoryBond->findOneBy(['sku' => $response->result[0]->sku])
                ]
            );
        } else {
            echo $this->view->render('produtos', [
                'product' => [],
                ]
            );
        }
    }

    private function category()
    {
        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => "http://api.ideris.com.br/Categoria",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: {$this->token}",
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($this->curl);
        $response = json_decode($response);
        
        curl_close($this->curl);
        return $response;
    }

    private function subcategory()
    {
        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => "http://api.ideris.com.br/SubCategoria",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: {$this->token}",
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($this->curl);
        $response = json_decode($response);
        
        curl_close($this->curl);
        return $response;
    }

    private function brand()
    {
        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => "http://api.ideris.com.br/Marca",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: {$this->token}",
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($this->curl);
        $response = json_decode($response);
        
        curl_close($this->curl);
        return $response;
    }

    private function department()
    {
        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => "http://api.ideris.com.br/Departamento",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: {$this->token}",
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($this->curl);
        $response = json_decode($response);
        
        curl_close($this->curl);
        return $response;
    }

    public function persist($data)
    {

        $bond = $this->repositoryBond->findOneBy(['sku' => $data['sku']]);

        if(empty($bond) && $data['team'] != ''){
            $product = $this->request(['id' => $data['id']]);
            $this->bond->bond([
                'group' => $data['team'],
                'image' => $product->caminhoImagem,
                'sku' => $product->sku
            ]);
            unset($data['team']);
        } elseif(!empty($bond)){
            $this->bond->remove(['id' => $data['id']]);
            if ($data['tema'] != '') {
                $product = $this->request(['id' => $data['id']]);
                $this->bond->bond([
                    'group' => $data['team'],
                    'image' => $product->caminhoImagem,
                    'sku' => $product->sku
                ]);
            }
            unset($data['team']);
        }

        $this->curl = curl_init();

        curl_setopt_array($this->curl, array(
            CURLOPT_URL => 'http://api.ideris.com.br/Produto',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Authorization: {$this->token}",
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($this->curl);
        $response = json_decode($response);

        curl_close($this->curl);

        if (isset($response->result)) {
            $this->defineMensagem('success', "Produto alterado com sucesso!");
            $this->router->redirect("$_SERVER[HTTP_REFERER]");
            return false;
        }

        $this->defineMensagem('error', "Não foi possivel alterar o produto! Erro: $response");
        $this->router->redirect("$_SERVER[HTTP_REFERER]");
    }

    public function request($data)
    {
        $id = filter_var($data['id'], FILTER_VALIDATE_INT);
        
        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => "http://api.ideris.com.br/Produto/$id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: {$this->token}",
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($this->curl);
        $response = json_decode($response);

        return $response->result[0];
    }

    public function update($data)
    {
        $this->curl = curl_init();

        curl_setopt_array($this->curl, array(
            CURLOPT_URL => 'http://api.ideris.com.br/Produto',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Authorization: {$this->token}",
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($this->curl);
        $response = json_decode($response);

        curl_close($this->curl);
    }

    public function persistDB($data)
    {
        if (isset($data['id'])) {
            $id = filter_var(
                $data['id'],
                FILTER_VALIDATE_INT
            );
        }

        $titulo = filter_var($data['titulo'], FILTER_SANITIZE_STRING);
        $valorVenda = filter_var($data['valorVenda'], FILTER_SANITIZE_STRING);
        $valorCusto = filter_var($data['valorCusto'], FILTER_SANITIZE_STRING);
        $sku = filter_var($data['sku'], FILTER_SANITIZE_STRING);
        $peso = filter_var($data['peso'], FILTER_SANITIZE_STRING);
        $altura = filter_var($data['altura'], FILTER_SANITIZE_STRING);
        $largura = filter_var($data['largura'], FILTER_SANITIZE_STRING);
        $comprimento = filter_var($data['comprimento'], FILTER_SANITIZE_STRING);
        $pesoEmbalagem = filter_var($data['pesoEmbalagem'], FILTER_SANITIZE_STRING);
        $alturaEmbalagem = filter_var($data['alturaEmbalagem'], FILTER_SANITIZE_STRING);
        $larguraEmbalagem = filter_var($data['larguraEmbalagem'], FILTER_SANITIZE_STRING);
        $comprimentoEmbalagem = filter_var($data['comprimentoEmbalagem'], FILTER_SANITIZE_STRING);
        $categoriaIdIderis = filter_var($data['categoriaIdIderis'], FILTER_VALIDATE_INT);
        $subCategoriaIdIderis = filter_var($data['subCategoriaIdIderis'], FILTER_VALIDATE_INT);
        $marcaIdIderis = filter_var($data['marcaIdIderis'], FILTER_VALIDATE_INT);
        $departamentoIdIderis = filter_var($data['departamentoIdIderis'], FILTER_VALIDATE_INT);

        
        $product = new Product();
        (isset($data['id'])) ? $product->setId($id) : '';
        $product->setTitulo($titulo);
        $product->setValorVenda($valorVenda);
        $product->setValorCusto($valorCusto);
        $product->setSku($sku);
        $product->setPeso($peso);
        $product->setAltura($altura);
        $product->setLargura($largura);
        $product->setComprimento($comprimento);
        $product->setPesoEmbalagem($pesoEmbalagem);
        $product->setAlturaEmbalagem($alturaEmbalagem);
        $product->setLarguraEmbalagem($larguraEmbalagem);
        $product->setComprimentoEmbalagem($comprimentoEmbalagem);
        $product->setCategoriaIdIderis($categoriaIdIderis);
        $product->setSubCategoriaIdIderis($subCategoriaIdIderis);
        $product->setMarcaIdIderis($marcaIdIderis);
        $product->setDepartamentoIdIderis($departamentoIdIderis);

        try {
            if(isset($data['id'])){
                $this->entityManager->merge($product);
                $this->entityManager->flush();
                $this->defineMensagem('success', "Produto Alterado com sucesso!");
                $this->router->redirect("/product/edit/sketch/{$id}");
            } else {
                $this->entityManager->persist($product);
                $this->entityManager->flush();
                $this->defineMensagem('success', "Produto Inserido com sucesso!");
                $this->router->redirect("/product/edit/sketch/{$product->getId()}");
            }
        } catch (\Throwable $th) {
            $this->defineMensagem('error', "Não foi possivel inserir produto. Erro: {$th->getMessage()}");
            $this->router->redirect("/product/create");
        }
    }

    public function sketch($data)
    {
        $id = filter_var(
            $data['id'],
            FILTER_VALIDATE_INT
        );

        echo $this->view->render('criar-produto', [
            'product' => $this->repository->findOneBy(['id' => $id]),
            'image' => $this->repositoryImage->findBy(['product' => $id, 'type' => 0]),
            'imageV' => $this->repositoryImage->findBy(['type' => 1]),
            'variation' => $this->repositoryVariations->findBy(['product' => $id]),
            'info' => $this->repositoryInfo->findOneBy(['product' => $id]),
            'category' => $this->category(),
            'subcategory' => $this->subcategory(),
            'brand' => $this->brand(),
            'department' => $this->department()
        ]);
    }

    public function search()
    {
        echo $this->view->render('produtos-rascunho', [
            'products' => $this->repository->findAll(),
            'image' => $this->repositoryImage->findBy(['type' => 0]),
        ]);
    }

    public function delete($data)
    {
        $id = filter_var(
            $data['id'],
            FILTER_VALIDATE_INT
        );

        $product = $this->repository->findOneBy(['id' => $id]);
        $images = $this->repositoryImage->findBy(['product' => $id, 'type' => 0]);
        $info = $this->repositoryInfo->findOneBy(['product' => $id]);
        $variations = $this->repositoryVariations->findBy(['product' => $product->getId()]);

        if (!empty($images)) {
            foreach ($images as $item) {
                $image = $this->repositoryImage->findOneBy(['id' => $item->getId()]);
                $path = 'public/images/images/';
                $img = explode('/', $image->getUrlImagem());
                $img = $img[count($img) - 3] .'/'. $img[count($img) - 2] .'/'. $img[count($img) - 1];
                unlink($path . $img);
                $this->entityManager->remove($image);
            }
        }

        if (!empty($variations)) {
            foreach ($variations as $item) {
                $this->controller->delete(['id' => $item->getId(), 'product' => $product->getId()]);
            }
        }

        if (!empty($info)) {
            $this->entityManager->remove($info);
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }

    public function publish($data)
    {
        $id = filter_var(
            $data['id'],
            FILTER_VALIDATE_INT
        );

        $this->curl = curl_init();

        curl_setopt_array($this->curl, array(
            CURLOPT_URL => 'http://api.ideris.com.br/Produto',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($this->hydrate($id)),
            CURLOPT_HTTPHEADER => array(
                "Authorization: {$this->token}",
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($this->curl);
        echo($response);
        curl_close($this->curl);
    }

    private function hydrate(int $id)
    {
        $product = $this->repository->findOneBy(['id' => $id]);
        $images = $this->repositoryImage->findBy(['product' => $id, 'type' => 0]);
        $info = $this->repositoryInfo->findOneBy(['product' => $id]);
        $variations = $this->repositoryVariations->findBy(['product' => $product->getId()]);

        $publish = [
            "sku" => $product->getSku(), //Código SKU do produto : string, obrigatório
            "titulo" => $product->getTitulo(), //Tíulo / Nome do produto : string, obrigatório
            "descricaoLonga" => (!empty($info)) ? $info->getDescricaoLonga() : null, //Descrição longa do produto : string, obrigatório
            "categoriaIdIderis" => $product->getCategoriaIdIderis(), //ID da categoria no Ideris : int, obrigatório
            "subCategoriaIdIderis" => $product->getSubCategoriaIdIderis(), //ID da sub categoria no Ideris : int, obrigatório
            "marcaIdIderis" => $product->getMarcaIdIderis(), //ID da marca no Ideris : int, obrigatório
            "departamentoIdIderis" => $product->getDepartamentoIdIderis(), //ID do departamento no Ideris : int, obrigatório
            "ncmId" => null, //ID do NCM no Ideris : int, nullable
            "produtoOrigemId" => null, //ID da origem do produto no Ideris : int, nullable
            "modelo" => null, //Modelo do produto : string
            "garantia" => null, //Garantia do produto : string
            "peso" => $product->getPeso(), //Peso do produto (em gramas) : decimal, nullable
            "comprimento" => $product->getComprimento(), //Comprimento do produto (em centímetros) : decimal, nullable
            "largura" => $product->getLargura(), //Largura do produto (em centímetros) : decimal, nullable
            "altura" => $product->getAltura(), //Altura do produto (em centímetros) : decimal, nullable
            "pesoEmbalagem" => $product->getPesoEmbalagem(), //Peso do produto embalado (em quilos) : decimal, nullable
            "comprimentoEmbalagem" => $product->getComprimentoEmbalagem(), //Comprimento do produto embalado (em metros) : decimal, nullable
            "larguraEmbalagem" => $product->getLarguraEmbalagem(), //Largura do produto embalado (em metros) : decimal, nullable
            "alturaEmbalagem" => $product->getAlturaEmbalagem(), //Altura do produto embalado (em metros) : decimal, nullable
            "cest" => null, //Código CEST do produto : string
            "ean" => null, //Código EAN do produto : string
            "valorCusto" => $product->getValorCusto(), //Valor de custo do produto : decimal, obrigatório
            "valorVenda" => $product->getValorVenda(), //Valor de venda do produto : decimal, obrigatório,
            "quantidadeEstoquePrincipal" => 1, //Quantidade de estoque principal do produto. Somente preencher quando for produto simples. : int, nullable
            "Imagem" => [], //Nó que agrupa as imagens do produto. É necessário informar pelo menos uma imagem  e no máximo dez imagens. Se houver variação as imagens inseridas aqui serão ignoradas. Não é necessário informar os 2 campos, porém se ambos forem informados levaremos em consideração o campo 'urlImagem';
            "Variacao" => [], //Nó que agrupa as variações. Somente preencher se for um produto com variação. Caso contrário deixar vazio. O número máximo de variações em um produto é 50 (cinquenta).
        ];

        if (!empty($images)) { 
            foreach ($images as $item) {
                $img = [
                    "urlImagem" => $item->getUrlImagem(), //URL da imagem da variação. Extensões aceitas: PNG, JPG e JPEG : string
                    "imagemBase64" => null //String contendo informações da extensão e Base64 da imagem. A string deve ser no seguinte formato: type:image/<Extensão da imagem (JPG, JPEG ou PNG)>;base64:<Base64 da imagem> : string
                ];
                array_push($publish['Imagem'], $img);
            }
        }

        if (!empty($variations)) {
            foreach ($variations as $item) { 
                $iten = [
                    "skuVariacao" => $item->getSkuVariacao(), //Código SKU da variação : string, obrigatório
                    "quantidadeVariacao" => $item->getQuantidadeVariacao(), //Quantidade de estoque da variação : int, obrigatório
                    "nomeAtributo" => $item->getNomeAtributo(), //Nome do atributo personalizado da variação. O nome do atributo deve ser o mesmo para todas as variações. Ex: Tamanho, Polegada : string, obrigatório
                    "valorAtributo" => $item->getValorAtributo(), //Valor do atributo da variação : string, obrigatorio
                    "Imagem" => [] //Nó que agrupa as imagens do produto. É necessário informar pelo menos uma imagem  e no máximo dez imagens. Se houver variação as imagens inseridas aqui serão ignoradas. Não é necessário informar os 2 campos, porém se ambos forem informados levaremos em consideração o campo 'urlImagem'
                ];
                $imageV = $this->repositoryImage->findBy(['product' => $item->getId(), 'type' => 1]);
                if (!empty($imageV)) {
                    foreach ($imageV as $value) {
                        $imgV = [
                            "urlImagem" => $value->getUrlImagem(), //URL da imagem da variação. Extensões aceitas: PNG, JPG e JPEG : string
                            "imagemBase64" => null //String contendo informações da extensão e Base64 da imagem. A string deve ser no seguinte formato: type:image/<Extensão da imagem (JPG, JPEG ou PNG)>;base64:<Base64 da imagem> : string
                        ];
                        array_push($iten['Imagem'], $imgV);
                    }
                }
                array_push($publish['Variacao'], $iten);
                $this->publishV(['id' => $item->getId()]);
            }
        }

        return $publish;
    }

    public function publishV($data)
    {
        $id = filter_var(
            $data['id'],
            FILTER_VALIDATE_INT
        );

        $this->curl = curl_init();

        curl_setopt_array($this->curl, array(
            CURLOPT_URL => 'http://api.ideris.com.br/Produto',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($this->hydrateV($id)),
            CURLOPT_HTTPHEADER => array(
                "Authorization: {$this->token}",
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($this->curl);
        curl_close($this->curl);
    }


    private function hydrateV(int $id)
    {
        $variations = $this->repositoryVariations->findOneBy(['product' => $id]);
        $product = $this->repository->findOneBy(['id' => $variations->getProduct()]);
        $images = $this->repositoryImage->findBy(['product' => $variations->getProduct(), 'type' => 1]);
        $info = $this->repositoryInfo->findOneBy(['product' => $variations->getProduct()]);

        $publish = [
            "sku" => $variations->getSkuVariacao() . '-duplicado', //Código SKU do produto : string, obrigatório
            "titulo" => $product->getTitulo(), //Tíulo / Nome do produto : string, obrigatório
            "descricaoLonga" => (!empty($info)) ? $info->getDescricaoLonga() : null, //Descrição longa do produto : string, obrigatório
            "categoriaIdIderis" => $product->getCategoriaIdIderis(), //ID da categoria no Ideris : int, obrigatório
            "subCategoriaIdIderis" => $product->getSubCategoriaIdIderis(), //ID da sub categoria no Ideris : int, obrigatório
            "marcaIdIderis" => $product->getMarcaIdIderis(), //ID da marca no Ideris : int, obrigatório
            "departamentoIdIderis" => $product->getDepartamentoIdIderis(), //ID do departamento no Ideris : int, obrigatório
            "ncmId" => null, //ID do NCM no Ideris : int, nullable
            "produtoOrigemId" => null, //ID da origem do produto no Ideris : int, nullable
            "modelo" => null, //Modelo do produto : string
            "garantia" => null, //Garantia do produto : string
            "peso" => $product->getPeso(), //Peso do produto (em gramas) : decimal, nullable
            "comprimento" => $product->getComprimento(), //Comprimento do produto (em centímetros) : decimal, nullable
            "largura" => $product->getLargura(), //Largura do produto (em centímetros) : decimal, nullable
            "altura" => $product->getAltura(), //Altura do produto (em centímetros) : decimal, nullable
            "pesoEmbalagem" => $product->getPesoEmbalagem(), //Peso do produto embalado (em quilos) : decimal, nullable
            "comprimentoEmbalagem" => $product->getComprimentoEmbalagem(), //Comprimento do produto embalado (em metros) : decimal, nullable
            "larguraEmbalagem" => $product->getLarguraEmbalagem(), //Largura do produto embalado (em metros) : decimal, nullable
            "alturaEmbalagem" => $product->getAlturaEmbalagem(), //Altura do produto embalado (em metros) : decimal, nullable
            "cest" => null, //Código CEST do produto : string
            "ean" => null, //Código EAN do produto : string
            "valorCusto" => $product->getValorCusto(), //Valor de custo do produto : decimal, obrigatório
            "valorVenda" => $product->getValorVenda(), //Valor de venda do produto : decimal, obrigatório,
            "quantidadeEstoquePrincipal" => 1, //Quantidade de estoque principal do produto. Somente preencher quando for produto simples. : int, nullable
            "Imagem" => [], //Nó que agrupa as imagens do produto. É necessário informar pelo menos uma imagem  e no máximo dez imagens. Se houver variação as imagens inseridas aqui serão ignoradas. Não é necessário informar os 2 campos, porém se ambos forem informados levaremos em consideração o campo 'urlImagem';
            "Variacao" => [], //Nó que agrupa as variações. Somente preencher se for um produto com variação. Caso contrário deixar vazio. O número máximo de variações em um produto é 50 (cinquenta).
        ];

        if (!empty($images)) { 
            foreach ($images as $item) {
                $img = [
                    "urlImagem" => $item->getUrlImagem(), //URL da imagem da variação. Extensões aceitas: PNG, JPG e JPEG : string
                    "imagemBase64" => null //String contendo informações da extensão e Base64 da imagem. A string deve ser no seguinte formato: type:image/<Extensão da imagem (JPG, JPEG ou PNG)>;base64:<Base64 da imagem> : string
                ];
                array_push($publish['Imagem'], $img);
            }
        }

        return $publish;
    }

    public function availability($data)
    {
        $product = $this->skuPrivate($data);
        $productSaved = $this->repository->findOneBy(['sku' => $data['sku']]);

        if(!empty($product->result)){
            print_r(json_encode($product->result));
        } elseif(empty($product->result) && !empty($productSaved)){
            $productSaved = [
                'id' => $productSaved->getId(),
                'titulo' => $productSaved->getTitulo(),
                'sku' => $productSaved->getSku(),
                'peso' => $productSaved->getPeso(),
                'altura' => $productSaved->getAltura(),
                'largura' => $productSaved->getLargura(),
                'comprimento' => $productSaved->getComprimento(),
            ];
            print_r(json_encode($productSaved));
        } else {
            print_r(null);
        }
    }

    private function skuPrivate($data)
    {
        $sku = htmlentities(urlencode($data['sku']));
        
        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => "http://api.ideris.com.br/Produto?sku=$sku&limit=1",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: {$this->token}",
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($this->curl);
        $response = json_decode($response);
        return $response;
    }
    
}