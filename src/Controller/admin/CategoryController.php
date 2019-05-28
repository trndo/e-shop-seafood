<?php


namespace App\Controller\admin;


use App\Form\CategoryForm\CategoryCreateType;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/lipadmin/category/create", name="createCategory")
     */
    public function createCategory(Request $request,EntityManagerInterface $em): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryCreateType::class,$category);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $data = $form->getData();

            $em->persist($data);
            $em->flush();

            return $this->redirectToRoute('');
        }

        return $this->render('',[
            'form' => $form->createView()
        ]);
    }
}