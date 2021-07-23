<?php

namespace Template\Controller;

use League\Plates\Engine;
use CoffeeCode\Router\Router;
use Template\Helper\FlashMessageTrait;
use Doctrine\ORM\EntityManagerInterface;
use Template\Entity\Bond;
use Template\Entity\Team;

class Groups 
{
    use FlashMessageTrait;

    private $view;
    private $router;
    private $token;
    private $repository;
    private $repositoryBond;
    private $office = [
        '1' => 'Criação',
        '2' => 'Pré-Impressão',
        '3' => 'Impressão',
        '4' => 'Quadros',
        '5' => 'Acabamento',
        '6' => 'Expedição'
    ];

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->view = new Engine(__DIR__ . '/../../views/');
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Team::class);
        $this->repositoryBond = $entityManager->getRepository(Bond::class);
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

    public function index()
    {
        echo $this->view->render('grupo-produtos', [
            'office' => $this->office,
            'groups' => $this->repository->findAll()
        ]);
    }

    public function group($data)
    {
        $id = filter_var(
            $data['id'],
            FILTER_VALIDATE_INT
        );

        echo $this->view->render('grupo', [
            'office' => $this->office,
            'group' => $this->repository->findOneBy(['id' => $id]),
            'products' => $this->repositoryBond->findBy(['team' => $id])
        ]);
    }

    public function persist($data)
    {

        if (isset($data['id'])) {
            $id = filter_var(
                $data['id'],
                FILTER_VALIDATE_INT
            );
        }

        $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
        $sectorP = filter_var($data['sectorP'], FILTER_VALIDATE_INT);
        $sectorS = filter_var($data['sectorS'], FILTER_VALIDATE_INT);
        
        $group = new Team();
        (isset($data['id'])) ? $group->setId($id) : '';
        $group->setName($name);
        $group->setSectorP($sectorP);
        $group->setSectorS($sectorS);

        try {
            if(isset($data['id'])){
                $this->entityManager->merge($group);
                $this->entityManager->flush();
                $this->defineMensagem('success', "Grupo de Produtos Alterado com sucesso!");
                $this->router->redirect("/group/{$id}");
            } else {
                $this->entityManager->persist($group);
                $this->entityManager->flush();
                $this->defineMensagem('success', "Grupo de Produtos Inserido com sucesso!");
                $this->router->redirect("/group");
            }
        } catch (\Throwable $th) {
            $this->defineMensagem('error', "Não foi possivel inserir grupo de produtos. Erro: {$th->getMessage()}");
            $this->router->redirect("/group");
        }
    }

    public function complete($data)
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
            $this->complete($data);
            exit();
        }
        
        curl_close($this->curl);

        if (!empty($response->result)) {
            print_r(json_encode($response->result));
        } else {
            print_r(null);
        }
    }

    public function bond($data)
    {

        $group = filter_var($data['group'], FILTER_VALIDATE_INT);
        $image = filter_var($data['image'], FILTER_SANITIZE_STRING);
        $sku = filter_var($data['sku'], FILTER_SANITIZE_STRING);
        
        $bond = new Bond();
        $bond->setTeam($group);
        $bond->setImage($image);
        $bond->setSku($sku);

        $this->entityManager->persist($bond);
        $this->entityManager->flush();
    }

    public function delete($data)
    {
        $id = filter_var(
            $data['id'],
            FILTER_VALIDATE_INT
        );

        $group = $this->repository->findOneBy(['id' => $id]);
        $products = $this->repositoryBond->findBy(['team' => $id]);

        if(!empty($products)){
            foreach ($products as $item) {
                $product = $this->repositoryBond->findOneBy(['id' => $item->getId()]);
                $this->entityManager->remove($product);
            }
        }

        $this->entityManager->remove($group);
        $this->entityManager->flush();
    }

    public function remove($data)
    {
        $id = filter_var(
            $data['id'],
            FILTER_VALIDATE_INT
        );

        $product = $this->repositoryBond->findOneBy(['id' => $id]);

        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }

}
