<?php

namespace Template\Controller;

use Template\Entity\Bond;
use Template\Entity\Stock;
use Doctrine\ORM\EntityManagerInterface;

error_reporting(0);

class Routine 
{    
    private $curl;
    private $token;
    private $product;
    private $entityManager;
    private $repositoryGroup;
    private $repositoryStock;
    private $department = [
        0 => [
            'id' => 1220,
            'descricao' => 'Criação',
        ],
        1 => [
            'id' => 1219,
            'descricao' => 'Pré-Impressão',
        ],
        2 => [
            'id' => 1217,
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
    
    public function __construct(EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;
        $this->repositoryGroup = $entityManager->getRepository(Bond::class);
        $this->repositoryStock = $entityManager->getRepository(Stock::class);

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
    
    public function index()
    {
        
        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => "http://api.ideris.com.br/ListaPedido?limit=50&Status=".htmlentities(urlencode('Aberto')),
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
        if (!empty($response->result) || !is_null($response->result)) {
            $this->filter($response->result);
        }
    }

    private function filter($data)
    {
        foreach ($data as $item) {

            $item = $this->order($item->id);
            $this->sku($item->Item[0]);

            if(empty($this->product)){
                $this->transfer($item->id, $this->department[0]['id']);
            } elseif(!empty($this->product) && !$this->department()){
                $this->transfer($item->id, $this->department[0]['id']);
            } elseif(!empty($this->product) && !empty($this->department())){
                $bond = $this->repositoryGroup->findOneBy(['sku' => $this->product->sku]);
                if (!empty($bond)) {
                    $stock = $this->repositoryStock->findOneBy(['product' => $this->product->sku]);
                    if (!empty($stock)) {
                        $this->transfer($item->id, $this->department[$bond->getSectorS() - 1]['id']);
                    } else {
                        $this->transfer($item->id, $this->department[$bond->getSectorP() - 1]['id']);
                    }
                } else {
                    $sector = current($this->department());
                    $this->transfer($item->id, $sector['id']);
                }                
            }
        }
    }

    private function order($data)
    {
        $id = filter_var($data, FILTER_VALIDATE_INT);
        
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
        return $response->result[0];
    }

    private function sku($data)
    {

        $this->curl = curl_init();

        $sku = htmlentities(urlencode($data->skuProdutoItem));

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
        
        curl_close($this->curl);
        if (!empty($response->result[0])) {
            $this->product = $response->result[0];
        } else {
            $this->product = [];
        }
    }

    private function department()
    {
        $product = $this->product;
        $sector = array_filter($this->department, function ($elem) use ($product){
            return $elem['descricao'] == $product->departamento;
        });

        return $sector;
    }

    public function transfer($id, $sector)
    {
        $data = [
            'idPedidos' => $id,
            'idNovoStatus' => $sector,
        ];

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
    }
}