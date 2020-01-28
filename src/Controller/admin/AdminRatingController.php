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

class AdminRatingController extends AbstractController
{
    /**
     * @Route("/lipadmin/rating", name="rating")
     *
     * @param RatingServiceInterface $ratingService
     * @return Response
     */
    public function rating(RatingServiceInterface $ratingService): Response
    {
        return $this->render('admin/rating/rating.html.twig',[
            'results' => $ratingService->getItems()
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
        $service->updateRating((array) json_decode($rating,true));

        return new JsonResponse([], 200);
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