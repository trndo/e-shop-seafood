<?php


namespace App\Controller\admin;


use App\Form\SpecialPropositionForm\GiftPromotionType;
use App\Form\SpecialPropositionForm\GlobalSpecialPricePromotionType;
use App\Form\SpecialPropositionForm\PercentPromotionType;
use App\Form\SpecialPropositionForm\SpecialPricePromotionType;
use App\Model\PromotionModel;
use App\Service\EntityService\SpecialPropositionService\Factory\SpecialPropositionAbstractFactory;
use App\Service\EntityService\SpecialPropositionService\SpecialPropositionHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpecialPropositionController extends AbstractController
{
    /**
     * @Route("/lipadmin/promotion", name="promotion")
     * @param SpecialPropositionHandlerInterface $handler
     * @return Response
     */
    public function showPromotions(SpecialPropositionHandlerInterface $handler): Response
    {
        $specialPropositions = $handler->getAllSpecialProposition();

        return $this->render('admin/special_proposition/promotion.html.twig',[
            'specialPropositions' => $specialPropositions
        ]);
    }

    /**
     * @Route("/lipadmin/promotion/createPromotion", name="createPromotion")
     *
     * @return Response
     */
    public function createPromotion(): Response
    {
        return $this->render('admin/special_proposition/create_promotion.html.twig');
    }

    /**
     * @Route("/lipadmin/promotion/createPromotion/{promotionType}", name="createPromotionType")
     *
     * @param string $promotionType
     * @return Response
     */
    public function createPromotionType(string $promotionType): Response
    {
        return $this->render('admin/special_proposition/type_promotion.html.twig',[
            'promotionType' => $promotionType
        ]);
    }

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
        $options['receipt'] = $type == 'receipt' ? true : false;
        $form = $this->createForm(GiftPromotionType::class, $promotion, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $percentFactory = $factory->createGiftPromotion($promotion);
            if ($type === 'receipt') {
                $percentFactory->addReceiptPromotion();
            } else {
                $percentFactory->addProductPromotion();
            }
            return $this->redirectToRoute('admins');
        }

        return $this->render('admin/special_proposition/gift.html.twig',[
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

        return $this->render('admin/special_proposition/global_special_price.html.twig',[
            'form' => $form->createView()
        ]);
    }





}