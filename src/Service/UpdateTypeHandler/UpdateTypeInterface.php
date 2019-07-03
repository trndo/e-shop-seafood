<?php


namespace App\Service\UpdateTypeHandler;


use Symfony\Component\Form\FormBuilderInterface;

interface UpdateTypeInterface
{
    public function isUpdateOption(FormBuilderInterface $builder, array $options): void ;
}