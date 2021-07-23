<?php

namespace Template\Controller;

use DateTime;
use League\Plates\Engine;
use Template\Entity\Record;
use CoffeeCode\Router\Router;
use CoffeeCode\Paginator\Paginator;
use Template\Helper\FlashMessageTrait;
use Doctrine\ORM\EntityManagerInterface;
use Template\Entity\Contributor;
use Template\Entity\Product;

class Creator 
{
    use FlashMessageTrait;
    
    private $view;
    private $router;
    private $curl;
    private $token;
    private $entityManager;
    private $repository;
    private $repositoryUser;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->view = new Engine(__DIR__ . '/../../views/');
        $this->router = new Router(URL_BASE);
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Record::class);
        $this->repositoryUser = $entityManager->getRepository(Contributor::class);
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
    
    public function index($data)
    {
        $page = filter_var(
            $data['page'],
            FILTER_SANITIZE_STRIPPED
        );
        
        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => "http://api.ideris.com.br/ListaPedido?limit=10&Status=".htmlentities(urlencode('Criação')),
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
        
        $paginator = new Paginator(URL_BASE . "create/", '', ["Primeira Página", "Primeira"], ["Última Página", "Última"]);
        $paginator->pager($response->paging->total, 10, $page, 2);
        
        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => "http://api.ideris.com.br/ListaPedido?offset={$paginator->offset()}&limit={$paginator->limit()}&Status=".htmlentities(urlencode('Criação')),
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
        echo $this->view->render('pedidos', [
            'orders' => $response,
            'paginator' => $paginator,
            'url' => 'create',
            'marketplaces' => $this->marketplaces(),
            ]
        );
    }
    
    public function search($data)
    {
        $page = filter_var(
            $data['page'],
            FILTER_SANITIZE_STRIPPED
        );
        
        $url = "http://api.ideris.com.br/ListaPedido?limit=10&Status=".htmlentities(urlencode('Criação'));
        
        if (isset($_GET['dataFinal'])) { 
            $_SESSION['search'] = [
                'dataInicial' => $_GET['dataInicial'],
                'dataFinal' => $_GET['dataFinal'],
                'marketplace' => (isset($_GET['marketplace'])) ? $_GET['marketplace'] : '',
                'codigoCarrinhoCompras' => $_GET['codigoCarrinhoCompras'],
            ];
            if (!empty($_GET['dataInicial'])) {
                $dataInicial = new DateTime($_GET['dataInicial']);
                $dataInicial = $dataInicial->format('Y-m-d\TH:i:sP');
                $url .= "&dataInicial=$dataInicial";
            }
            if (!empty($_GET['dataFinal'])) {
                $dataFinal = new DateTime($_GET['dataFinal']);
                $dataFinal = $dataFinal->format('Y-m-d\TH:i:sP');
                $url .= "&dataFinal=$dataFinal";
            }
            $url .= (!empty($_GET['marketplace'])) ? "&marketplace=". htmlentities(urlencode($_GET['marketplace'])) : '';
            $url .= (!empty($_GET['codigoCarrinhoCompras'])) ? "&codigoCarrinhoCompras=$_GET[codigoCarrinhoCompras]" : '';
        }
        
        if (isset($_SESSION['search']) && !isset($_GET['dataFinal'])) { 
            if (!empty($_SESSION['search']['dataInicial'])) {
                $dataInicial = new DateTime($_SESSION['search']['dataInicial']);
                $dataInicial = $dataInicial->format('Y-m-d\TH:i:sP');
                $url .= "&dataInicial=$dataInicial";
            }
            if (!empty($_SESSION['search']['dataFinal'])) {
                $dataFinal = new DateTime($_SESSION['search']['dataFinal']);
                $dataFinal = $dataFinal->format('Y-m-d\TH:i:sP');
                $url .= "&dataFinal=$dataFinal";
            }
            $url .= (!empty($_SESSION['search']['marketplace'])) ? "&marketplace=". htmlentities(urlencode($_SESSION['search']['marketplace'])) : '';
            $url .= (!empty($_SESSION['search']['codigoCarrinhoCompras'])) ? "&codigoCarrinhoCompras=$_SESSION[search][codigoCarrinhoCompras]" : '';
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
            $this->search($data);
            exit();
        }
        
        curl_close($this->curl);
        
        $paginator = new Paginator(URL_BASE . "create/search/", '', ["Primeira Página", "Primeira"], ["Última Página", "Última"]);
        $paginator->pager($response->paging->total, 10, $page, 2);
        
        $url = "http://api.ideris.com.br/ListaPedido?offset={$paginator->offset()}&limit={$paginator->limit()}&Status=".htmlentities(urlencode('Criação'));
        
        if (isset($_GET['dataFinal'])) { 
            if (!empty($_GET['dataInicial'])) {
                $dataInicial = new DateTime($_GET['dataInicial']);
                $dataInicial = $dataInicial->format('Y-m-d\TH:i:sP');
                $url .= "&dataInicial=$dataInicial";
            }
            if (!empty($_GET['dataFinal'])) {
                $dataFinal = new DateTime($_GET['dataFinal']);
                $dataFinal = $dataFinal->format('Y-m-d\TH:i:sP');
                $url .= "&dataFinal=$dataFinal";
            }
            $url .= (!empty($_GET['marketplace'])) ? "&marketplace=". htmlentities(urlencode($_GET['marketplace'])) : '';
            $url .= (!empty($_GET['codigoCarrinhoCompras'])) ? "&codigoCarrinhoCompras=$_GET[codigoCarrinhoCompras]" : '';
        }
        
        if (isset($_SESSION['search']) && !isset($_GET['dataFinal'])) { 
            if (!empty($_SESSION['search']['dataInicial'])) {
                $dataInicial = new DateTime($_SESSION['search']['dataInicial']);
                $dataInicial = $dataInicial->format('Y-m-d\TH:i:sP');
                $url .= "&dataInicial=$dataInicial";
            }
            if (!empty($_SESSION['search']['dataFinal'])) {
                $dataFinal = new DateTime($_SESSION['search']['dataFinal']);
                $dataFinal = $dataFinal->format('Y-m-d\TH:i:sP');
                $url .= "&dataFinal=$dataFinal";
            }
            $url .= (!empty($_SESSION['search']['marketplace'])) ? "&marketplace=". htmlentities(urlencode($_SESSION['search']['marketplace'])) : '';
            $url .= (!empty($_SESSION['search']['codigoCarrinhoCompras'])) ? "&codigoCarrinhoCompras=$_SESSION[search][codigoCarrinhoCompras]" : '';
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
            $this->search($data);
            exit();
        }
        
        curl_close($this->curl);
        echo $this->view->render('pedidos', [
            'orders' => $response,
            'paginator' => $paginator,
            'url' => 'create',
            'marketplaces' => $this->marketplaces(),
            ]
        );
    }
    
    private function marketplaces()
    {
        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => 'http://api.ideris.com.br/Marketplace',
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
        
        curl_close($this->curl);
        return json_decode($response);
    }
    
    public function clean()
    {
        unset($_SESSION['search']);
        $this->router->redirect("/create");
    }
    
    public function view($data)
    {
        $id = filter_var($data['id'], FILTER_VALIDATE_INT);
        
        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => "http://api.ideris.com.br/Pedido/$id",
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

        $status = [
            0 => [
                'id' => 1220,
                'descricao' => 'Criação',
            ],
            1 => [
                'id' => 1219,
                'descricao' => 'Pré-Impressão',
            ],
            2 => [
                'id' => 1223,
                'descricao' => 'Impressão',
            ],
            3 => [
                'id' => 1218,
                'descricao' => 'Quadros',
            ],
            4 => [
                'id' => 1118,
                'descricao' => 'Acabamento',
            ],
            5 => [
                'id' => 1009,
                'descricao' => 'Expedição',
            ]
        ];
        
        curl_close($this->curl);
        echo $this->view->render('create', [
            'order' => $response->result[0],
            'status' => $status,
            'contributors' => $this->repositoryUser->findAll(),
            'record' => $this->repository->findBy(['request' => $id]),
            'url' => 'create'
            ]
        );
    }

    public function persist($data)
    {

        $this->curl = curl_init();

        curl_setopt_array($this->curl, array(
            CURLOPT_URL => 'http://api.ideris.com.br/PedidoStatus',
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
            $this->defineMensagem('success', "Status do Pedido alterado com sucesso!");
            $this->router->redirect("$_SERVER[HTTP_REFERER]");
            return false;
        }

        $this->defineMensagem('error', "Não foi possivel alterar o status do pedido! Erro: $response");
        $this->router->redirect("$_SERVER[HTTP_REFERER]");
    }
    
}