<?php


namespace App\Service\FileSystemService;


use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploadFileInterface
{
    /**
     * Move file into folder and hash it
     *
     * @param UploadedFile|null $file
     * @param string $folder
     * @param null $hash
     * @return string|null
     */
    public function uploadFile(?UploadedFile $file, string $folder, $hash = null): ?string ;

    /**
     * Get upload directory in the project
     *
     * @return string|null
     */
    public function getUploadDir(): ?string ;
}