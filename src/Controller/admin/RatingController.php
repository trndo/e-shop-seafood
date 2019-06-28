<?php

namespace App\Controller\admin;

use App\Service\EntityService\ProductService\ProductServiceInterface;
use App\Service\EntityService\ReceiptService\ReceiptServiceInterface;
use App\Service\RatingService\RatingServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RatingController extends AbstractController
{
    /**
     * @Route("/lipadmin/rating", name="rating")
     *
     * @param ProductServiceInterface $productService
     * @param ReceiptServiceInterface $receiptService
     * @return Response
     */
    public function rating(ProductServiceInterface $productService,
                           ReceiptServiceInterface $receiptService): Response
    {
        $result = array_merge($productService->getProductsForRating(),$receiptService->getReceiptsForRating());
        usort($result, function ($p,$r){
            if($p->getRating() == $r->getRating())
                return null;
            return ($p->getRating() < $r->getRating()) ? -1 : 1;
        });

        return $this->render('admin/rating/rating.html.twig',[
            'results' => $result
        ]);
    }


    /**
     * @Route("/lipadmin/rating/update", methods={"POST"})
     *
     * @param Request $request
     * @param RatingServiceInterface $service
     * @return JsonResponse
     */
    public function saveRating(Request $request, RatingServiceInterface $service): JsonResponse
    {
        $rating = $request->getContent();
        $service->updateRating((array)json_decode($rating,true));

        return new JsonResponse([],200);
    }

    /**
     * @Route("/lipadmin/rating/remove", methods={"DELETE"})
     *
     * @param Request $request
     * @param RatingServiceInterface $service
     * @return JsonResponse
     */
    public function removeFromRating(Request $request,RatingServiceInterface $service):JsonResponse
    {
        $id = $request->request->get('id');
        $service->removeFromRate($id);

        return new JsonResponse([],200);
    }
}