<?php

namespace Template\Entity;

/**
 * @Entity
 * @Table(name="contributor")
 */
class Contributor
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
    private $user;
    /**
     * @Column(type="string")
     */
    private $name;
    /**
     * @Column(type="string")
     */
    private $image;
    /**
     * @Column(type="string", length=500)
     */
    private $password;
    /**
     * @Column(type="integer")
     */
    private $office;
    /**
     * @Column(type="string")
     */
    private $permission;


    public function verify_password(string $pass): bool
    {
        return password_verify($pass, $this->password);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;

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

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getOffice(): int
    {
        return $this->office;
    }

    public function setOffice(int $office): self
    {
        $this->office = $office;

        return $this;
    }

    public function getPermission(): string
    {
        return $this->permission;
    }

    public function setPermission(string $permission): self
    {
        $this->permission = $permission;

        return $this;
    }
}
