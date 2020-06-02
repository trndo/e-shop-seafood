<?php

namespace App\Controller\admin;

use App\Repository\CategoryRepository;
use App\Repository\SupplyRepository;
use App\Service\EntityService\OrderInfoHandler\OrderInfoInterface;
use App\Service\EntityService\ProductService\ProductServiceInterface;
use App\Service\EntityService\SupplyService\SupplyServiceInterface;
use App\Service\SearchService\SearcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminSupplyController extends AbstractController
{
    /**
     * @Route("/lipadmin/supply/edit", name="supplyEdit", methods={"POST"})
     *
     * @param Request $request
     * @param SupplyServiceInterface $supplyService
     * @return JsonResponse
     */
    public function editProductSupply(Request $request, SupplyServiceInterface $supplyService): JsonResponse
    {
        $data = $request->getContent();
        $supplyService->editSupply(json_decode($data, true));

        return new JsonResponse([
            'status' => true
        ], 200);
    }

    /**
     * @Route("/lipadmin/supply", name="supply")
     *
     * @param Request $request
     * @param SupplyServiceInterface $supplyService
     * @param ProductServiceInterface $productService
     * @return Response
     */
    public function showSupplies(Request $request, SupplyServiceInterface $supplyService, ProductServiceInterface $productService, OrderInfoInterface $orderInfo): Response
    {
        $category = $request->query->getInt('category', null);
        $supplies = $supplyService->findByCriteria($category);
        $categories = $productService->getProductsCategories();
        $todayOrders = $orderInfo->getOrdersForToday();

        return $this->render('admin/supply/show.html.twig', [
            'supplies' => $supplies,
            'categories' => $categories,
            'todayOrders' => $todayOrders
        ]);
    }

    /**
     * @Route("/lipadmin/supply/live_search", methods={"GET"}, name="searchByName")
     *
     * @param Request $request
     * @
     * @param SearcherInterface $searcher
     * @param SupplyRepository $repository
     * @return Response
     */
    public function searchByName(Request $request, SearcherInterface $searcher, SupplyRepository $repository): Response
    {
        $name = $request->query->get('q');
        $supplies = $searcher->searchByNameForRender($name, $repository);

        return $this->render('elements/supply_card.html.twig', [
            'supplies' => $supplies
        ]);
    }

}