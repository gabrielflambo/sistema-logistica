<?php

namespace Template\Entity;

/**
 * @Entity
 * @Table(name="user")
 */
class User
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
     * @Column(type="string", length=500)
     * 
     */
    private $password;

    public function verify_password(string $pass): bool
    {
        return password_verify($pass, $this->password);
    }
}
