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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GlobalSpecialPricePromotionType extends AbstractType
{
    use PromotionTrait;
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
        $builder->add('globalPrice',IntegerType::class)
            ->add('availableAt',DateType::class)
            ->add('quantity', IntegerType::class);
            $this->isUpdateOption($builder,$options);
        $builder->add('productSize',TextType::class)
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
}