<?php

namespace Template\Controller;

use CoffeeCode\Router\Router;
use Template\Helper\FlashMessageTrait;
use Doctrine\ORM\EntityManagerInterface;
use Template\Entity\Info;

class Infos 
{
    use FlashMessageTrait;

    private $view;
    private $router;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Info::class);
        $this->router = new Router(URL_BASE);
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
        $descricaoLonga = $data['descricaoLonga'];
        
        $info = new Info();
        (isset($data['id'])) ? $info->setId($id) : '';
        $info->setProduct($product);
        $info->setDescricaoLonga($descricaoLonga);

        try {
            if(isset($data['id'])){
                $this->entityManager->merge($info);
            } else {
                $this->entityManager->persist($info);
            }
            $this->entityManager->flush();
            $this->defineMensagem('success', "Informações alteradas com sucesso!");
            $this->router->redirect("/product/edit/sketch/{$product}#information");
        } catch (\Throwable $th) {
            $this->defineMensagem('error', "Não foi possivel alterar as informações. Erro: {$th->getMessage()}");
            $this->router->redirect("/product/edit/sketch/{$product}#information");
        }
    }
}
