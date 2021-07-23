<?php

namespace Template\Controller;

use DateTime;
use League\Plates\Engine;
use Template\Helper\Nota;
use CoffeeCode\Router\Router;
use CoffeeCode\Paginator\Paginator;
use Template\Helper\FlashMessageTrait;
use Picqer\Barcode\BarcodeGeneratorHTML;

class Order 
{
    use FlashMessageTrait;
    use Nota;
    
    private $view;
    private $router;
    private $curl;
    private $token;
    private $generator;
    
    public function __construct()
    {
        $this->view = new Engine(__DIR__ . '/../../views/');
        $this->router = new Router(URL_BASE);
        $this->generator = new BarcodeGeneratorHTML();
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
            CURLOPT_URL => "http://api.ideris.com.br/ListaPedido?limit=10",
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

        curl_close($this->curl);

        if(is_null($response)){
            sleep(5);
            $this->index($data);
            exit();
        }
        
        $paginator = new Paginator(URL_BASE . "orders/", '', ["Primeira Página", "Primeira"], ["Última Página", "Última"]);
        $paginator->pager($response->paging->total, 10, $page, 2);
        
        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => "http://api.ideris.com.br/ListaPedido?offset={$paginator->offset()}&limit={$paginator->limit()}",
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

        if(is_null($response)){
            sleep(5);
            $this->index($data);
            exit();
        }

        echo $this->view->render('pedidos', [
            'orders' => $response,
            'paginator' => $paginator,
            'status' => $this->status(),
            'marketplaces' => $this->marketplaces(),
            'url' => 'orders'
            ]
        );
    }
    
    public function search($data)
    {
        $page = filter_var(
            $data['page'],
            FILTER_SANITIZE_STRIPPED
        );
        
        $url = "http://api.ideris.com.br/ListaPedido?limit=10";
        
        if (isset($_GET['dataFinal'])) { 
            $_SESSION['search'] = [
                'dataInicial' => $_GET['dataInicial'],
                'dataFinal' => $_GET['dataFinal'],
                'status' => (isset($_GET['status'])) ? $_GET['status'] : '',
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
            $url .= (!empty($_GET['status'])) ? "&status=" . htmlentities(urlencode($_GET['status'])) : '';
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
            $url .= (!empty($_SESSION['search']['status'])) ? "&status=" . htmlentities(urlencode($_SESSION['search']['status'])) : '';
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

        curl_close($this->curl);

        if(is_null($response)){
            sleep(5);
            $this->search($data);
            exit();
        }
        
        $paginator = new Paginator(URL_BASE . "orders/search/", '', ["Primeira Página", "Primeira"], ["Última Página", "Última"]);
        $paginator->pager($response->paging->total, 10, $page, 2);
        
        $url = "http://api.ideris.com.br/ListaPedido?offset={$paginator->offset()}&limit={$paginator->limit()}";
        
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
            $url .= (!empty($_GET['status'])) ? "&status=" . htmlentities(urlencode($_GET['status'])) : '';
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
            $url .= (!empty($_SESSION['search']['status'])) ? "&status=" . htmlentities(urlencode($_SESSION['search']['status'])) : '';
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

        curl_close($this->curl);

        if(is_null($response)){
            sleep(5);
            $this->search($data);
            exit();
        }
        
        echo $this->view->render('pedidos', [
            'orders' => $response,
            'paginator' => $paginator,
            'status' => $this->status(),
            'marketplaces' => $this->marketplaces(),
            'url' => 'orders'
            ]
        );
    }
    
    private function status()
    {
        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => 'http://api.ideris.com.br/Status',
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
        $this->router->redirect("/orders");
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
        
        curl_close($this->curl);

        if(is_null($response)){
            sleep(5);
            $this->view($data);
            exit();
        }

        echo $this->view->render('pedido', [
            'order' => $response->result[0],
            'status' => $this->status(),
            'nota' => $this->requestNota($response->result[0])
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