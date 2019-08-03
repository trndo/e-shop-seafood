<?php


namespace App\Service\EntityService\ReservationHandler;


use App\Collection\ReservationCollection;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
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
    }


    public function reserve(string $productId, bool $orderType, float $quantity): void
    {
        $explodedId = $this->explodeProductId($productId);
        $uniqId = $this->session->get('reservationId');
        $product = $this->productService->getProductById($explodedId);
        $reservation = $this->getReservation($explodedId);

        if ($reservation) {
            $supply = $reservation->getProduct()->getSupply();
            $reservationQuantity = $reservation->getReservationQuantity();
            $diff = $this->updateSupply($quantity,$reservationQuantity);
            $supply->setQuantity($supply->getQuantity() + $diff);
            $reservation->setReservationQuantity($quantity);

        } elseif ($orderType) {
            $reservation = new Reservation();

            $reservation->setReservationQuantity($quantity)
                ->setProduct($product)
                ->setReservationTime(new \DateTime());
            if (!$uniqId){
                $reservation->setUniqId(
                    $this->generateUniqueReservation(
                        $reservation->getReservationTime()->format('Y-m-d H:i'),5
                    ));
                $this->session->set('reservationId',$reservation->getUniqId());
            } else {
                $reservation->setUniqId($uniqId);
            }

            $this->entityManager->persist($reservation);
        }

        $this->entityManager->flush();

    }

    /**
     * @param string $productId
     * @return Reservation
     */
    public function getReservation(?string $productId): ?Reservation
    {
        $reservationId = $this->session->get('reservationId');
        if (!$reservationId){
            return null;
        }

        return $this->reservationRepository->findOneBy([
            'uniqId' => $reservationId,
            'product' => $productId
        ]);
    }

    /**
     *
     * @param array $cart
     * @param string $key
     * @return void
     */
    public function deleteReservation(array $cart,string $key): void
    {
        if (is_array($cart[$key])) {
            $keys = array_keys($cart[$key]);
            $productId = $this->explodeProductId($keys[0]);
        } else {
            $productId = $this->explodeProductId($key);
        }
        $reservation = $this->getReservation($productId);
        $supply = $reservation->getProduct()->getSupply();
        $supply->setQuantity($supply->getQuantity() + $reservation->getReservationQuantity());
        $this->entityManager->remove($reservation);

        $this->entityManager->flush();
    }

    private function explodeProductId(string $productId)
    {
        return (int)explode('-',$productId)[1];
    }

    private function updateSupply(float $newQuantity, float $oldQuantity): float
    {
        $difference = 0;
        if ($newQuantity > $oldQuantity) {
            $difference = ($newQuantity - $oldQuantity)*-1;
        }

        if ($newQuantity < $oldQuantity) {
            $difference = $newQuantity - $oldQuantity;
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