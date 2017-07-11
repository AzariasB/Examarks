<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\MappedSuperclass
 */
class User implements \Symfony\Component\Security\Core\User\UserInterface, \Symfony\Component\Security\Core\User\EquatableInterface, \JsonSerializable {

    const ROLE_USER = 1;
    const ROLE_STUDENT = 2;
    const ROLE_TEACHER = 4;
    const ROLE_ADMIN = 8;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=255, unique=true)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     *
     * @var int
     * @ORM\Column(name="roles", type="integer")
     */
    private $roles;


    public function __construct(string $login, string $password, array $roles) {
        $this->login = $login;
        $this->password = $password;
        $this->setRoles($roles);
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set login
     *
     * @param string $login
     *
     * @return User
     */
    public function setLogin($login) {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin() {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set new role
     * 
     * @param int $nwRoles
     */
    public function setRoles($nwRoles) {
        if (!is_array($nwRoles)) {
            $this->roles = $nwRoles;
            return;
        }

        foreach ($nwRoles as $r) {
            if ($r == "ROLE_USER") {
                $this->roles |= User::ROLE_USER;
            } else if ($r === "ROLE_STUDENT") {
                $this->roles |= User::ROLE_STUDENT;
            } else if ($r === "ROLE_TEACHER") {
                $this->roles |= User::ROLE_TEACHER;
            } else if ($r === "ROLE_ADMIN") {
                $this->roles |= User::ROLE_ADMIN;
            }
        }
    }
    
    
    public function eraseCredentials() {
        //Nothing sensitive to erase
    }

    public function getRoles() {
        $res = [];
        if ($this->roles & User::ROLE_USER) {
            $res[] = "ROLE_USER";
        }
        if ($this->roles & User::ROLE_STUDENT) {
            $res[] = "ROLE_STUDENT";
        }
        if ($this->roles & User::ROLE_TEACHER) {
            $res[] = "ROLE_TEACHER";
        }
        if ($this->roles & User::ROLE_ADMIN) {
            $res[] = "ROLE_ADMIN";
        }

        return $res;
    }

    public function getSalt() {
        return hash("sha256", $this->login);
    }

    public function getUsername(): string {
        return $this->login;
    }

    public function isEqualTo(\Symfony\Component\Security\Core\User\UserInterface $user): bool {
        return $user->getUsername() == $this->getUsername();
    }

    public function jsonSerialize() {
        return [
            "id" => $this->id,
            "login" => $this->login
        ];
    }
    
    public function isStudent(){
        return $this->isRole(User::ROLE_STUDENT);
    }
    
    public function isTeacher(){
        return $this->isRole(User::ROLE_TEACHER);
    }
    
    public function isAdmin(){
        return $this->isRole(User::ROLE_ADMIN);
    }
    
    private function isRole(int $role){
        return $this->roles & $role != 0;
    }

}
