<?php

namespace App\Routing;

use App\Repository\RouteRepository;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Loading routes from database
 */
class DatabaseLoader extends Loader
{
    public function __construct(private readonly RouteRepository $routeRepository, string $env = null)
    {
        parent::__construct($env);
    }

    public function load(mixed $resource, string $type = null)
    {
        $routeCollection = new RouteCollection();
        
        foreach ($this->routeRepository->findAll() as $route) {
            $routeCollection->add(
                $route->getName(),
                new Route(
                    $route->getPath(),
                    defaults: $route->getDefaults(),
                    requirements: $route->getRequirements(),
                    methods: $route->getMethods()
                )
            );
        }
        
        return $routeCollection;
    }

    public function supports($resource, string $type = null)
    {
        return $type === 'db';
    }
}
