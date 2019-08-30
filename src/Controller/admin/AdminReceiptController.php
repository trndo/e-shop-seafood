<?php

namespace App\Controller\admin;

use App\Entity\Receipt;
use App\Form\ReceiptType;
use App\Mapper\ProductMapper;
use App\Mapper\ReceiptMapper;
use App\Model\ReceiptModel;
use App\Repository\ReceiptRepository;
use App\Service\EntityService\CategoryService\CategoryService;
use App\Service\EntityService\ProductService\ProductServiceInterface;
use App\Service\EntityService\ReceiptService\ReceiptService;
use App\Service\EntityService\ReceiptService\ReceiptServiceInterface;
use App\Service\SearchService\SearcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminReceiptController extends AbstractController
{
    /**
     * @Route("/lipadmin/receipts/", name="receipts")
     *
     * @param ReceiptServiceInterface $service
     * @param Request $request
     * @return Response
     */
    public function receipts(ReceiptServiceInterface $service, Request $request): Response
    {
        $name = $request->query->get('name',null);
        $category = $request->query->getInt('category',null);
        $receipts = $service->getReceiptsByCriteria($name, $category);
        $categories = $service->getReceiptsCategories();

        return $this->render('admin/receipt/receipts.html.twig',[
            'receipts' => $receipts,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/lipadmin/receipts/activate", name="activteReceipt")
     *
     * @param Request $request
     * @param ReceiptService $service
     * @return JsonResponse
     */
    public function activateReceipt(Request $request,ReceiptService $service): JsonResponse
    {
        $service->activateReceipt($request->request->get('id'));
        return new JsonResponse(['status' => true],200);
    }

    /**
     * @Route("/lipadmin/receipts/{slug}/show", name="showReceipt")
     *
     * @param Receipt $receipt
     * @return Response
     */
    public function showReceipt(Receipt $receipt): Response
    {
        return $this->render('admin/receipt/show_receipt.html.twig', [
            'receipt' => $receipt
        ]);
    }


}