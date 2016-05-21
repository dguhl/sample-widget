<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /** @var EntityManager */
    private $em;

    private $entities = [];

    public function setUp()
    {
        self::bootKernel();

        $this->em = self::$kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());
    }

    public function testWidgetCallWhenUserExists()
    {
        $uuid = uniqid('test--');

        $user = new User();
        $user->setUuid($uuid);
        $user->setRating(61);

        $this->entities[] = $user;
        $this->em->persist($user);
        $this->em->flush();

        $client = static::createClient();

        $crawler = $client->request('GET', '/widget/'.$user->getUuid().'.js');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertNotEmpty($client->getResponse()->getContent());

    }

    public function testWidgetCallWhenUserNotExists()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/widget/notexistinguserhere.js');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEmpty($client->getResponse()->getContent());
    }

    public function tearDown()
    {
        parent::tearDown();

        foreach ($this->entities as $entity) {
            $entity = $this->em->merge($entity);
            $this->em->remove($entity);
        }

        $this->em->flush();
    }
}
