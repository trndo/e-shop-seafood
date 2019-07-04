<?php


namespace App\Form\SpecialPropositionForm;


use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Receipt;
use Doctrine\ORM\EntityRepository;
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

    private function isUpdateOption(FormBuilderInterface $builder,array $options): void
    {
        if (!$options['update']) {
            $builder->add('category', EntityType::class, [
                'class' => Category::class,
                'placeholder' => '',
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    $builder = $er->createQueryBuilder('c')->where('c.type = :type');
                    return $options['receipt'] == true ? $builder->setParameter('type', 'receipts') : $builder->setParameter('type', 'products');
                }
            ]);
            $formModifierProduct = $options['receipt'] == true ? $this->modifyInput('receipt') : $this->modifyInput('product');
            $this->changeOption($formModifierProduct, 'category', $builder);
        } else {
            if ($options['receipt']){
                $builder->add('receipt',EntityType::class,[
                    'class' => Receipt::class,
                    'choice_label' => 'name',
                    'disabled' => true
                ]);
            } else {
                $builder->add('product',EntityType::class,[
                    'class' => Product::class,
                    'choice_label' => 'name',
                    'disabled' => true
                ]);
            }
        }
    }
}