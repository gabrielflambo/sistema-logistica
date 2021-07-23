<?php

namespace Template\Entity;

/**
 * @Entity
 * @Table(name="product")
 */
class Product
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    private $id;
    /**
     * @Column(type="string")
     */
    private $titulo;
    /**
     * @Column(type="string")
     */
    private $valorVenda;
    /**
     * @Column(type="string")
     */
    private $valorCusto;
    /**
     * @Column(type="string")
     */
    private $sku;
    /**
     * @Column(type="string")
     */
    private $peso;
    /**
     * @Column(type="string")
     */
    private $altura;
    /**
     * @Column(type="string")
     */
    private $largura;
    /**
     * @Column(type="string")
     */
    private $comprimento;
    /**
     * @Column(type="string")
     */
    private $pesoEmbalagem;
    /**
     * @Column(type="string")
     */
    private $alturaEmbalagem;
    /**
     * @Column(type="string")
     */
    private $larguraEmbalagem;
    /**
     * @Column(type="string")
     */
    private $comprimentoEmbalagem;
    /**
     * @Column(type="integer")
     */
    private $categoriaIdIderis;
    /**
     * @Column(type="integer")
     */
    private $subCategoriaIdIderis;
    /**
     * @Column(type="integer")
     */
    private $marcaIdIderis;
    /**
     * @Column(type="integer")
     */
    private $departamentoIdIderis;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getValorVenda(): string
    {
        return $this->valorVenda;
    }

    public function setValorVenda(string $valorVenda): self
    {
        $this->valorVenda = $valorVenda;

        return $this;
    }

    public function getValorCusto(): string
    {
        return $this->valorCusto;
    }

    public function setValorCusto(string $valorCusto): self
    {
        $this->valorCusto = $valorCusto;

        return $this;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getPeso(): string
    {
        return $this->peso;
    }

    public function setPeso(string $peso): self
    {
        $this->peso = $peso;

        return $this;
    }

    public function getAltura(): string
    {
        return $this->altura;
    }

    public function setAltura(string $altura): self
    {
        $this->altura = $altura;

        return $this;
    }

    public function getLargura(): string
    {
        return $this->largura;
    }

    public function setLargura(string $largura): self
    {
        $this->largura = $largura;

        return $this;
    }

    public function getComprimento(): string
    {
        return $this->comprimento;
    }

    public function setComprimento(string $comprimento): self
    {
        $this->comprimento = $comprimento;

        return $this;
    }

    public function getPesoEmbalagem(): string 
    {
        return $this->pesoEmbalagem;
    }

    public function setPesoEmbalagem(string $pesoEmbalagem): self
    {
        $this->pesoEmbalagem = $pesoEmbalagem;

        return $this;
    }

    public function getAlturaEmbalagem(): string 
    {
        return $this->alturaEmbalagem;
    }

    public function setAlturaEmbalagem(string $alturaEmbalagem): self
    {
        $this->alturaEmbalagem = $alturaEmbalagem;

        return $this;
    }

    public function getLarguraEmbalagem(): string
    {
        return $this->larguraEmbalagem;
    }

    public function setLarguraEmbalagem(string $larguraEmbalagem): self
    {
        $this->larguraEmbalagem = $larguraEmbalagem;

        return $this;
    }

    public function getComprimentoEmbalagem(): string
    {
        return $this->comprimentoEmbalagem;
    }

    public function setComprimentoEmbalagem(string $comprimentoEmbalagem): self
    {
        $this->comprimentoEmbalagem = $comprimentoEmbalagem;

        return $this;
    }

    public function getCategoriaIdIderis(): int
    {
        return $this->categoriaIdIderis;
    }

    public function setCategoriaIdIderis(int $categoriaIdIderis): self
    {
        $this->categoriaIdIderis = $categoriaIdIderis;

        return $this;
    }

    public function getSubCategoriaIdIderis(): int
    {
        return $this->subCategoriaIdIderis;
    }

    public function setSubCategoriaIdIderis(int $subCategoriaIdIderis): self
    {
        $this->subCategoriaIdIderis = $subCategoriaIdIderis;

        return $this;
    }

    public function getMarcaIdIderis(): int
    {
        return $this->marcaIdIderis;
    }

    public function setMarcaIdIderis(int $marcaIdIderis): self
    {
        $this->marcaIdIderis = $marcaIdIderis;

        return $this;
    }

    public function getDepartamentoIdIderis(): int
    {
        return $this->departamentoIdIderis;
    }

    public function setDepartamentoIdIderis(int $departamentoIdIderis): self
    {
        $this->departamentoIdIderis = $departamentoIdIderis;

        return $this;
    }
}
