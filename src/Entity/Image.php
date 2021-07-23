<?php

namespace Template\Entity;

/**
 * @Entity
 * @Table(name="image")
 */
class Image
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    private $id;
    /**
     * @Column(type="string", length=1000)
     */
    private $urlImagem;
    /**
     * @Column(type="string", length=1000)
     */
    private $imagemBase64;
    /**
     * @Column(type="integer")
     */
    private $type;
    /**
     * @Column(type="integer")
     */
    private $product;

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUrlImagem(): string
    {
        return $this->urlImagem;
    }

    public function setUrlImagem(string $urlImagem): self
    {
        $this->urlImagem = $urlImagem;

        return $this;
    }

    public function getImagemBase64(): string
    {
        return $this->imagemBase64;
    }

    public function setImagemBase64(string $imagemBase64): self
    {
        $this->imagemBase64 = $imagemBase64;

        return $this;
    }

    public function getAlt(): string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getProduct(): int
    {
        return $this->product;
    }

    public function setProduct(int $product): self
    {
        $this->product = $product;

        return $this;
    }

}
