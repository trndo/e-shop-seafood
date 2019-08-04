<?php


namespace App\Service\EntityService\ReservationHandler;


use App\Collection\ReservationCollection;
use App\Entity\Product;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Service\CartHandler\Item;
use App\Service\EntityService\ProductService\ProductServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ReservationHandler implements ReservationInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ReservationRepository
     */
    private $reservationRepository;
    /**
     * @var ProductServiceInterface
     */
    private $productService;
    /**
     * @var SessionInterface
     */
    private $session;

    private $reservationId;

    /**
     * ReservationHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param ReservationRepository $reservationRepository
     * @param ProductServiceInterface $productService
     * @param SessionInterface $session
     */
    public function __construct(EntityManagerInterface $entityManager, ReservationRepository $reservationRepository, ProductServiceInterface $productService, SessionInterface $session)
    {
        $this->entityManager = $entityManager;
        $this->reservationRepository = $reservationRepository;
        $this->productService = $productService;
        $this->session = $session;
        $this->reservationId = $session->get('reservationId');
    }


    public function reserve(Product $product, bool $orderType, float $quantity): void
    {
        $reservation = $this->getReservation($product->getId());
        $supply = $product->getSupply();

        if ($reservation) {
            $reservationQuantity = $reservation->getReservationQuantity();
            $diff = $this->recognizeDiff($quantity,$reservationQuantity);
            $supply->setQuantity($supply->getQuantity() + $diff);
            $reservation->setReservationQuantity($quantity);

        } elseif ($orderType) {
            $reservation = new Reservation();
            $reservation->setReservationQuantity($quantity)
                ->setProduct($product)
                ->setReservationTime(new \DateTime());

            if (!$this->reservationId){
                $reservation->setUniqId(
                    $this->generateUniqueReservation(
                        $reservation->getReservationTime()->format('Y-m-d H:i'),5
                    ));
                $this->session->set('reservationId',$reservation->getUniqId());
            } else {
                $reservation->setUniqId($this->reservationId);
            }

            $this->entityManager->persist($reservation);
            $supply->setQuantity($supply->getQuantity() - $quantity);
        }
        $this->entityManager->flush();
    }

    /**
     * @param int $productId
     * @return Reservation
     */
    public function getReservation(int $productId): ?Reservation
    {
        if (!$this->reservationId){
            return null;
        }
        $reservation = $this->reservationRepository->findOneBy([
            'uniqId' => $this->reservationId,
            'product' => $productId
        ]);
        return $reservation instanceof Reservation ? $reservation : null;
    }

    /**
     *
     * @param Item $item
     * @return void
     */
    public function deleteReservation(Item $item): void
    {
        if ($item->getItemType() == 'product') {
            $reservation = $this->getReservation($item->getId());
        } elseif($item->getItemType() == 'receipt') {
            $reservation = $this->getReservation($item->getRelatedProductId());
        }
        else $reservation = null;
        if($reservation instanceof Reservation) {
            $supply = $reservation->getProduct()->getSupply();
            $supply->setQuantity($supply->getQuantity() + $reservation->getReservationQuantity());
            $this->entityManager->remove($reservation);
            $this->entityManager->flush();
        }
    }

    private function explodeProductId(string $productId)
    {
        return (int)explode('-',$productId)[1];
    }

    private function recognizeDiff(float $newQuantity, float $oldQuantity): float
    {
        $difference = 0;
        if ($newQuantity > $oldQuantity) {
            $difference = ($newQuantity - $oldQuantity)*-1;
        }

        if ($newQuantity < $oldQuantity) {
            $difference = $oldQuantity - $newQuantity;
        }

        return $difference;
    }

    private function generateUniqueReservation(string $reservationTime,int $length): string
    {
        return \substr(\md5(\uniqid($reservationTime,true)),0,$length);
    }

    public function getReservations(): ReservationCollection
    {
        return new ReservationCollection($this->reservationRepository->findAll());
    }


}