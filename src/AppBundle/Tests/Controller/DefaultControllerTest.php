<?php
/**
 *
 * @author    Dominic Guhl <github@dominic-guhl.de>
 * @package   sample-widget
 * @subpackage
 * @copyright 2016 Dominic Guhl
 */

namespace AppBundle\Tests\Controller;

use AppBundle\Controller\DefaultController;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class DefaultControllerTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $em;

    public function setUp()
    {
        self::bootKernel();

        /** @var KernelInterface em */
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testJsUuidFilename()
    {
        $uuid = uniqid('test--');

        $user = new User();
        $user->setUuid($uuid);

        $this->em->persist($user);

        $defaultController = new DefaultController();
        $response = $defaultController->widgetAction(new Request(), $uuid);
        $this->assertNotEmpty($response->getContent(), 'The script is empty. The user might not be found.');

        $response = $defaultController->widgetAction(new Request(), 'numb');
        $this->assertEmpty($response->getContent(), 'The script is not empty.');

        $this->em->remove($user);
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }
}