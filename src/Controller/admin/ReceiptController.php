<?php

namespace App\Controller\admin;

use App\Entity\Receipt;
use App\Form\ReceiptType;
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

class ReceiptController extends AbstractController
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
     * @Route("/lipadmin/receipts/", name="receipts")
     *
     * @param ReceiptService $service
     * @param Request $request
     * @return Response
     */
    public function receipts(ReceiptService $service, Request $request): Response
    {
        $receipts = $service->getReceiptsByCriteria($request->query->all(),['status' => 'ASC']);

        return $this->render('admin/receipt/receipts.html.twig',[
            'receipts' => $receipts
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
        $form = $this->createForm(ReceiptType::class, ReceiptMapper::entityToModel($receipt),$options);

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

    /**
     * @Route(path="/lipadmin/receipts/{slug}/showPhotos", name="showReceiptPhotos")
     *
     * @param Receipt $receipt
     * @return Response
     */
    public function showReceiptPhotos(Receipt $receipt): Response
    {
        return $this->render('admin/receipt/show_receipt_photos.html.twig',[
            'receipt' => $receipt
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
     * @Route("/lipadmin/receipts/search", name="searchReceipts", methods={"GET"})
     *
     * @param Request $request
     * @param SearcherInterface $searcher
     * @param ReceiptRepository $repository
     * @return JsonResponse
     */
    public function searchReceipt(Request $request, SearcherInterface $searcher, ReceiptRepository $repository): JsonResponse
    {
        $name = $request->query->get('term');
        $receipts = $searcher->searchByName($name, $repository);

        return new JsonResponse(
            $receipts
        );
    }

    /**
     * @Route("lipadmin/receipts/{slug}/addProducts", name="addProducts")
     *
     * @param Receipt $receipt
     * @param ProductServiceInterface $service
     * @param CategoryService $categoryService
     * @param Request $request
     * @return Response
     */
    public function addProductsForReceipt(Receipt $receipt,
                                          ProductServiceInterface $service,
                                          CategoryService $categoryService,
                                          Request $request): Response
    {
        $products = $service->getProductsByCriteria($request->query->all());
        $relatedProducts = $receipt->getProducts();
        $categories = $categoryService->getCategoryByCriteria(['type' => 'products']);

        return $this->render('admin/receipt/addProductsToReceipt.html.twig',[
            'products' => $products,
            'relatedProducts' => $relatedProducts,
            'categories' => $categories
        ]);
    }

}