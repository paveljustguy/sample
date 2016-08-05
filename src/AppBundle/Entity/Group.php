<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name = "groups")
 */
class Group
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
     * @var string
     *
     * @ORM\Column(
     *      name     = "name",
     *      type     = "string",
     *      length   = 100,
     *      nullable = false
     * )
     *
     * @Assert\NotBlank()
     */
    private $name;

    public function getId() : int
    {
        return $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }
}
