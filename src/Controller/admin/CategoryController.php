<?php


namespace App\Controller\admin;


use App\Form\CategoryForm\CategoryCreateType;
use App\Entity\Category;
use App\Form\CategoryForm\CategoryUpdateType;
use App\Mapper\CategoryMapper;
use App\Repository\CategoryRepository;
use App\Service\EntityService\CategoryService\CategoryServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/lipadmin/category/create", name="createCategory")
     *
     * @param Request $request
     * @param CategoryServiceInterface $categoryService
     * @return Response
     */
    public function createCategory(Request $request, CategoryServiceInterface $categoryService): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryCreateType::class,$category);

        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();

                $categoryService->addCategory($data);

                return $this->redirectToRoute('category');
            }

        return $this->render('admin/category/create_category.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/lipadmin/category/", name="category")
     *
     * @param CategoryServiceInterface $categoryService
     * @return Response
     */
    public function showCategories(CategoryServiceInterface $categoryService): Response
    {
        $categories = $categoryService->getAllCategories();

        return $this->render('admin/category/categories.html.twig',[
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/lipadmin/category/edit/{slug}", name="editCategory")
     *
     * @param Request $request
     * @param Category $category
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function editCategory(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $model = CategoryMapper::entityToModel($category);

        $form = $this->createForm(CategoryUpdateType::class,$model);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            CategoryMapper::modelToEntity($model,$category);

            $entityManager->flush();

            return $this->redirectToRoute('category');
        }

        return $this->render('admin/category/create_category.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/lipadmin/category/delete/{slug}", name="deleteCategory")
     *
     * @param CategoryServiceInterface $categoryService
     * @param Category $category
     * @return Response
     */
    public function deleteCategory(CategoryServiceInterface $categoryService, Category $category): Response
    {
        $categoryService->deleteCategory($category);

        return $this->redirectToRoute('category');
    }

}