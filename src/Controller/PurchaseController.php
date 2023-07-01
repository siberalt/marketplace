<?php

namespace App\Controller;

use App\Repository\PurchaseRepository;
use App\Service\PurchaseService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractFOSRestController
{
    #[Route('/purchase', name: 'app_purchase', methods: ['POST'])]
    public function purchase(PurchaseService $service, PurchaseRepository $repository)
    {

    }
}
