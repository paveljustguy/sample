<?php

namespace AppBundle\Serializer;

use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UserDenormalizer implements DenormalizerInterface
{
    /**
     * @var EntityManager
     */
    private $manager;

    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    public function denormalize($data, $class, $format = null, array $context = array())
    {
        $user = isset($context['user']) ? $context['user'] : new $class();

        if (isset($data['firstName'])) {
            $user->setFirstName($data['firstName']);
        }

        if (isset($data['lastName'])) {
            $user->setLastName($data['lastName']);
        }

        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }

        if (isset($data['active'])) {
            $user->setActive((bool) $data['active']);
        }

        if (isset($data['group'])) {
            $group = $this->fetchGroup($data['group']);

            if (null !== $group) {
                $user->setGroup($group);
            }
        }

        return $user;
    }

    private function fetchGroup($id)
    {
        return $this->manager
            ->getRepository(Group::class)
            ->find($id);
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($format !== 'json') {
            return false;
        }

        if ($type !== User::class) {
            return false;
        }

        return true;
    }
}
