<?php

namespace App\DataFixtures;

use App\Controller\ProductController;
use App\Entity\Route;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RouteFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $route = new Route();
        $route->setPath('/products/{uuid}');
        $route->setName('products_item');
        $route->setRequirements(['uuid' => '.+']);
        $route->setMethods(['GET']);
        $route->setDefaults(['_controller' => ProductController::class.'::item']);

        $manager->persist($route);
        $manager->flush();
    }
}
