<?php


namespace App\Controller\admin;


use App\Entity\SpecialProposition;
use App\Form\SpecialPropositionForm\GiftPromotionType;
use App\Form\SpecialPropositionForm\GlobalSpecialPricePromotionType;
use App\Form\SpecialPropositionForm\PercentPromotionType;
use App\Form\SpecialPropositionForm\SpecialPricePromotionType;
use App\Mapper\PromotionMapper;
use App\Model\PromotionModel;
use App\Service\EntityService\SpecialPropositionService\Factory\SpecialPropositionAbstractFactory;
use App\Service\EntityService\SpecialPropositionService\SpecialPropositionHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $sProposition = new SpecialProposition();
        $options['receipt'] = $type == 'receipt' ? true : false;
        $form = $this->createForm(PercentPromotionType::class, $promotion,$options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $percentFactory = $factory->createPercentPromotion($promotion,$sProposition);
            if ($type == 'receipt') {
                $percentFactory->addReceiptPromotion();
            } else {
                $percentFactory->addProductPromotion();
            }

            return $this->redirectToRoute('promotion');
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
        $sProposition = new SpecialProposition();
        $options['receipt'] = $type == 'receipt' ? true : false;
        $form = $this->createForm(SpecialPricePromotionType::class, $promotion,$options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sProposition = new SpecialProposition();
            $percentFactory = $factory->createSpecialPricePromotion($promotion,$sProposition);
            if ($type === 'receipt') {
                $percentFactory->addReceiptPromotion();
            } else {
                $percentFactory->addProductPromotion();
            }

            return $this->redirectToRoute('promotion');
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
        $sProposition = new SpecialProposition();
        $options['receipt'] = $type == 'receipt' ? true : false;
        $form = $this->createForm(GiftPromotionType::class, $promotion, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $percentFactory = $factory->createGiftPromotion($promotion,$sProposition);
            if ($type === 'receipt') {
                $percentFactory->addReceiptPromotion();
            } else {
                $percentFactory->addProductPromotion();
            }
            return $this->redirectToRoute('promotion');
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
        $sProposition = new SpecialProposition();
        $options['receipt'] = $type == 'receipt' ? true : false;
        $form = $this->createForm(GlobalSpecialPricePromotionType::class, $promotion,$options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $globalSpecialPrice = $factory->createGlobalSpecialPricePromotion($promotion,$sProposition);
            if ($type === 'receipt') {
                $globalSpecialPrice->addReceiptPromotion();

            } else {
                $globalSpecialPrice->addProductPromotion();
            }
            return $this->redirectToRoute('promotion');
        }

        return $this->render('admin/special_proposition/global_special_price.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/lipadmin/promotion/editPercentPromotion/{type}/{id}", name="editPercentPromotion")
     * @param Request $request
     * @param SpecialProposition $proposition
     * @param SpecialPropositionAbstractFactory $factory
     * @param string $type
     * @return Response
     */
    public function editPercentPromotion(Request $request, SpecialProposition $proposition, SpecialPropositionAbstractFactory $factory, string $type): Response
    {
        $model = $type == 'receipt' ? PromotionMapper::entityToPercentReceiptModel($proposition) : PromotionMapper::entityToPercentProductModel($proposition);
        $options['receipt'] = $type == 'receipt' ? true : false ;
        $options['update'] = true;

        $form = $this->createForm(PercentPromotionType::class,$model,$options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $percentFactory = $factory->createPercentPromotion($model,$proposition);
            if ($type === 'receipt') {
                $percentFactory->addReceiptPromotion();
            } else {
                $percentFactory->addProductPromotion();
            }
            return $this->redirectToRoute('promotion');
        }
        return $this->render('admin/special_proposition/percent.html.twig',[
           'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/lipadmin/promotion/editSpecialPrice/{type}/{id}", name="editSpecialPrice")
     * @param Request $request
     * @param SpecialProposition $proposition
     * @param SpecialPropositionAbstractFactory $factory
     * @param string $type
     * @return Response
     */
    public function editSpecialPrice(Request $request, SpecialProposition $proposition, SpecialPropositionAbstractFactory $factory, string $type): Response
    {

        $model = $type == 'receipt' ? PromotionMapper::entityToSpecialPriceReceiptModel($proposition) : PromotionMapper::entityToSpecialPriceProductModel($proposition);
        $options['receipt'] = $type == 'receipt' ? true : false ;
        $options['update'] = true;

        $form = $this->createForm(SpecialPricePromotionType::class,$model,$options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $specialPriceFactory = $factory->createSpecialPricePromotion($model,$proposition);
            if ($type === 'receipt') {
                $specialPriceFactory->addReceiptPromotion();
            } else {
                $specialPriceFactory->addProductPromotion();
            }
            return $this->redirectToRoute('promotion');
        }
        return $this->render('admin/special_proposition/special_price.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/lipadmin/promotion/editGlobalSpecialPrice/{type}/{id}", name="editGlobalSpecialPrice")
     * @param Request $request
     * @param SpecialProposition $proposition
     * @param SpecialPropositionAbstractFactory $factory
     * @param string $type
     * @return Response
     */
    public function editGlobalSpecialPrice(Request $request, SpecialProposition $proposition, SpecialPropositionAbstractFactory $factory, string $type): Response
    {

        $model = $type == 'receipt' ? PromotionMapper::entityToGlobalPriceReceiptModel($proposition) : PromotionMapper::entityToGlobalPriceProductModel($proposition);
        $options['receipt'] = $type == 'receipt' ? true : false ;
        $options['update'] = true;

        $form = $this->createForm(GlobalSpecialPricePromotionType::class,$model,$options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $globalSpecialPrice = $factory->createGlobalSpecialPricePromotion($model,$proposition);
            if ($type === 'receipt') {
                $globalSpecialPrice->addReceiptPromotion();
            } else {
                $globalSpecialPrice->addProductPromotion();
            }
            return $this->redirectToRoute('promotion');
        }
        return $this->render('admin/special_proposition/global_special_price.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/lipadmin/promotion/editGift/{type}/{id}", name="editGift")
     * @param Request $request
     * @param SpecialProposition $proposition
     * @param SpecialPropositionAbstractFactory $factory
     * @param string $type
     * @return Response
     */
    public function editGift(Request $request, SpecialProposition $proposition, SpecialPropositionAbstractFactory $factory, string $type): Response
    {

        $model = $type == 'receipt' ? PromotionMapper::entityToGiftReceiptModel($proposition) : PromotionMapper::entityToGiftProductModel($proposition);
        $options['receipt'] = $type == 'receipt' ? true : false ;
        $options['update'] = true;

        $form = $this->createForm(GiftPromotionType::class,$model,$options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $giftFactory = $factory->createGlobalSpecialPricePromotion($model,$proposition);
            if ($type === 'receipt') {
                $giftFactory->addReceiptPromotion();
            } else {
                $giftFactory->addProductPromotion();
            }
            return $this->redirectToRoute('promotion');
        }
        return $this->render('admin/special_proposition/gift.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/lipadmin/promotion/delete/{id}", name="deletePromotion")
     * @param SpecialPropositionHandlerInterface $handler
     * @param SpecialProposition $proposition
     * @param int $id
     * @return Response
     */
    public function deletePromotion(SpecialPropositionHandlerInterface $handler,SpecialProposition $proposition,int $id): Response
    {
        $handler->removeSpecialProposition($id);

        return $this->redirectToRoute('promotion');
    }


    /**
     * @Route("/lipadmin/promotion/activatePromotion", name="activatePromotion", methods={"POST"})
     *
     * @param Request $request
     * @param SpecialPropositionHandlerInterface $handler
     * @return JsonResponse
     */
    public function activatePromotion(Request $request,SpecialPropositionHandlerInterface $handler): JsonResponse
    {
        $promotionStatus = $handler->activateSpecialProposition($request->request->get('id'));

        return new JsonResponse(['status' => $promotionStatus],200);
    }






}