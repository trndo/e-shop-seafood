<?php


namespace App\Controller;


use App\Service\CartHandler\CartHandlerInterface;
use App\Service\EntityService\ProductService\ProductServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/addToCart",name="addToCart", methods={"POST"})

     * @param Request $request
     * @param CartHandlerInterface $cartHandler
     * @return JsonResponse
     */
    public function addToCart(Request $request,CartHandlerInterface $cartHandler): JsonResponse
    {
        $slug = $request->request->get('slug');
        $type = $request->request->get('type');
        $item = $cartHandler->getItem($type,$slug);

        $session = $request->getSession()->set($item->getSlug(),$item);

        return new JsonResponse([
            'name' => $item->getName(),
            'size' => $type == 'product' ? $item->getProductSize() : null,
            'unit' => $item->getUnit(),
            'titlePhoto' => $item->getTitlePhotoPath(),
            'price' => $item->getPrice()
        ],200);
    }
}