<?php

namespace App\Tests;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TestCase extends KernelTestCase
{
    public function setUp()
    {
        parent::setUp();
        static::bootKernel();
        $this->resetDatabase();
    }

    public function tearDown()
    {
        $this->getDefaultEntityManager()->close();
    }

    protected function get($serviceName)
    {
        return self::$kernel->getContainer()->get($serviceName);
    }

    private function resetDatabase()
    {
        $entityManager = $this->getDefaultEntityManager();
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
        if (!empty($metadata)) {
            $tool = new SchemaTool($entityManager);
            $tool->dropSchema($metadata);
            $tool->createSchema($metadata);
        }
    }

    private function getDefaultEntityManager(): EntityManager
    {
        return $this->get('doctrine.orm.entity_manager');
    }
}