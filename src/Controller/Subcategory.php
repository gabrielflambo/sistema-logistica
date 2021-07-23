<?php

namespace Template\Controller;

use League\Plates\Engine;
use CoffeeCode\Router\Router;
use CoffeeCode\Paginator\Paginator;
use DateTime;
use Template\Helper\FlashMessageTrait;

class Subcategory 
{
    use FlashMessageTrait;
    
    private $view;
    private $router;
    private $curl;
    private $token;
    
    public function __construct()
    {
        $this->view = new Engine(__DIR__ . '/../../views/');
        $this->router = new Router(URL_BASE);
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

    public function create($data)
    {
        $this->curl = curl_init();

        curl_setopt_array($this->curl, array(
            CURLOPT_URL => 'http://api.ideris.com.br/SubCategoria',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
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
            $this->defineMensagem('success', "Subcategoria criada com sucesso!");
            $this->router->redirect("/subcategory");
            return false;
        }

        $this->defineMensagem('error', "Não foi possivel cadastrar a subcategoria!");
        $this->router->redirect("/subcategory");
    }
    
    public function index($data)
    {
        $page = filter_var(
            $data['page'],
            FILTER_SANITIZE_STRIPPED
        );
        
        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => "http://api.ideris.com.br/SubCategoria/?limit=10",
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
        
        $paginator = new Paginator(URL_BASE . "subcategory/", '', ["Primeira Página", "Primeira"], ["Última Página", "Última"]);
        $paginator->pager($response->paging->total, 10, $page, 2);
        
        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => "http://api.ideris.com.br/SubCategoria/?offset={$paginator->offset()}&limit={$paginator->limit()}",
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
        echo $this->view->render('subcategoria', [
            'subcategory' => $response,
            'paginator' => $paginator
            ]
        );
    }

    public function query($data)
    {
        $page = filter_var(
            $data['page'],
            FILTER_SANITIZE_STRIPPED
        );
        
        $url = "http://api.ideris.com.br/SubCategoria?limit=10";
        
        if (isset($_GET['query'])) { 
            $_SESSION['search'] = [
                'query' => $_GET['query'],
            ];
            $url .= (!empty($_GET['query'])) ? "&descricao=" . htmlentities(urlencode($_GET['query'])) : '';
        }
        
        if (isset($_SESSION['search']) && !isset($_GET['query'])) { 
            $url .= (!empty($_SESSION['search']['query'])) ? "&descricao=" . htmlentities(urlencode($_SESSION['search']['query'])) : '';
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

        $paginator = new Paginator(URL_BASE . "subcategory/query/", '', ["Primeira Página", "Primeira"], ["Última Página", "Última"]);
        $paginator->pager($response->paging->count, 10, $page, 2);
        
        $url = "http://api.ideris.com.br/SubCategoria?limit=10";
        
        if (isset($_GET['query'])) { 
            $url .= (!empty($_GET['query'])) ? "&descricao=" . htmlentities(urlencode($_GET['query'])) : '';
        }
        
        if (isset($_SESSION['search']) && !isset($_GET['query'])) { 
            $url .= (!empty($_SESSION['search']['query'])) ? "&descricao=" . htmlentities(urlencode($_SESSION['search']['query'])) : '';
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
        echo $this->view->render('subcategoria', [
            'subcategory' => $response,
            'paginator' => $paginator
            ]
        );
    }

    public function clean()
    {
        unset($_SESSION['search']);
        $this->router->redirect("/subcategory");
    }
    
}