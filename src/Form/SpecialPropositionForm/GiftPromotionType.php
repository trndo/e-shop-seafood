<?php


namespace App\Form\SpecialPropositionForm;


use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Receipt;
use App\Model\PromotionModel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GiftPromotionType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('gift', EntityType::class,[
            'class' => Product::class,
            'choice_label' => 'name'
        ])
        ->add('quantity', IntegerType::class)
        ->add('category',EntityType::class,[
            'class' => Category::class,
            'placeholder' => 'Выберите категорию',
            'choice_label' => 'name'
//            'query_builder' => function(EntityRepository $er){
//                return $er->createQueryBuilder('c')
//                    ->where('c.type = :type')
//                    ->setParameter('type','products');
//            }
        ])
            ->add('product',EntityType::class,[
                'class' => Product::class,
                'placeholder' => 'Выберите категорию',
                'choice_label' => 'name'
            ])
//        ->add('product',ChoiceType::class, [
//            'placeholder' => 'Выберите продукт',
//            'choices' => $this->getCategoryItems($category->getCategory()->getId()),
//            'required' => false,
//        ])
        ->add('productSize',TextType::class)
        ->add('description',TextareaType::class)
        ->add('save',SubmitType::class);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var PromotionModel|null $data */
                $data = $event->getData();
                if (!$data) {
                    return;
                }

                $this->setupProductField(
                    $event->getForm(),
                    $data->getCategory()->getId()
                );
            }
        );

        $builder->get('category')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $e)
            {
                $form = $e->getForm();
                $this->setupProductField(
                    $form->getParent(),
                    $form->getData()
                );
            }
        );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'data_class' => PromotionModel::class,
        ]);
    }

    private function getCategoryItems(?int $categoryId): ?array
    {
        $repository = $this->entityManager->getRepository(Category::class);

        return $repository->getProductsByCategory($categoryId);

    }

    private function setupProductField(FormInterface $form,?int $categoryId)
    {
        if ($categoryId === null) {
            $form->remove('product');

            return;
        }

        $choices = $this->getCategoryItems($categoryId);

        if ($choices === null) {
            $form->remove('product');

            return;
        }

        $form->add('product',ChoiceType::class, [
            'placeholder' => 'Выберите продукт',
            'choices' => $choices,
            'required' => false,
        ]);
    }
}