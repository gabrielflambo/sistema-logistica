<?php

namespace Template\Entity;

/**
 * @Entity
 * @Table(name="team")
 */
class Team
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
    private $name;
    /**
     * @Column(type="integer")
     */
    private $sectorP;
    /**
     * @Column(type="integer")
     */
    private $sectorS;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSectorP(): int
    {
        return $this->sectorP;
    }

    public function setSectorP(int $sectorP): self
    {
        $this->sectorP = $sectorP;

        return $this;
    }

    public function getSectorS(): int
    {
        return $this->sectorS;
    }

    public function setSectorS(int $sectorS): self
    {
        $this->sectorS = $sectorS;

        return $this;
    }
}
