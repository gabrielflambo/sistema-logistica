<?php

namespace Template\Controller;

use CoffeeCode\Router\Router;
use CoffeeCode\Uploader\Image;
use Template\Helper\FlashMessageTrait;
use Doctrine\ORM\EntityManagerInterface;
use Template\Entity\Image as EntityImage;

class Images 
{
    
    use FlashMessageTrait;
    
    private $router;
    private $repositorio;
    private $entityManager;
    private $upload;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repositorio = $this->entityManager->getRepository(EntityImage::class);
        $path = 'public/images';
        $this->upload = new Image($path, "images", 600);
        $this->router = new Router(URL_BASE);
    }
    
    public function persist(array $data)
    {
        if (isset($data['id'])) {
            $id = $data['id'];
        }
        
        $product = filter_var($data['product'], FILTER_SANITIZE_STRING);
        $product_id = filter_var($data['product_id'], FILTER_VALIDATE_INT);
        $type = $data['type'];
        $alt = $data['alt'];
        $i = 0;
        
        try {
            if(isset($_FILES['image'])){
                foreach ($this->upload->multiple("image", $_FILES) as $file) {
                    
                    if($file["size"] > 0){
                        $upload = $this->upload->upload($file, $product . $i, 1200);
                        $image = $this->repositorio->findOneBy(['id' => $id[$i]]);
                        unlink($image->getImage());
                    }
                    $image = new EntityImage();
                    $image->setId($id[$i]);
                    $image->setType($type);
                    $image->setProduct($product_id);
                    $image = $this->repositorio->findOneBy(['id' => $id[$i]]);
                    ($file["size"] > 0) ? $image->setUrlImagem(URL_BASE . $upload) : $image->setUrlImagem($image->getImage());
                    $image->setImagemBase64('');
                    
                    $this->entityManager->merge($image);
                    
                    $i++;
                }
            } else {
                foreach ($this->upload->multiple("files", $_FILES) as $file) {
                    if ($alt[$i] !== null) {
                        $upload = $this->upload->upload($file, $product . $i, 1200);
                        
                        $image = new EntityImage();
                        (isset($id)) ? $image->setId($id[$i]) : '';
                        $image->setUrlImagem(URL_BASE . $upload);
                        $image->setImagemBase64('');
                        $image->setType($type);
                        $image->setProduct($product_id);
                        
                        $this->entityManager->persist($image);
                        
                        $i++;
                    }
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        
        $this->entityManager->flush();
        $this->defineMensagem('success', "Upload de Imagens com sucesso!");
        $this->router->redirect("$_SERVER[HTTP_REFERER]#images");
    }
    
    public function delete($data)
    {
        $id = filter_var(
            $data['id'],
            FILTER_VALIDATE_INT
        );
        
        $image = $this->repositorio->findOneBy(['id' => $id]);
        $path = 'public/images/images/';
        $img = explode('/', $image->getUrlImagem());
        $img = $img[count($img) - 3] .'/'. $img[count($img) - 2] .'/'. $img[count($img) - 1];
        unlink($path . $img);
        $this->entityManager->remove($image);
        $this->entityManager->flush();
    }
}
