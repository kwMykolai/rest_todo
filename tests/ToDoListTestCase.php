<?php


namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

class ToDoListTestCase extends ApiTestCase
{
    public function testListCreation()
    {
        $client = static::createClient();
        $client->request("GET", "/api/v1/lists");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}