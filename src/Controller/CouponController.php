<?php

namespace App\Controller;

use App\Entity\Coupon;
use App\Form\CouponForm;
use App\Repository\CouponRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CouponController extends AbstractFOSRestController
{
    #[Route('/coupon', name: 'app_coupons', methods: 'GET')]
    public function index(CouponRepository $couponRepository): Response
    {
        $view = $this->view($couponRepository->findAll(), 200);

        return $this->handleView($view);
    }

    #[Route('/coupon/{id}', name: 'app_coupon', methods: 'GET')]
    public function get(int $id, CouponRepository $couponRepository): Response
    {
        $view = $this->view($couponRepository->find($id), 200);

        return $this->handleView($view);
    }

    #[Route('/coupon/{id}', name: 'app_coupon_delete', methods: 'DELETE')]
    public function delete(int $id, CouponRepository $couponRepository): Response
    {
        $coupon = $couponRepository->find($id);

        $view = $this->view($coupon, 200);
        $couponRepository->remove($coupon, true);

        return $this->handleView($view);
    }

    #[Route('/coupon/{id}', name: 'app_coupon_update', methods: 'PUT')]
    public function update(Coupon $coupon, CouponRepository $couponRepository, Request $request): Response
    {
        return $this->tryToSave($request, $couponRepository, $coupon);
    }

    #[Route('/coupon', name: 'app_coupon_create', methods: 'POST')]
    public function create(CouponRepository $couponRepository, Request $request): Response
    {
        return $this->tryToSave($request, $couponRepository, new Coupon());
    }

    protected function tryToSave(Request $request, CouponRepository $couponRepository, Coupon $coupon): Response
    {
        $form = $this->createForm(CouponForm::class, $coupon);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $couponRepository->save($coupon, true);
            $view = $this->view($coupon, 200);
        }  else {
            $view = $this->view($form);
        }

        return $this->handleView($view);
    }
}
