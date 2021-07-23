<?php

namespace Template\Entity;

/**
 * @Entity
 * @Table(name="stock")
 */
class Stock
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
    private $product;
    /**
     * @Column(type="integer")
     */
    private $type;
    /**
     * @Column(type="string")
     */
    private $amount;
    /**
     * @Column(type="string")
     */
    private $price;
    /**
     * @Column(type="string")
     */
    private $note;
    /**
     * @Column(type="datetime")
     */
    private $date;

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProduct(): string
    {
        return $this->product;
    }

    public function setProduct(string $product): self
    {
        $this->product = $product;

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

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate()
    {
        $this->date = new \DateTime("now");
        return $this;
    }
}
