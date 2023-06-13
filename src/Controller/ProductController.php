<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractFOSRestController
{
    #[Route('/product', name: 'app_product')]
    public function index(ProductRepository $productRepository): JsonResponse
    {

    }

    public function get(ProductRepository $productRepository)
    {

    }

    public function delete(ProductRepository $productRepository)
    {

    }

    public function update(ProductRepository $productRepository)
    {

    }

    public function create(ProductRepository $productRepository)
    {

    }
}
