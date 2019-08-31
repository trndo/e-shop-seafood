<?php


namespace App\Controller\admin;


use App\Entity\Product;
use App\Service\EntityService\PhotoService\PhotoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminProductPhoto extends AbstractController
{
    /**
     * @Route("lipadmin/products/changePhoto", methods={"POST"})
     *
     * @param Request $request
     * @param PhotoService $service
     * @return JsonResponse
     */
    public function updateProductPhoto(Request $request, PhotoService $service): JsonResponse
    {
        $post = $request->request;
        $file = $request->files->get('file');
        $data = $service->updatePhoto($file,$post->get('id'),(int)$post->get('product'));

        return new JsonResponse($data,200);
    }

    /**
     * @Route("lipadmin/products/deletePhoto", methods={"DELETE"})
     *
     * @param Request $request
     * @param PhotoService $service
     * @return JsonResponse
     */
    public function deleteProductPhoto(Request $request, PhotoService $service): JsonResponse
    {
        $service->deletePhoto((int)$request->request->get('id'));
        return new JsonResponse([],200);
    }

    /**
     * @Route("/lipadmin/products/{slug}/showPhotos", name="showProductPhotos")
     *
     * @param Product $product
     * @return Response
     */
    public function showProductPhotos(Product $product): Response
    {
        return $this->render('admin/product/show_product_photos.html.twig', [
            'product' => $product
        ]);
    }
}