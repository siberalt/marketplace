<?php

namespace App\Controller;

use App\Entity\Purchase;
use App\Form\PurchaseForm;
use App\Repository\PurchaseRepository;
use App\Service\PurchaseService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class PurchaseController extends AbstractFOSRestController
{

    #[OA\QueryParameter(

    )]
    #[Route('/purchase', name: 'app_purchase_cost', methods: ['GET'])]
    public function purchaseCost(Request $request, PurchaseService $purchaseService): Response
    {
        $purchase = new Purchase();
        $data = $request->query->all();
        $form = $this->createForm(PurchaseForm::class, $purchase);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $cost = $purchaseService->calculateCost($purchase);
            $view = $this->view($cost, 200);
        }  else {
            $view = $this->view($form);
        }

        return $this->handleView($view);
    }

    #[Route('/purchase', name: 'app_purchase_make', methods: ['POST'])]
    public function purchaseMake(Request $request, PurchaseService $purchaseService, PurchaseRepository $repository)
    {
        $purchase = new Purchase();
        $data = json_decode($request->getContent());
        $form = $this->createForm(PurchaseForm::class, $purchase);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $purchaseService->makePurchase($purchase);
            $view = $this->view($purchase, 200);
        }  else {
            $view = $this->view($form);
        }

        return $this->handleView($view);
    }
}
