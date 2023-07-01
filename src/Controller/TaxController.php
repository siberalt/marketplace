<?php

namespace App\Controller;

use App\Entity\Tax;
use App\Form\TaxForm;
use App\Repository\TaxRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class TaxController extends AbstractFOSRestController
{
    #[Route('/tax', name: 'app_tax', methods: 'GET')]
    public function index(TaxRepository $taxRepository): Response
    {
        $view = $this->view($taxRepository->findAll(), 200);

        return $this->handleView($view);
    }

    #[Route('/tax/{id}', name: 'app_tax', methods: 'GET')]
    public function get(int $id, TaxRepository $taxRepository): Response
    {
        $view = $this->view($taxRepository->find($id), 200);

        return $this->handleView($view);
    }

    #[Route('/tax/{id}', name: 'app_tax_delete', methods: 'DELETE')]
    public function delete(int $id, TaxRepository $taxRepository): Response
    {
        $tax = $taxRepository->find($id);

        $view = $this->view($tax, 200);
        $taxRepository->remove($tax, true);

        return $this->handleView($view);
    }

    #[OA\RequestBody(
        content: new OA\JsonContent(ref: new Model(type: Tax::class, groups: ['request']))
    )]
    #[Route('/tax/{id}', name: 'app_tax_update', methods: 'PUT')]
    public function update(Tax $tax, TaxRepository $taxRepository, Request $request): Response
    {
        return $this->tryToSave($request, $taxRepository, $tax);
    }

    #[OA\RequestBody(
        content: new OA\JsonContent(ref: new Model(type: Tax::class, groups: ['request']))
    )]
    #[Route('/tax', name: 'app_tax_create', methods: 'POST')]
    public function create(TaxRepository $taxRepository, Request $request): Response
    {
        return $this->tryToSave($request, $taxRepository, new Tax());
    }

    protected function tryToSave(Request $request, TaxRepository $taxRepository, Tax $tax): Response
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(TaxForm::class, $tax);
        $form->submit($data);

        if ($form->isValid()) {
            $taxRepository->save($tax, true);
            $view = $this->view($tax, 200);
        }  else {
            $view = $this->view($form);
        }

        return $this->handleView($view);
    }
}
