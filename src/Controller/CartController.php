<?php


namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\CartHandler\CartHandlerInterface;
use App\Service\EntityService\ProductService\ProductServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CartController extends AbstractController
{
    /**
     * @Route("/chooseOrder",name="chooseOrder", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function chooseOrderType(Request $request): JsonResponse
    {
        $orderType = json_decode($request->getContent(),true)['orderType'];
        $request->getSession()->set('chooseOrder',$orderType);
        return new JsonResponse([
            'status' => true
        ], 200);
    }

    /**
     * @Route("/addToCart",name="addToCart", methods={"POST"})
     * @param Request $request
     * @param CartHandlerInterface $cartHandler
     * @return JsonResponse
     */
    public function addToCart(Request $request,CartHandlerInterface $cartHandler): JsonResponse
    {
        $response = $cartHandler->addItemToCart($request->request);
        return new JsonResponse($response,200);
    }

    /**
     * @Route("/cart",name="cart")
     *
     * @param Request $request
     * @param CartHandlerInterface $cartHandler
     * @return Response
     */
    public function showCart(Request $request,CartHandlerInterface $cartHandler): Response
    {
        $items = $cartHandler->getItems();
        return $this->render('cart.html.twig',[
            'items' => $items
        ]);
    }

    /**
     * @Route("/removeFromCart",name="removeFromCart", methods={"DELETE"})
     * @param Request $request
     * @param CartHandlerInterface $handler
     * @return JsonResponse
     */
    public function removeFromCart(Request $request, CartHandlerInterface $handler): JsonResponse
    {
        $handler->removeFromCart($request->request);
        $totalSum = $request->getSession()->get('totalSum');
        return new JsonResponse([
            'status' => true,
            'totalSum' => $totalSum
        ],200);

    }

    /**
     * @Route("/changeQuantity",name="changeQuantity", methods={"POST"})
     *
     * @param Request $request
     * @param CartHandlerInterface $handler
     * @return JsonResponse
     */
    public function changeQuantity(Request $request, CartHandlerInterface $handler): JsonResponse
    {
        $response = $handler->changeItemQuantity($request->request);
        return new JsonResponse($response);
    }

    /**
     * @Route("/changeOrderType", name="changeOrderType", methods={"GET"})
     *
     * @param SessionInterface $session
     * @return JsonResponse
     */
    public function changeOrderType(SessionInterface $session): JsonResponse
    {
        $session->set('chooseOrder',!$session->get('chooseOrder'));
        $session->set('cart', []);
        $session->set('totalSum', 0);

        return new JsonResponse(true, Response::HTTP_OK);
    }
}