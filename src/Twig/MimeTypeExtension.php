<?php


namespace App\Twig;


use App\Entity\Receipt;
use Gedmo\Uploadable\MimeType\MimeTypeGuesser;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MimeTypeExtension extends AbstractExtension
{
    private $uploadDir;

    public function __construct($uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getMimeType', [$this, 'getMimeType']),
        ];
    }

    public function getMimeType(?string $fileName): string
    {
        $mimeType = new MimeTypeGuesser();

        return $mimeType->guess('/uploads/receipts/'.$fileName);

    }
}