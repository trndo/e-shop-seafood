<?php


namespace App\Controller\admin;


use App\Form\SpecialPropositionForm\GiftPromotionType;
use App\Form\SpecialPropositionForm\GlobalSpecialPricePromotionType;
use App\Form\SpecialPropositionForm\PercentPromotionType;
use App\Form\SpecialPropositionForm\SpecialPricePromotionType;
use App\Model\PromotionModel;
use App\Service\EntityService\SpecialPropositionService\Factory\SpecialPropositionAbstractFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpecialPropositionController extends AbstractController
{
    /**
     * @Route("/lipadmin/promotion/createPromotion/percent/{type}", name="createPercentPromotion")
     * @param Request $request
     * @param SpecialPropositionAbstractFactory $factory
     * @param string $type
     * @return Response
     */
    public function addPercentPromotion(Request $request, SpecialPropositionAbstractFactory $factory, string $type): Response
    {
        $promotion = new PromotionModel();
        $options['receipt'] = $type == 'receipt' ? true : false;
        $form = $this->createForm(PercentPromotionType::class, $promotion,$options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $percentFactory = $factory->createPercentPromotion($promotion);
            if ($type == 'receipt') {
                $percentFactory->addReceiptPromotion();
            } else {
                $percentFactory->addProductPromotion();
            }

            return $this->redirectToRoute('admins');
        }

        return $this->render('admin/special_proposition/percent.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/lipadmin/promotion/createPromotion/specialPrice/{type}", name="createSpecialPricePromotion")
     * @param Request $request
     * @param SpecialPropositionAbstractFactory $factory
     * @param string $type
     * @return Response
     */
    public function addSpecialPricePromotion(Request $request, SpecialPropositionAbstractFactory $factory, string $type): Response
    {
        $promotion = new PromotionModel();
        $options['receipt'] = $type == 'receipt' ? true : false;
        $form = $this->createForm(SpecialPricePromotionType::class, $promotion,$options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $percentFactory = $factory->createSpecialPricePromotion($promotion);
            if ($type === 'receipt') {
                $percentFactory->addReceiptPromotion();
            } else {
                $percentFactory->addProductPromotion();
            }

            return $this->redirectToRoute('admins');
        }

        return $this->render('admin/special_proposition/special_price.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/lipadmin/promotion/createPromotion/gift/{type}", name="createGiftPromotion")
     * @param Request $request
     * @param SpecialPropositionAbstractFactory $factory
     * @param string $type
     * @return Response
     */
    public function addGiftPromotion(Request $request, SpecialPropositionAbstractFactory $factory, string $type): Response
    {
        $promotion = new PromotionModel();
        $form = $this->createForm(GiftPromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $percentFactory = $factory->createGiftPromotion($promotion);
            $rak = $percentFactory->addProductPromotion();


            return $this->redirectToRoute('admins');
        }

        return $this->render('admin/special_proposition/special_price.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/lipadmin/promotion/createPromotion/globalSpecialPrice/{type}", name="createGlobalSpecialPricePromotion")
     * @param Request $request
     * @param SpecialPropositionAbstractFactory $factory
     * @param string $type
     * @return Response
     */
    public function addGlobalSpecialPricePromotion(Request $request, SpecialPropositionAbstractFactory $factory, string $type): Response
    {
        $promotion = new PromotionModel();
        $options['receipt'] = $type == 'receipt' ? true : false;
        $form = $this->createForm(GlobalSpecialPricePromotionType::class, $promotion,$options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $percentFactory = $factory->createGlobalSpecialPricePromotion($promotion);
            if ($type === 'receipt') {
                $percentFactory->addReceiptPromotion();
            } else {
                $percentFactory->addProductPromotion();
            }

            return $this->redirectToRoute('admins');
        }

        return $this->render('admin/special_proposition/special_price.html.twig',[
            'form' => $form->createView()
        ]);
    }



}