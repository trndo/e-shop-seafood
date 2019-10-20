<?php


namespace App\Controller\admin;

use App\Entity\Receipt;
use App\Form\ReceiptType;
use App\Mapper\ReceiptMapper;
use App\Model\ReceiptModel;
use App\Service\EntityService\CategoryService\CategoryService;
use App\Service\EntityService\ReceiptService\ReceiptService;
use App\Service\EntityService\ReceiptService\ReceiptServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminReceiptHandlerController extends AbstractController
{
    /**
     * @Route("/lipadmin/receipts/create", name="createReceipt")
     *
     * @param Request $request
     * @param ReceiptService $service
     * @return Response
     */
    public function createReceipt(Request $request, ReceiptService $service): Response
    {
        $receiptModel = new ReceiptModel();
        $form = $this->createForm(ReceiptType::class,$receiptModel);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $service->saveReceipt($form->getData());
            return $this->redirectToRoute('receipts');
        }

        return $this->render('admin/receipt/create_receipt.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/lipadmin/receipts/{slug}/update", name="updateReceipt")
     *
     * @param Receipt $receipt
     * @param Request $request
     * @param ReceiptService $service
     * @return Response
     */
    public function updateReceipt(Receipt $receipt,Request $request, ReceiptService $service): Response
    {
        $options['update'] = true;
        $form = $this->createForm(ReceiptType::class, ReceiptMapper::entityToModel($receipt), $options);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $service->updateReceipt($receipt,$form->getData());
            return $this->redirectToRoute('receipts');
        }

        return $this->render('admin/receipt/update_recipe.html.twig',[
            'receipt' => $receipt,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/lipadmin/receipts/{slug}/delete", name="deleteReceipt")
     *
     * @param Receipt $receipt
     * @param ReceiptServiceInterface $service
     * @return RedirectResponse
     */
    public function deleteReceipt(Receipt $receipt, ReceiptServiceInterface $service): RedirectResponse
    {
        $service->deleteReceipt($receipt);
        return $this->redirectToRoute('receipts');
    }

    /**
     * @Route("lipadmin/receipts/{slug}/saveSales", methods={"POST"})
     *
     * @param Request $request
     * @param Receipt $receipt
     * @param ReceiptService $receiptService
     * @return JsonResponse
     */
    public function saveSalesForReceipt(Receipt $receipt,Request $request, ReceiptService $receiptService): JsonResponse
    {
        $receiptService->addSalesInReceipt((array)$request->request->get('products'), $receipt);
        return new JsonResponse([],200);
    }

    /**
     * @Route("lipadmin/receipts/{slug}/addProducts", name="addProducts")
     *
     * @param Receipt $receipt
     * @param CategoryService $categoryService
     * @return Response
     */
    public function addProductsForReceipt(Receipt $receipt,
                                          CategoryService $categoryService): Response
    {
        $relatedProducts = $receipt->getProducts();
        $categories = $categoryService->getCategoryByCriteria(['type' => 'products']);

        return $this->render('admin/receipt/addProductsToReceipt.html.twig',[
            'relatedProducts' => $relatedProducts,
            'categories' => $categories,
            'receipt' => $receipt
        ]);
    }
}