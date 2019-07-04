<?php


namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\CartHandler\CartHandlerInterface;
use App\Service\EntityService\ProductService\ProductServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CartController extends AbstractController
{
    /**
     * @Route("/addToCart",name="addToCart", methods={"POST"})
     * @param Request $request
     * @param CartHandlerInterface $cartHandler
     * @return Response
     */
    public function addToCart(Request $request,CartHandlerInterface $cartHandler): Response
    {
        $slug = $request->request->get('slug');
        $type = $request->request->get('type');
        $quantity = $request->request->get('quantity');
        $session = $request->getSession();
        $item = $cartHandler->getItem($type, $slug);
        $shoppingCart = [];
        if (!$session) {
            $session->set('cart',$shoppingCart);
        }else {
            $shoppingCart = $session->get('cart');
            $shoppingCart[$item->getSlug()] = ['item' => $item,'quantity' =>$quantity];
            $session->set('cart',$shoppingCart);
        }
        return new JsonResponse(['status' => true],200);
    }

    /**
     * @Route("/cart",name="cart")
     *
     * @param SessionInterface $session
     * @return Response
     */
    public function showCart(SessionInterface $session): Response
    {
        return $this->render('cart.html.twig');
    }
}