<?php

namespace App\Controller\admin;

use App\Service\EntityService\PhotoService\PhotoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PhotoController extends AbstractController
{
    /**
     * @Route("lipadmin/products/changePhoto", methods={"POST"})
     *
     * @param Request $request
     * @param PhotoService $service
     * @return JsonResponse
     */
    public function updatePhoto(Request $request, PhotoService $service): JsonResponse
    {
        $post = $request->request;
        $file = $request->files->get('file');
        $hash = $service->updatePhoto($file,$post->get('id'),(int)$post->get('product'));

        return new JsonResponse(['hash' => $hash],200);
    }

    /**
     * @Route("lipadmin/products/deletePhoto", methods={"DELETE"})
     *
     * @param Request $request
     * @param PhotoService $service
     * @return JsonResponse
     */
    public function deletePhoto(Request $request, PhotoService $service): JsonResponse
    {
        $service->deletePhoto((int)$request->request->get('id'));
        return new JsonResponse([],200);
    }
}