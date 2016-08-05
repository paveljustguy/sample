<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUsersData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach (range(1, 10) as $i) {
            $user = new User();
            $user->setEmail("mail_$i@sampleapp.dev");
            $user->setFirstName("First name $i");
            $user->setLastName("Last name $i");
            $user->setActive(true);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
