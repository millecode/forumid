<?php

namespace App\Controller;

use App\Repository\AgendaRepository;
use App\Repository\EventDateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(EventDateRepository $eventDateRepo, AgendaRepository $agendaRepo): Response
    {
        $tz = new \DateTimeZone('Africa/Djibouti');

        $activeEventDate = $eventDateRepo->findActiveOne();

        $countdownStartIso = null;
        $countdownEndIso = null;

        if ($activeEventDate) {
            $bounds = $agendaRepo->findBoundsForEventDate($activeEventDate);

            $minStart = $this->normalizeTime($bounds['minStartTime'] ?? null, $tz);
            $maxEnd   = $this->normalizeTime($bounds['maxEndTime'] ?? null, $tz);

            // Fallback si aucun agenda
            $startTimeStr = $minStart ? $minStart->format('H:i:s') : '09:00:00';
            $endTimeStr   = $maxEnd   ? $maxEnd->format('H:i:s')   : '23:59:59';

            $startDate = $activeEventDate->getStartDate();
            $endDate   = $activeEventDate->getEndDate();

            $startDateTime = \DateTimeImmutable::createFromFormat(
                'Y-m-d H:i:s',
                $startDate->format('Y-m-d') . ' ' . $startTimeStr,
                $tz
            ) ?: (new \DateTimeImmutable('now', $tz));

            $endDateTime = \DateTimeImmutable::createFromFormat(
                'Y-m-d H:i:s',
                $endDate->format('Y-m-d') . ' ' . $endTimeStr,
                $tz
            ) ?: $startDateTime;

            // Sécurité : éviter un end < start
            if ($endDateTime < $startDateTime) {
                $endDateTime = $startDateTime;
            }

            // ISO avec offset (+03:00) => JS parse OK
            $countdownStartIso = $startDateTime->format(\DATE_ATOM);
            $countdownEndIso   = $endDateTime->format(\DATE_ATOM);
        }

        return $this->render('home/index.html.twig', [
            'activeEventDate'    => $activeEventDate,
            'countdownStartIso'  => $countdownStartIso,
            'countdownEndIso'    => $countdownEndIso,
        ]);
    }

    private function normalizeTime(mixed $time, \DateTimeZone $tz): ?\DateTimeImmutable
    {
        if ($time instanceof \DateTimeInterface) {
            return \DateTimeImmutable::createFromInterface($time)->setTimezone($tz);
        }

        if (is_string($time) && $time !== '') {
            $t = \DateTimeImmutable::createFromFormat('H:i:s', $time, $tz)
                ?: \DateTimeImmutable::createFromFormat('H:i', $time, $tz);
            return $t ?: null;
        }

        return null;
    }
}
