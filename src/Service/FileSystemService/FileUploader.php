<?php


namespace App\Service\FileSystemService;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader implements UploadFileInterface
{

/**
* @var string $uploadsDir
     */
    private $uploadsDir;

    /**
     * FileUploader constructor.
     * @param $uploadsDir
     */
    public function __construct($uploadsDir)
    {
        $this->uploadsDir = $uploadsDir;
    }

    /**
     * @param UploadedFile $file
     * @param string $folder
     * @param null $hash
     * @return string
     */
    public function uploadFile(?UploadedFile $file,string $folder, $hash = null): ?string
    {
        if (!$file  instanceof UploadedFile)
            return null;

        try{
            if ($hash)
                $newFileName = $hash;
            else
                $newFileName = $this->hashFile($file->getClientOriginalName()).'.'.$file->getClientOriginalExtension();

            $file->move($this->uploadsDir.$folder,$newFileName);
        } catch (FileException $exception) {
            return null;
        }

        return $newFileName;
    }

    /**
     * @return string
     */
    public function getUploadDir(): ?string
    {
        return $this->uploadsDir;
    }

    /**
     * @param string $filename
     * @return string
     */
    private function hashFile(string $filename): string
    {
        return \md5(\uniqid($filename));
    }
}