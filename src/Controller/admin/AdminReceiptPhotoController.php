<?php

namespace App\Controller\admin;

use App\Entity\Receipt;
use App\Service\EntityService\PhotoService\PhotoService;
use App\Service\EntityService\PhotoService\ReceiptPhotoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminReceiptPhotoController extends AbstractController
{
    /**
     * @Route("lipadmin/receipts/changePhoto", methods={"POST"})
     *
     * @param Request $request
     * @param ReceiptPhotoService $service
     * @return JsonResponse
     */
    public function updateReceiptsPhoto(Request $request,ReceiptPhotoService $service): JsonResponse
    {
        $post = $request->request;
        $file = $request->files->get('file');
        $data = $service->updatePhoto($file,$post->get('id'),(int)$post->get('product'));

        return new JsonResponse($data,200);
    }

    /**
     * @Route("lipadmin/receipts/deletePhoto", methods={"DELETE"})
     *
     * @param Request $request
     * @param ReceiptPhotoService $service
     * @return JsonResponse
     */
    public function deleteReceiptPhoto(Request $request, ReceiptPhotoService $service): JsonResponse
    {
        $service->deletePhoto((int)$request->request->get('id'));
        return new JsonResponse([],200);
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
}