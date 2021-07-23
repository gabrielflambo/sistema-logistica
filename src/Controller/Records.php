<?php

namespace Template\Controller;

use DateTime;
use League\Plates\Engine;
use CoffeeCode\Router\Router;
use Template\Helper\FlashMessageTrait;
use Doctrine\ORM\EntityManagerInterface;
use Template\Entity\Record;

class Records 
{
    use FlashMessageTrait;
    
    private $view;
    private $router;
    private $curl;
    private $token;
    private $entityManager;
    private $repository;
    private $status = [
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
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->view = new Engine(__DIR__ . '/../../views/');
        $this->router = new Router(URL_BASE);
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Record::class);
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

    public function persist($data)
    {

        if($_SESSION['type'] === 0){
            $this->defineMensagem('error', "Administradores não podem utilizar essa funcionalidade, apenas colaboradores!");
            $this->router->redirect("$_SERVER[HTTP_REFERER]");
        }

        $order = filter_var($data['order'], FILTER_VALIDATE_INT);
        $status = array_filter($this->status, function ($elem) use($data){
           return $elem['descricao'] === $data['status'];
        });
        $status = current($status);
        $note = filter_var($data['note'], FILTER_SANITIZE_STRING);

        if (isset($data['transfer']) && $data['transfer'] == 1) {
            $transfer = filter_var($data['transfer'], FILTER_VALIDATE_INT);
            $sector = filter_var($data['sector'], FILTER_VALIDATE_INT);
        } elseif(!isset($data['transfer']) && $data['transfer'] != 1 && empty($note)){
            $this->defineMensagem('error', "Para continuar, por favor preencha os campos necessários!");
            $this->router->redirect("$_SERVER[HTTP_REFERER]");
        }
        
        $record = new Record();
        $record->setRequest($order);
        $record->setCurrentSector($status['id']);
        $record->setNote($note);
        $record->setUser($_SESSION['id']);
        $record->setDate();
        (isset($transfer)) ? $record->setTransferredSector($sector) : '';

        try {
            $this->entityManager->persist($record);
            $this->entityManager->flush();
            (isset($transfer)) ? $this->status(['idPedidos' => $order, 'idNovoStatus' => $sector]) : '';
            $this->defineMensagem('success', "Registro criado com sucesso");
            $this->router->redirect("$_SERVER[HTTP_REFERER]");
        } catch (\Throwable $th) {
            $this->defineMensagem('error', "Não foi possivel criar o registro. Erro: {$th->getMessage()}");
            $this->router->redirect("$_SERVER[HTTP_REFERER]");
        }
    }

    private function status($data)
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
    }
    
}