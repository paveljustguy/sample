<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name = "users")
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(
     *     name   = "id",
     *     type   = "integer"
     * )
     * @ORM\GeneratedValue(strategy = "AUTO")
     */
    private $id;

    /**
     * @var Group
     *
     * @ORM\ManyToOne(targetEntity = "AppBundle\Entity\Group")
     * @ORM\JoinColumn(
     *      name                 = "group_id",
     *      referencedColumnName = "id",
     *      nullable             = true,
     *      onDelete             = "SET NULL",
     * )
     */
    private $group;

    /**
     * @var string
     *
     * @ORM\Column(
     *      name     = "email",
     *      type     = "string",
     *      length   = 100,
     *      nullable = false
     * )
     *
     * @Assert\NotBlank()
     * @Assert\Length(max = 100)
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(
     *      name     = "last_name",
     *      type     = "string",
     *      length   = 100,
     *      nullable = false
     * )
     *
     * @Assert\NotBlank()
     * @Assert\Length(max = 100)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(
     *      name     = "first_name",
     *      type     = "string",
     *      length   = 100,
     *      nullable = false
     * )
     *
     * @Assert\NotBlank()
     * @Assert\Length(max = 100)
     */
    private $firstName;

    /**
     * @var bool
     *
     * @ORM\Column(
     *      name = "active",
     *      type = "boolean",
     * )
     *
     * @Assert\NotBlank()
     */
    private $active;

    /**
     * @var DateTime User last login datetime
     *
     * @ORM\Column(
     *      name     = "created_at",
     *      type     = "datetime",
     *      nullable = false
     * )
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function getLastName() : string
    {
        return $this->lastName;
    }

    public function getFirstName() : string
    {
        return $this->firstName;
    }

    public function getActive() : bool
    {
        return $this->active;
    }

    public function getCreatedAt() : DateTime
    {
        return $this->createdAt;
    }

    public function setGroup(Group $group)
    {
        $this->group = $group;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;
    }

    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
    }

    public function setActive(bool $active)
    {
        $this->active = $active;
    }
}
