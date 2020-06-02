<?php


namespace App\Controller;


use App\Entity\Product;
use App\Entity\Receipt;
use App\Service\EntityService\BlogHandler\BlogHandlerInterface;
use App\Service\EntityService\ProductService\ProductServiceInterface;
use App\Service\EntityService\ReceiptService\ReceiptServiceInterface;
use App\Service\RatingService\RatingServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param RatingServiceInterface $ratingService
     * @param BlogHandlerInterface $blogHandler
     * @return Response
     */
    public function home(RatingServiceInterface $ratingService, BlogHandlerInterface $blogHandler): Response
    {
        return $this->render('home.html.twig', [
            'items' => $ratingService->getItems(),
            'messages' => $blogHandler->getMessages()
        ]);
    }

    /**
     * @Route("/attention", name="attention")
     */
    public function attendUser(): Response
    {
        return $this->render('attention/attention.html.twig');
    }

    /**
     * @Route("/makeOrderAttention" , name="attention_order")
     * @return Response
     */
    public function attendAfterMakeOrder(): Response
    {
        return $this->render('attention/attentionMakeOrder.html.twig');
    }


    /**
     * For dev purpose only
     * @Route("/404")
     * @return Response
     */
    public function error(): Response
    {
        return $this->render('error/404.html.twig');
    }

}
