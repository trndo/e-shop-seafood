<?php


namespace App\Service\UpdateTypeHandler;


use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Receipt;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateTypeHandler implements UpdateTypeInterface
{

    public function isUpdateOption(FormBuilderInterface $builder, array $options): void
    {
        // TODO: Implement isUpdateOption() method.
    }
}