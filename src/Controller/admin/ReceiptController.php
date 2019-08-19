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
     * @param CategoryService $categoryService
     * @param Request $request
     * @return Response
     */
    public function addProductsForReceipt(Receipt $receipt,
                                          CategoryService $categoryService,
                                          Request $request): Response
    {
        $relatedProducts = $receipt->getProducts();
        $categories = $categoryService->getCategoryByCriteria(['type' => 'products']);

        return $this->render('admin/receipt/addProductsToReceipt.html.twig',[
            'relatedProducts' => $relatedProducts,
            'categories' => $categories,
            'receipt' => $receipt
        ]);
    }

    /**
     * @Route("lipadmin/receipts/{slug}/saveProducts", methods={"POST"})
     *
     * @param Request $request
     * @param Receipt $receipt
     * @param ReceiptService $receiptService
     * @return JsonResponse
     */
    public function saveProductsForReceipt(Receipt $receipt,Request $request, ReceiptService $receiptService): JsonResponse
    {
        $receiptService->addProductsInReceipt((array)$request->request->get('products'), $receipt);
        return  new JsonResponse([],200);
    }

    /**
     * @Route("lipadmin/receipts/{slug}/checkProducts", methods={"GET"})
     * @param Receipt $receipt
     * @return JsonResponse
     */
    public function checkIfReceiptHasProducts(Receipt $receipt): JsonResponse
    {
        $products = $receipt->getProducts();
        $data = ProductMapper::fromCollectionToArray($products);

        return new JsonResponse($data,200);
    }

    /**
     * @Route("/lipadmin/receipts/live_search", methods={"GET"})
     *
     * @param Request $request
     * @param SearcherInterface $searcher
     * @param ReceiptRepository $receiptRepository
     * @return Response
     */
    public function liveSearchReceipt(Request $request, SearcherInterface $searcher, ReceiptRepository $receiptRepository): Response
    {
        $name = $request->query->get('q');
        $receipts = $searcher->searchByNameForRender($name,$receiptRepository);

        return $this->render('elements/productsForRating.html.twig',[
            'goods' => $receipts,
            'type' => 'receipt'
        ]);
    }

    /**
     * @Route("lipadmin/receipts/{slug}/addSales", name="addReceiptSales")
     *
     * @param CategoryService $categoryService
     * @param Receipt $receipt
     * @return Response
     */
    public function addAdditionalSales(Receipt $receipt, CategoryService $categoryService): Response
    {
        $categories = $categoryService->getCategoryByCriteria(['type' => 'products']);
        return $this->render('admin/receipt/additionalSales.html.twig',[
            'additionalProds' => $receipt->getProductSales(),
            'categories' => $categories,
            'receipt' => $receipt
        ]);
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
        return  new JsonResponse([],200);
    }

}