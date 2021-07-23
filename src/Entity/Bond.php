<?php

namespace Template\Entity;

/**
 * @Entity
 * @Table(name="bond")
 */
class Bond
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
    private $team;
    /**
     * @Column(type="string", length=1000)
     */
    private $image;
    /**
     * @Column(type="string")
     */
    private $sku;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

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

    public function getTeam(): int
    {
        return $this->team;
    }

    public function setTeam(int $team): self
    {
        $this->team = $team;

        return $this;
    }
}
