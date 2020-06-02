<?php

namespace App\Service\EntityService\PhotoService;

use App\Repository\PhotoRepository;
use App\Service\FileSystemService\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface PhotoServiceInterface
{
    public function __construct(EntityManagerInterface $entityManager, FileUploader $fileUploader);

    public function updatePhoto(?UploadedFile $file, ?int $id, ?int $product): ?array;

    public function deletePhoto(int $id): void;
}