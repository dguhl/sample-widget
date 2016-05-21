<?php

namespace Application\Migrations;

use AppBundle\Entity\User;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Migrations\Version;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160521164938 extends AbstractMigration implements ContainerAwareInterface
{
    /** @var  ContainerInterface */
    private $container;

    /** @var  EntityManager */
    private $em;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->receiveEntityManager();

        for ($i = 0; $i < 7; $i++) {
            $user = new User();
            $user->setRating(rand(0, 100));
            $user->setUuid(uniqid());
            $this->em->persist($user);
        }

        $this->em->flush();

        $schema->getTable($this->getUserTableName());
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->receiveEntityManager();
        $this->connection->executeQuery('TRUNCATE TABLE '. $this->getUserTableName());
    }

    /**
     * @return string
     */
    protected function getUserTableName()
    {
        return $this->em->getClassMetadata(User::class)->getTableName();
    }

    protected function receiveEntityManager()
    {
        $this->em = $this->container->get('doctrine.orm.entity_manager');
    }
}
