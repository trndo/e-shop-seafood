<?php


namespace App\Controller\admin;


use App\Entity\Blog;
use App\Form\BlogType;
use App\Service\EntityService\BlogHandler\BlogHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/lipadmin/blog/createMessage", name="createMessage")
     * @param Request $request
     * @param BlogHandlerInterface $blogHandler
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createMessage(Request $request, BlogHandlerInterface $blogHandler)
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Blog $data */
            $data = $form->getData();

            $blogHandler->saveBlog($data);

            return $this->redirectToRoute('showBlog');
        }

        return $this->render('admin/blog/blogForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/lipadmin/blog/editMessage/{id}", name="editMessage")
     * @param Blog $blog
     * @param Request $request
     * @param BlogHandlerInterface $blogHandler
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editMessage(Blog $blog, Request $request, BlogHandlerInterface $blogHandler)
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Blog $data */
            $data = $form->getData();
            $blogHandler->updateBlog($data);

            return $this->redirectToRoute('showBlog');
        }

        return $this->render('admin/blog/blogForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/lipadmin/blog", name="showBlog")
     * @param BlogHandlerInterface $blogHandler
     * @return Response
     */
    public function showBlog(BlogHandlerInterface $blogHandler): Response
    {
        return $this->render('admin/blog/blog.html.twig',[
            'messages' => $blogHandler->getMessages()
        ]);
    }

    /**
     * @Route("/lipadmin/blog/deleteMessage/{id}", name="deleteMessage")
     * @param Blog $blog
     * @param BlogHandlerInterface $blogHandler
     * @return Response
     */
    public function deleteMessage(Blog $blog, BlogHandlerInterface $blogHandler): Response
    {
        $blogHandler->deleteBlog($blog);

        return $this->redirectToRoute('showBlog');
    }
}

