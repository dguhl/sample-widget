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

    protected function getUser()
    {
        return $this->em
            ->getRepository('AppBundle:User')
            ->createQueryBuilder('u')
            ->where('u.id IS NOT NULL')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function testWidgetCallsWhenUserExists()
    {
        $user = $this->getUser();

        $client = static::createClient();

        $urlPart = '/widget/'.$user->getUuid();

        $client->request('GET', $urlPart.'.js');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertNotEmpty($client->getResponse()->getContent());

        $client->request('GET', $urlPart.'.json');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertNotEmpty($client->getResponse()->getContent());

        $client->request('GET', $urlPart.'.html');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertNotEmpty($client->getResponse()->getContent());
    }

    public function testWidgetCallWhenUserNotExists()
    {
        $client = static::createClient();

        $client->request('GET', '/widget/notexistinguserhere.js');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEmpty($client->getResponse()->getContent());

        $client->request('GET', '/widget/notexistinguserhere.json');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEmpty($client->getResponse()->getContent());

        $client->request('GET', '/widget/notexistinguserhere.html');

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
