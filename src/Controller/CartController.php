<?php


namespace App\Controller;


use App\Service\EntityService\ProductService\ProductServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/addToCart/{slug}",name="addToCart")
     * @param Request $request
     * @param string $slug
     */
    public function addToCart(Request $request,string $slug,ProductServiceInterface $service)
    {

    }
}