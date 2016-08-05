<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Group;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadGroupsData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach (range(1, 10) as $i) {
            $group = new Group();
            $group->setName("Group-$i");

            $manager->persist($group);
        }

        $manager->flush();
    }
}
