<?php


namespace App\Controller;


use App\Service\EntityService\ProductService\ProductServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param ProductServiceInterface $service
     * @return Response
     */
    public function home(ProductServiceInterface $service): Response
    {
        $products = $service->getProducts();

        return $this->render('home.html.twig',[
            'products' => $products
        ]);
    }

    /**
     * @Route("/attention", name="attention")
     */
    public function attendUser(): Response
    {
        return $this->render('attention/attention.html.twig');
    }

}