<?php

namespace Template\Controller;

use League\Plates\Engine;
use Template\Entity\Stock;
use CoffeeCode\Router\Router;
use Template\Helper\FlashMessageTrait;
use Doctrine\ORM\EntityManagerInterface;
use Template\Entity\Image;
use Template\Entity\Variations as EntityVariations;

class Variations 
{
    use FlashMessageTrait;
    
    private $view;
    private $router;
    private $curl;
    private $token;
    private $repository;
    private $repositoryImage;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->view = new Engine(__DIR__ . '/../../views/');
        $this->router = new Router(URL_BASE);
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(EntityVariations::class);
        $this->repositoryImage = $entityManager->getRepository(Image::class);
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

    public function view($data)
    {
        $id = filter_var($data['productId'], FILTER_VALIDATE_INT);
        $variation = filter_var($data['variationId'], FILTER_VALIDATE_INT);
        
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

        $response = array_filter($response->result[0]->Variacao, function ($elem) use ($variation){
            return $elem->variacaoId == $variation;
        });

        $response = current($response);
        
        curl_close($this->curl);
        echo $this->view->render('produtos', [
            'variations' => $response,
            'id' => $id,
            ]
        );
    }

    public function persist($data)
    {
        if (isset($data['id'])) {
            $id = filter_var(
                $data['id'],
                FILTER_VALIDATE_INT
            );
        }

        $product = filter_var($data['product'], FILTER_VALIDATE_INT);
        $skuVariacao = filter_var($data['skuVariacao'], FILTER_SANITIZE_STRING);
        $quantidadeVariacao = filter_var($data['quantidadeVariacao'], FILTER_SANITIZE_STRING);
        $nomeAtributo = filter_var($data['nomeAtributo'], FILTER_SANITIZE_STRING);
        $valorAtributo = filter_var($data['valorAtributo'], FILTER_SANITIZE_STRING);

        
        $variation = new EntityVariations();
        (isset($data['id'])) ? $variation->setId($id) : '';
        $variation->setProduct($product);
        $variation->setSkuVariacao($skuVariacao);
        $variation->setQuantidadeVariacao($quantidadeVariacao);
        $variation->setNomeAtributo($nomeAtributo);
        $variation->setValorAtributo($valorAtributo);

        try {
            if(isset($data['id'])){
                $this->entityManager->merge($variation);
                $this->entityManager->flush();
                $this->defineMensagem('success', "Variação Alterada com sucesso!");
                $this->router->redirect("/variations/edit/{$id}");
            } else {
                $this->entityManager->persist($variation);
                $this->entityManager->flush();
                $this->defineMensagem('success', "Variação Inserida com sucesso!");
                $this->router->redirect("/variations/edit/{$variation->getId()}");
            }
        } catch (\Throwable $th) {
            $this->defineMensagem('error', "Não foi possivel inserir a variação. Erro: {$th->getMessage()}");
            $this->router->redirect("/product/edit/sketch/$product#variations");
        }
    }

    public function edit($data)
    {
        $id = filter_var(
            $data['id'],
            FILTER_VALIDATE_INT
        );

        echo $this->view->render('criar-produto', [
            'variations' => $this->repository->findOneBy(['id' => $id]),
            'image' => $this->repositoryImage->findBy(['product' => $id, 'type' => 1]),
        ]);
    }

    public function delete($data)
    {
        $id = filter_var(
            $data['id'],
            FILTER_VALIDATE_INT
        );

        $product = filter_var(
            $data['product'],
            FILTER_VALIDATE_INT
        );

        $product = $this->repository->findOneBy(['id' => $id]);
        $images = $this->repositoryImage->findBy(['product' => $product, 'type' => 1]);

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

        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }
    
}