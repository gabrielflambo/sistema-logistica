<?php

namespace Template\Entity;

use DateTime;

/**
 * @Entity
 * @Table(name="record")
 */
class Record
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
    private $request;
    /**
     * @Column(type="string", length=2000)
     */
    private $note;
    /**
     * @Column(type="integer", nullable=true)
     */
    private $currentSector;
    /**
     * @Column(type="integer", nullable=true)
     */
    private $transferredSector;
    /**
     * @Column(type="integer")
     */
    private $user;
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

    public function getRequest(): int
    {
        return $this->request;
    }

    public function setRequest(int $request): self
    {
        $this->request = $request;

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

    public function getCurrentSector(): int
    {
        return $this->currentSector;
    }

    public function setCurrentSector(?int $currentSector): self
    {
        $this->currentSector = $currentSector;

        return $this;
    }

    public function getTransferredSector(): ?int
    {
        return $this->transferredSector;
    }

    public function setTransferredSector(?int $transferredSector): self
    {
        $this->transferredSector = $transferredSector;

        return $this;
    }

    public function getUser(): int
    {
        return $this->user;
    }

    public function setUser(int $user): self
    {
        $this->user = $user;

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
