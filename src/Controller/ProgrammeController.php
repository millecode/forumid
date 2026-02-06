<?php

namespace App\Controller;

use App\Repository\EventDateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProgrammeController extends AbstractController
{
    #[Route('/programme', name: 'app_programme', methods: ['GET'])]
    public function index(EventDateRepository $eventDateRepository): Response
    {
        $activeEventDate = $eventDateRepository->findActiveOne();
        $eventDates = $eventDateRepository->findAllWithAgendasOrdered();

        // Active tab (jourX) : si une date active existe et est dans la liste
        $activeTab = null;
        if ($activeEventDate) {
            foreach ($eventDates as $idx => $ed) {
                if ($ed->getId() === $activeEventDate->getId()) {
                    $activeTab = 'jour' . ($idx + 1);
                    break;
                }
            }
        }
        if (!$activeTab && count($eventDates) > 0) {
            $activeTab = 'jour1';
        }

        return $this->render('programme/index.html.twig', [
            'activeEventDate' => $activeEventDate,
            'eventDates' => $eventDates,
            'activeTab' => $activeTab,
        ]);
    }
}
