<?php
/**
 * @author    Dominic Guhl <dominic.guhl@deinhandy.de>
 * @copyright © 2016 DEINHANDY.de, a Mister Mobile GmbH brand
 */

namespace AppBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 7; $i++) {
            $user = new User();
            $user->setReviews([]);
            $user->setId(uniqid('fix--'));

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }

}