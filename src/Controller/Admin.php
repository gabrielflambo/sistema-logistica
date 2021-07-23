<?php

namespace Template\Controller;

use CoffeeCode\Router\Router;
use League\Plates\Engine;
use Doctrine\ORM\EntityManagerInterface;
use Template\Entity\Contributor;
use Template\Entity\User;
use Template\Helper\FlashMessageTrait;

class Admin 
{
    use FlashMessageTrait;

    private $view;
    private $router;
    private $repository;
    private $repositoryContributor;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->view = new Engine(__DIR__ . '/../../views/');
        $this->repository = $entityManager->getRepository(User::class);
        $this->repositoryContributor = $entityManager->getRepository(Contributor::class);
        $this->router = new Router(URL_BASE);
    }

    public function index()
    {
        echo $this->view->render('index', []);

        if (isset($_SESSION['logado'])) {
            $this->router->redirect("/dashboard");
        }
    }

    public function login($data)
    {
        $users = filter_var(
           $data['user'],
            FILTER_SANITIZE_STRING
        );

        $password = filter_var(
            $data['password'],
             FILTER_SANITIZE_STRING
         );

        /** @var User $user */
        $user = $this->repository->findOneBy(['user' => $users]);
        $contributor = $this->repositoryContributor->findOneBy(['user' => $users]);

        if (is_null($user) && is_null($contributor)) {
            $this->defineMensagem('danger', 'Usuário Inexistente!');

            $this->router->redirect("/");
        }

        if (!is_null($user) && !$user->verify_password($password) || !is_null($contributor) && !$contributor->verify_password($password)) {
            $this->defineMensagem('danger', 'Senha incorreta, tente novamente...');

            $this->router->redirect("/");
        }

        $_SESSION['logado'] = true;
        if(!is_null($contributor)){
            $_SESSION['id'] = $contributor->getId();
            $_SESSION['permission'] = [
                $contributor->getName(),
                $contributor->getOffice(),
                $contributor->getImage()
            ];
            $_SESSION['type'] = explode(',', $contributor->getPermission());
            $this->defineMensagem('success', "Bem vindo {$contributor->getName()}!");
        } else {
            $_SESSION['type'] = 0;
            $this->defineMensagem('success', 'Bem vindo Administrador!');
        }
        
        $this->router->redirect("/dashboard");
    }

    public function painel()
    {
        echo $this->view->render('painel', []);
    }

    public function logout()
    {
        unset($_SESSION);
        session_destroy();
        $this->defineMensagem('success', 'Sessão encerrada!');
        $this->router->redirect("/");
    }

}
