<?php

namespace App\Service\EntityService\PhotoService;

use App\Entity\Photo;
use App\Entity\Receipt;
use App\Service\FileSystemService\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ReceiptPhotoService implements PhotoServiceInterface
{
    private const RECEIPT_FOLDER =  'receipts/';

    private $em;

    private $fileUploader;

    public function __construct(EntityManagerInterface $entityManager, FileUploader $fileUploader)
    {
       $this->em = $entityManager;
       $this->fileUploader = $fileUploader;
    }

    public function updatePhoto(?UploadedFile $file, ?int $id, ?int $product): ?string
    {
        if(isset($id)){
            $photo = $this->em->getRepository(Photo::class)->find($id);
            if($photo && $file instanceof UploadedFile){
                $this->fileUploader->uploadFile($file,self::RECEIPT_FOLDER,$photo->getHash());
                return $photo->getHash();
            }
            else return null;
        }
        else {
            if($file instanceof UploadedFile && isset($product)){
                $photo = new Photo();
                $photo->setHash($this->fileUploader->uploadFile($file,self::RECEIPT_FOLDER));
                $photo->setReceipt($this->em->getRepository(Receipt::class)->find($product));
                $this->em->persist($photo);
                $this->em->flush();
                return $photo->getHash();
            } else return null;
        }
    }

    public function deletePhoto(int $id): void
    {
        if($id) {
            $photo = $this->em->getRepository(Photo::class)->find($id);
            if($photo) {
                $path = $this->fileUploader->getUploadDir().self::RECEIPT_FOLDER.$photo->getHash();
                if(file_exists($path) && is_file($path))
                    unlink($path);
                $this->em->remove($photo);
                $this->em->flush();
            }
        }
    }
}