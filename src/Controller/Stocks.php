<?php

namespace Template\Controller;

use League\Plates\Engine;
use Template\Entity\Stock;
use CoffeeCode\Router\Router;
use CoffeeCode\Paginator\Paginator;
use Template\Helper\FlashMessageTrait;
use Doctrine\ORM\EntityManagerInterface;

class Stocks 
{
    use FlashMessageTrait;
    
    private $view;
    private $router;
    private $repository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->view = new Engine(__DIR__ . '/../../views/');
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Stock::class);
        $this->router = new Router(URL_BASE);
    }

    public function index($data)
    {

        $page = filter_var(
            $data['page'],
            FILTER_SANITIZE_STRIPPED
        );

        $paginator = new Paginator(URL_BASE . "stock/", '', ["Primeira Página", "Primeira"], ["Última Página", "Última"]);
        $paginator->pager(count($this->repository->findAll()), 10, $page, 2);

        $query_select = "SELECT s FROM Template\Entity\Stock s ORDER BY s.id DESC";

        $query = $this->entityManager
        ->createQuery($query_select)
        ->setFirstResult($paginator->offset())
        ->setMaxResults($paginator->limit());
  
        $stock = $query->getResult();
        
        echo $this->view->render('stock', [
            'stock' => $stock,
            'paginator' => $paginator,
        ]);
    }

    public function query($data)
    {
        $page = filter_var(
            $data['page'],
            FILTER_SANITIZE_STRIPPED
        );

        $paginator = new Paginator(URL_BASE . "stock/", '', ["Primeira Página", "Primeira"], ["Última Página", "Última"]);
        $paginator->pager(count($this->repository->findAll()), 10, $page, 2);

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('s')->from('Template\Entity\Stock', 's');
        $qb->add('orderBy', 's.id DESC');

        $_SESSION['search'] = [
            'dataInicial' => $_GET['dataInicial'],
            'dataFinal' => $_GET['dataFinal'],
            'tipo' => (isset($_GET['tipo'])) ? $_GET['tipo'] : '',
            'sku' => $_GET['sku'],
        ];

        if (!empty($_GET['dataInicial']) && !empty($_GET['dataFinal'])) {
            $dateInicial = new \DateTime($_GET['dataInicial'] . '00:00:00');
            $dateInicial = $dateInicial->format('Y-m-d H:i:s');
            $dateFinal = new \DateTime($_GET['dataFinal'] . '23:59:59');
            $dateFinal = $dateFinal->format('Y-m-d H:i:s');
            $qb->andWhere("s.date >= '$dateInicial' and s.date <= '$dateFinal'");
        }

        if (!empty($_GET['dataInicial']) && empty($_GET['dataFinal'])) {
            $dateInicial = new \DateTime($_GET['dataInicial'] . '00:00:00');
            $dateInicial = $dateInicial->format('Y-m-d H:i:s');
            $qb->andWhere("s.date >= '$dateInicial'");
        }

        if (empty($_GET['dataInicial']) && !empty($_GET['dataFinal'])) {
            $dateFinal = new \DateTime($_GET['dataFinal'] . '23:59:59');
            $dateFinal = $dateFinal->format('Y-m-d H:i:s');
            $qb->andWhere("s.date <= '$dateFinal'");
        }

        if (isset($_GET['sku']) && !empty($_GET['sku'])) {
            $qb->andWhere("s.product = '$_GET[sku]'");
        }

        if (isset($_GET['tipo'])) {
            $qb->andWhere("s.type = $_GET[tipo]");
        }
        
        $qb->setFirstResult($paginator->offset())
        ->setMaxResults($paginator->limit());
  
        $query = $qb->getQuery();
        $stock = $query->getResult();

        $paginator = new Paginator(URL_BASE . "stock/", '', ["Primeira Página", "Primeira"], ["Última Página", "Última"]);
        $paginator->pager(count($stock), 10, $page, 2);
        
        echo $this->view->render('stock', [
            'stock' => $stock,
            'paginator' => $paginator,
        ]);
    }

    public function clean()
    {
        unset($_SESSION['search']);
        $this->router->redirect("/stock");
    }
    
    public function persist($data)
    {
       
        foreach ($data['product'] as $item):
            $type = filter_var($data['type'], FILTER_VALIDATE_INT);
            $amount = filter_var($data['amount'], FILTER_SANITIZE_STRING);
            $price = filter_var($data['price'], FILTER_SANITIZE_STRING);
            $note = filter_var($data['note'], FILTER_SANITIZE_STRING);
            
            $stock = new Stock();
            $stock->setProduct($item);
            $stock->setType($type);
            $stock->setAmount($amount);
            $stock->setPrice($price);
            $stock->setNote($note);
            $stock->setDate();
            $this->entityManager->persist($stock);
        endforeach;
        
        try {
            $this->entityManager->flush();
            $this->defineMensagem('success', "Estoque inserido com sucesso!");
            $this->router->redirect("$_SERVER[HTTP_REFERER]#stock");
        } catch (\Throwable $th) {
            $this->defineMensagem('error', "Não foi possivel inserir o estoque. Erro: {$th->getMessage()}");
            $this->router->redirect("$_SERVER[HTTP_REFERER]#stock");
        }
    }
    
    public function delete($data)
    {
        $id = filter_var(
            $data['id'],
            FILTER_VALIDATE_INT
        );
        
        $stock = $this->repository->findOneBy(['id' => $id]);
        
        $this->entityManager->remove($stock);
        $this->entityManager->flush();
    }
}
