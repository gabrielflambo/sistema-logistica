<?php

namespace Template\Entity;

/**
 * @Entity
 * @Table(name="variations")
 */
class Variations
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
     * @Column(type="string")
     */
    private $skuVariacao;
    /**
     * @Column(type="string")
     */
    private $quantidadeVariacao;
    /**
     * @Column(type="string")
     */
    private $nomeAtributo;
    /**
     * @Column(type="string")
     */
    private $valorAtributo;

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

    public function getSkuVariacao(): string
    {
        return $this->skuVariacao;
    }

    public function setSkuVariacao(string $skuVariacao): self
    {
        $this->skuVariacao = $skuVariacao;

        return $this;
    }

    public function getQuantidadeVariacao(): string
    {
        return $this->quantidadeVariacao;
    }

    public function setQuantidadeVariacao(string $quantidadeVariacao): self
    {
        $this->quantidadeVariacao = $quantidadeVariacao;

        return $this;
    }

    public function getNomeAtributo(): string
    {
        return $this->nomeAtributo;
    }

    public function setNomeAtributo(string $nomeAtributo): self
    {
        $this->nomeAtributo = $nomeAtributo;

        return $this;
    }

    public function getValorAtributo(): string
    {
        return $this->valorAtributo;
    }

    public function setValorAtributo(string $valorAtributo): self
    {
        $this->valorAtributo = $valorAtributo;

        return $this;
    }
}
