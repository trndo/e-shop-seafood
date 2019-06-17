<?php

namespace App\Service\EntityService\PhotoService;

use App\Entity\Photo;
use App\Entity\Product;
use App\Repository\PhotoRepository;
use App\Service\FileSystemService\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PhotoService implements PhotoServiceInterface
{
    private const PRODUCT_FOLDER =  'products/';

    private $em;

    private  $fileUploader;

    public function __construct(EntityManagerInterface $entityManager,
                                FileUploader $fileUploader)
    {
        $this->em = $entityManager;
        $this->fileUploader = $fileUploader;
    }

    /**
     * @param UploadedFile|null $file
     * @param int|null $id
     * @param int|null $product
     * @return string|null
     */
    public function updatePhoto(?UploadedFile $file, ?int $id, ?int $product): ?string
    {
        if(isset($id)){
           $photo = $this->em->getRepository(Photo::class)->find($id);
           if($photo && $file instanceof UploadedFile){
               $this->fileUploader->uploadFile($file,self::PRODUCT_FOLDER,$photo->getHash());
               return $photo->getHash();
           }
           else return null;
        }
        else {
            if($file instanceof UploadedFile && isset($product)){
                $photo = new Photo();
                $photo->setHash($this->fileUploader->uploadFile($file,self::PRODUCT_FOLDER));
                $photo->setProduct($this->em->getRepository(Product::class)->find($product));
                $this->em->persist($photo);
                $this->em->flush();
                return $photo->getHash();
            } else return null;
        }
    }

    /**
     * @param int|null $id
     */
    public function deletePhoto(?int $id): void
    {
        if($id) {
            $photo = $this->em->getRepository(Photo::class)->find($id);
            if($photo) {
                $path = $this->fileUploader->getUploadDir().self::PRODUCT_FOLDER.$photo->getHash();
                if(file_exists($path) && is_file($path))
                    unlink($path);
                $this->em->remove($photo);
                $this->em->flush();
            }
        }
    }
}