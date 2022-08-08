<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductController extends AbstractController
{
    /**
     * Return single product
     */
    public function item(string $uuid): JsonResponse
    {
        return $this->json([]);
    }
}
