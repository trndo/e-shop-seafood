<?php


namespace App\Controller\admin;


use App\Repository\ProductRepository;
use App\Repository\ReceiptRepository;
use App\Service\SearchService\SearcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminSearchController extends AbstractController
{
    /**
     * @Route("/lipadmin/products/live_search", methods={"GET"})
     *
     * @param Request $request
     * @param SearcherInterface $searcher
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function liveSearchReceiptForProduct(Request $request, SearcherInterface $searcher, ProductRepository $productRepository): Response
    {
        $name = $request->query->get('q');
        $receipts = $searcher->searchByNameForRender($name,$productRepository);

        return $this->render('elements/productsForRating.html.twig',[
            'goods' => $receipts,
            'type' => 'product'
        ]);
    }

    /**
     * @Route("/lipadmin/products/search", name="searchProducts", methods={"GET"})
     *
     * @param Request $request
     * @param SearcherInterface $searcher
     * @param ProductRepository $repository
     * @return JsonResponse
     */
    public function searchProduct(Request $request, SearcherInterface $searcher, ProductRepository $repository): JsonResponse
    {
        $name = $request->query->get('term');
        $products = $searcher->searchByName($name, $repository);

        return new JsonResponse(
            $products
        );
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
}