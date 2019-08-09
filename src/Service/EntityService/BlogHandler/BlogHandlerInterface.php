<?php


namespace App\Service\EntityService\BlogHandler;


use App\Entity\Blog;

interface BlogHandlerInterface
{
    /**
     * @param Blog $blog
     * @return mixed
     */
    public function saveBlog(?Blog $blog): void;

    /**
     * @param Blog $blog
     * @return mixed
     */
    public function updateBlog(?Blog $blog): void ;

    public function getMessages(): ?array ;

    public function deleteBlog(?Blog $blog): void ;
}