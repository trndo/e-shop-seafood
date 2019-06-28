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
     * @Route("/addToCart/{type}/{slug}",name="addToCart")
     * @param Request $request
     * @param string $type
     * @param string $slug
     * @param CartHandlerInterface $cartHandler
     * @return JsonResponse
     */
    public function addToCart(Request $request,string $type,string $slug,CartHandlerInterface $cartHandler)
    {
        $item = $cartHandler->getItem($type,$slug);
        $session = $request->getSession();
        $session->set($slug,$item);
        dd($session);

    }
}