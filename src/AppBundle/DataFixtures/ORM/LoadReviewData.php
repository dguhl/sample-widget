<?php
/**
 * @author    Dominic Guhl <dominic.guhl@deinhandy.de>
 * @copyright © 2016 DEINHANDY.de, a Mister Mobile GmbH brand
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Review;
use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadReviewData extends AbstractFixture
    implements  OrderedFixtureInterface,
                ContainerAwareInterface

{
    /**
     * @var Container
     */
    private $container = null;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    public function load(ObjectManager $manager)
    {
        /** @var UserRepository $userRepository */
        $userRepository     = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:User');
        $users              = $userRepository->findAll();

        /** @var User $user */
        foreach ($users as $user) {
            $reviewsForThisUser = rand(1, 8);
            for ($j = 0; $j < $reviewsForThisUser; $j++) {
                $review = new Review();
                $review->setRating(rand(0, 100));
                $review->setUser($user);
                $manager->persist($review);
            }
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }

}