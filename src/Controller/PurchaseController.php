<?php

namespace App\Controller;

use App\Entity\Purchase;
use App\Form\PurchaseForm;
use App\Nelmio\PurchaseRequest;
use App\Service\Purchase\PaymentFailedException;
use App\Service\Purchase\PurchaseService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractFOSRestController
{
    #[OA\QueryParameter(name: 'product', in: 'query', required: true)]
    #[OA\QueryParameter(name: 'taxNumber', in: 'query', required: true)]
    #[OA\QueryParameter(name: 'couponCode', in: 'query', required: false)]
    #[OA\QueryParameter(name: 'paymentProcessor', in: 'query', required: true)]
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

    /**
     * @throws PaymentFailedException
     */
    #[OA\RequestBody(
        content: new OA\JsonContent(ref: new Model(type: PurchaseRequest::class))
    )]
    #[Route('/purchase', name: 'app_purchase_make', methods: ['POST'])]
    public function purchaseMake(Request $request, PurchaseService $purchaseService): Response
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
