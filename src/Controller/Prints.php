<?php

namespace Template\Controller;

use League\Plates\Engine;
use Template\Helper\Nota;
use CoffeeCode\Router\Router;
use Template\Helper\FlashMessageTrait;
use Picqer\Barcode\BarcodeGeneratorHTML;

class Prints 
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

    public function nota($data)
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
            $this->nota($data);
            exit();
        }

        $order = $response->result[0];
        $nota = $this->requestNota($order)->result[0];

        echo $this->view->render('print', [
            'order' => $order,
            'nota' => $nota,
            'rastreio' => $this->generator->getBarcode($order->numeroRastreio, $this->generator::TYPE_CODE_128, 1, 20),
            'numero' => $this->generator->getBarcode($nota->numeroNota, $this->generator::TYPE_CODE_128, 1, 20),
            'chave' => $this->generator->getBarcode($nota->chaveNota, $this->generator::TYPE_CODE_128, 1, 20),
            'pedido' => $this->generator->getBarcode($order->codigo, $this->generator::TYPE_CODE_128, 1, 20),
            ]
        );
    }

    public function conteudo($data)
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
            $this->conteudo($data);
            exit();
        }

        $order = $response->result[0];
        $nota = $this->requestNota($order)->result[0];

        echo $this->view->render('conteudo', [
            'order' => $order,
            'nota' => $nota,
            'rastreio' => $this->generator->getBarcode($order->numeroRastreio, $this->generator::TYPE_CODE_128, 1, 20),
            'pedido' => $this->generator->getBarcode($order->codigo, $this->generator::TYPE_CODE_128, 1, 20),
            ]
        );
    }
    
}