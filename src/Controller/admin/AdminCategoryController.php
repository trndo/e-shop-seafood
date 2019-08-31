<?php


namespace App\Controller\admin;


use App\Entity\Category;
use App\Form\CategoryType;
use App\Mapper\CategoryMapper;
use App\Model\CategoryModel;
use App\Repository\CategoryRepository;
use App\Service\EntityService\CategoryService\CategoryServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AbstractController
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
        $categoryModel = new CategoryModel();
        $form = $this->createForm(CategoryType::class,$categoryModel);

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
     * @Route("/lipadmin/category", name="category")
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
     * @param CategoryServiceInterface $categoryService
     * @return Response
     */
    public function editCategory(Request $request, Category $category, CategoryServiceInterface $categoryService): Response
    {
        $model = CategoryMapper::entityToModel($category);

        $form = $this->createForm(CategoryType::class,$model);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $categoryService->updateCategory($category,$model);

            return $this->redirectToRoute('category');
        }

        return $this->render('admin/category/edit_category.html.twig',[
            'form' => $form->createView(),
            'category' => $category
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