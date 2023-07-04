<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductForm;
use App\Repository\ProductRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class ProductController extends AbstractFOSRestController
{
    #[Route('/product', name: 'app_products', methods: 'GET')]
    public function index(ProductRepository $productRepository): Response
    {
        $view = $this->view($productRepository->findAll(), 200);

        return $this->handleView($view);
    }

    #[Route('/product/{id}', name: 'app_product', methods: 'GET')]
    public function get(int $id, ProductRepository $productRepository): Response
    {
        $view = $this->view($productRepository->find($id), 200);

        return $this->handleView($view);
    }

    #[Route('/product/{id}', name: 'app_product_delete', methods: 'DELETE')]
    public function delete(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        $view = $this->view($product, 200);
        $productRepository->remove($product, true);

        return $this->handleView($view);
    }

    #[OA\RequestBody(
        content: new OA\JsonContent(ref: new Model(type: Product::class, groups: ['request']))
    )]
    #[Route('/product/{id}', name: 'app_product_update', methods: 'PUT')]
    public function update(Product $product, ProductRepository $productRepository, Request $request): Response
    {
        return $this->tryToSave($request, $productRepository, $product);
    }

    #[OA\RequestBody(
        content: new OA\JsonContent(ref: new Model(type: Product::class, groups: ['request']))
    )]
    #[Route('/product', name: 'app_product_create', methods: 'POST')]
    public function create(ProductRepository $productRepository, Request $request): Response
    {
        return $this->tryToSave($request, $productRepository, new Product());
    }

    protected function tryToSave(Request $request, ProductRepository $productRepository, Product $product): Response
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(ProductForm::class, $product);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->save($product, true);
            $view = $this->view($product, 200);
        }  else {
            $view = $this->view($form);
        }

        return $this->handleView($view);
    }
}
