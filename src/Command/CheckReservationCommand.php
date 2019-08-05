<?php

namespace App\Command;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Service\EntityService\ReservationHandler\ReservationInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CheckReservationCommand extends Command
{


    protected static $defaultName = 'app:check-reservation';
    /**
     * @var ReservationInterface
     */
    private $reservation;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * CheckReservationCommand constructor.
     * @param ReservationInterface $reservation
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ReservationInterface $reservation, EntityManagerInterface $entityManager)
    {
        $this->reservation = $reservation;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Check reservation of a products');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $reservations = $this->reservation->getReservations();

        foreach ($reservations as $reservation) {
            /** @var Reservation $reservation */
            $reservationTime = $reservation->getReservationTime();

            $diff = $reservationTime->diff(new \DateTime());
            if ($diff->i >= 15) {
                $supply = $reservation->getProduct()->getSupply();
                $supply->setQuantity($supply->getQuantity() + $reservation->getReservationQuantity());
                $this->entityManager->remove($reservation);
                $io->success('Old reservation was deleted!');
            }
        }
        $this->entityManager->flush();

        $io->success('All reservations were checked!');
    }
}
