<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /** @var EntityManager */
    private $em;

    /** @var  User */
    private $user;

    public function setUp()
    {
        self::bootKernel();
        $uuid = uniqid('test--');

        $user = new User();
        $user->setUuid($uuid);
        $this->user = $user;

        $this->em = self::$kernel->getContainer()->get('doctrine')->getManager();

        $this->em->persist($user);
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
        $client = static::createClient();

        $crawler = $client->request('GET', '/widget/'.$this->user->getUuid().'.js');

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

        $this->em->remove($this->user);
    }
}
