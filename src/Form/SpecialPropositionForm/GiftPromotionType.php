<?php


namespace App\Form\SpecialPropositionForm;


use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Receipt;
use App\Model\PromotionModel;
use App\Service\UpdateTypeHandler\UpdateTypeInterface;
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
    use PromotionTrait;
    /**
     * @var EntityManagerInterface
     */


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(!$options['update']) {
            $builder->add('giftCategory', EntityType::class, [
                'class' => Category::class,
                'placeholder' => 'Выберете категорию подарка',
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.type = :type')
                        ->setParameter('type', 'products');
                }
            ]);
            $formModifierGift = $this->modifyGift('gift');
            $this->changeOption($formModifierGift,'giftCategory',$builder);
        } else {
            $builder->add('gift',EntityType::class,[
                'class' => Product::class,
                'choice_label' => 'name',
                'disabled' => true
            ]);
        }
        $this->isUpdateOption($builder,$options);
        $builder->add('productSize',TextType::class)
        ->add('availableAt',DateType::class)
        ->add('quantity', IntegerType::class)
        ->add('description',TextareaType::class)
        ->add('save',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'data_class' => PromotionModel::class,
            'receipt' => false,
            'update' => false
        ]);
    }

    private function modifyGift(string $inputName): callable
    {
        $modifier = function (FormInterface $form, Category $category = null) use ($inputName) {
            $products = null === $category ? [] : $category->getProducts();
            $form->add($inputName,EntityType::class,[
                'class' => Product::class,
                'placeholder' => 'Выберите продукт',
                'choice_label' => 'name',
                'choices' => $products,
            ]);
        };

        return $modifier;
    }


}