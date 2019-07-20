<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ItemSizeExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getSize', [$this, 'getSize']),
        ];
    }

    public function getSize(?string $size)
    {
        switch ($size) {
            case "XL" :
                return "<div class=\"size-xl\">
                        <span class=\"size-x\">x</span><span class=\"size-l\">L</span>
                    </div>";
            case "XXL":
                return "<div class=\"size-xl\">
                            <span class=\"size-xx\">xx</span><span class=\"size-l\">L</span>
                        </div>";
            case "S":
            case "M":
            case "L":
                return "<div class=\"size\">$size</div>";
            default:
                return null;
        }
    }
}
