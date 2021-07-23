<?php

namespace Template\Controller;

use League\Plates\Engine;
use CoffeeCode\Router\Router;
use CoffeeCode\Uploader\Image;
use Template\Helper\FlashMessageTrait;
use Doctrine\ORM\EntityManagerInterface;
use Template\Entity\Contributor;

class Contributors 
{
    use FlashMessageTrait;

    private $view;
    private $router;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->view = new Engine(__DIR__ . '/../../views/');
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Contributor::class);
        $path = 'public/admin/images';
        $this->upload = new Image($path, "images", 600);
        $this->router = new Router(URL_BASE);
    }

    public function create()
    {
        echo $this->view->render('colaborador', []);
    }

    public function persist($data)
    {

        if (isset($data['id'])) {
            $image = $this->repository->findOneBy(['id' => $data['id']]);
            $id = filter_var(
                $data['id'],
                FILTER_VALIDATE_INT
            );
        }

        $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
        $contributors = filter_var($data['user'], FILTER_SANITIZE_STRING);
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $office = filter_var($data['office'], FILTER_VALIDATE_INT);
        $permission = implode(",", $data['permission']);

        if ($_FILES['input-file']['size'] > 0) {
            try {
                if (isset($data['id'])) {
                    $image = $this->repository->findOneBy(['id' => $id]);
                    unlink($image->getImage());
                }
                $upload = $this->upload->upload($_FILES['input-file'], $name);
            } catch (Exception $e) {
                $this->defineMensagem('error', "{$e->getMessage()}");
                $this->router->redirect("/contributors/create");
            }
        } else if($_FILES['input-file']['size'] == 0 && !isset($data['id'])){
            $upload = 'public/images/avatar.jpg';
        }
        
        $contributor = new Contributor();
        (isset($data['id'])) ? $contributor->setId($id) : '';
        $contributor->setName($name);
        $contributor->setUser($contributors);
        (isset($data['id']) && $data['password'] != '' || !isset($data['id'])) ? $contributor->setPassword($password) : $contributor->setPassword($image->getPassword());
        $contributor->setOffice($office);
        $contributor->setPermission($permission);
        (!isset($upload)) ? $contributor->setImage($image->getImage()) : $contributor->setImage($upload);

        try {
            if(isset($data['id'])){
                $this->entityManager->merge($contributor);
                $this->entityManager->flush();
                $this->defineMensagem('success', "Colaborador Alterado com sucesso!");
                $this->router->redirect("/contributors/edit/{$id}");
            } else {
                $this->entityManager->persist($contributor);
                $this->entityManager->flush();
                $this->defineMensagem('success', "Colaborador Inserido com sucesso!");
                $this->router->redirect("/contributors/search");
            }
        } catch (\Throwable $th) {
            $this->defineMensagem('error', "Não foi possivel inserir colaborador. Erro: {$th->getMessage()}");
            $this->router->redirect("/contributors/create");
        }
    }

    public function search()
    {
        $office = [
            '1' => 'Criação',
            '2' => 'Pré-Impressão',
            '3' => 'Impressão',
            '4' => 'Quadros',
            '5' => 'Acabamento',
            '6' => 'Expedição'
        ];
        echo $this->view->render('todos-os-colaboradores', [
            'contributor' => $this->repository->findAll(),
            'office' => $office
        ]);
    }

    public function edit($data)
    {
        $id = filter_var(
            $data['id'],
            FILTER_VALIDATE_INT
        );

        echo $this->view->render('colaborador', [
            'contributors' => $this->repository->findOneBy(['id' => $id]),
        ]);
    }

    public function delete($data)
    {
        $id = filter_var(
            $data['id'],
            FILTER_VALIDATE_INT
        );

        $contributor = $this->repository->findOneBy(['id' => $id]);
        if(!empty($contributor->getImage()) && $contributor->getImage() != 'public/images/produto-sem-imagem.gif'){
            unlink($contributor->getImage());
        }
        $this->entityManager->remove($contributor);
        $this->entityManager->flush();
    }

}
