<?php


namespace App\Form\SpecialPropositionForm;


use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Receipt;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

trait PromotionTrait
{
    private function modifyInput(string $inputName):callable
    {
        $modifier = function (FormInterface $form, Category $category = null) use ($inputName) {

            if ($inputName == 'receipt'){
                $receipts = null === $category ? [] : $category->getReceipts();
                $form->add($inputName,EntityType::class,[
                    'class' => Receipt::class,
                    'placeholder' => 'Выберите рецепт',
                    'choice_label' => 'name',
                    'choices' => $receipts,
                ]);
            } else {
                $products = null === $category ? [] : $category->getProducts();
                $form->add($inputName,EntityType::class,[
                    'class' => Product::class,
                    'placeholder' => 'Выберите продукт',
                    'choice_label' => 'name',
                    'choices' => $products,
                ]);
            }

        };

        return $modifier;
    }

    private function changeOption(callable $modifier,string $inputName,FormBuilderInterface $builder)
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($modifier) {
                $data = $event->getData();

                $modifier($event->getForm(),$data->getCategory());
            }
        );

        $builder->get($inputName)->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($modifier) {
                $category = $event->getForm()->getData();

                $modifier($event->getForm()->getParent(),$category);
            }
        );
    }
}