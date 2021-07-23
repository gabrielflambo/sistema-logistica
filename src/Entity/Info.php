<?php

namespace Template\Entity;

/**
 * @Entity
 * @Table(name="info")
 */
class Info
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    private $id;
    /**
     * @Column(type="integer")
     */
    private $product;
    /**
     * @Column(type="string", length=65500)
     */
    private $descricaoLonga;

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getDescricaoLonga(): string
    {
        return $this->descricaoLonga;
    }

    public function setDescricaoLonga(string $descricaoLonga): self
    {
        $this->descricaoLonga = $descricaoLonga;

        return $this;
    }
}
