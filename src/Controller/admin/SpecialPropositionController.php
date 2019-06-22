<?php


namespace App\Controller\admin;


use App\Form\SpecialPropositionForm\PercentPromotionType;
use App\Model\PromotionModel;
use App\Service\EntityService\SpecialPropositionService\Factory\SpecialPropositionAbstractFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpecialPropositionController extends AbstractController
{
    /**
     * @Route("/lipadmin/promotion/createPromotion/percent/{type}", name="createReceipt")
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
            if ($type === 'receipt') {
                $percentFactory->addReceiptPromotion();
            }
            $percentFactory->addProductPromotion();

            return $this->redirectToRoute('admins');
        }

        return $this->render('admin/special_proposition/percent.html.twig',[
            'form' => $form->createView()
        ]);
    }
}