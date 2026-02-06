<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Form\AgendaType;
use App\Repository\AgendaRepository;
use App\Repository\EventDateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/gestions-agenda', name: 'admin_agenda_')]
class AgendaController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(
        Request $request,
        AgendaRepository $agendaRepo,
        EventDateRepository $eventDateRepo
    ): Response {
        $eventDates = $eventDateRepo->findBy([], ['startDate' => 'DESC']);

        $filterId = $request->query->getInt('date', 0);
        $currentDate = $filterId ? $eventDateRepo->find($filterId) : null;

        // si pas de filtre -> on prend la date active si elle existe (UX)
        if (!$currentDate) {
            $currentDate = $eventDateRepo->findOneBy(['isActive' => true]);
        }

        $agendas = $currentDate
            ? $agendaRepo->findBy(['eventDate' => $currentDate], ['startTime' => 'ASC'])
            : $agendaRepo->findBy([], ['createdAt' => 'DESC']);

        $agenda = new Agenda();
        if ($currentDate) {
            $agenda->setEventDate($currentDate);
        }

        $form = $this->createForm(AgendaType::class, $agenda, [
            'action' => $this->generateUrl('admin_agenda_create'),
            'method' => 'POST',
        ]);

        return $this->render('admin/agenda.html.twig', [
            'page_title' => "Gestions d'agenda",
            'eventDates' => $eventDates,
            'currentDate' => $currentDate,
            'agendas' => $agendas,
            'form' => $form,
            'is_edit' => false,
        ]);
    }

    #[Route('/new', name: 'create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        AgendaRepository $agendaRepo,
        EventDateRepository $eventDateRepo
    ): Response {
        $agenda = new Agenda();
        $form = $this->createForm(AgendaType::class, $agenda, [
            'action' => $this->generateUrl('admin_agenda_create'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        $eventDates = $eventDateRepo->findBy([], ['startDate' => 'DESC']);
        $currentDate = $agenda->getEventDate() ?: $eventDateRepo->findOneBy(['isActive' => true]);

        $agendas = $currentDate
            ? $agendaRepo->findBy(['eventDate' => $currentDate], ['startTime' => 'ASC'])
            : $agendaRepo->findBy([], ['createdAt' => 'DESC']);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($agenda);
            $em->flush();

            $this->addFlash('success', "Élément agenda ajouté avec succès.");
            return $this->redirectToRoute('admin_agenda_index', [
                'date' => $agenda->getEventDate()?->getId() ?: 0
            ]);
        }

        $this->addFlash('error', "Veuillez corriger les erreurs du formulaire.");

        return $this->render('admin/agenda.html.twig', [
            'page_title' => "Gestions d'agenda",
            'eventDates' => $eventDates,
            'currentDate' => $currentDate,
            'agendas' => $agendas,
            'form' => $form,
            'is_edit' => false,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(
        Agenda $agenda,
        Request $request,
        EntityManagerInterface $em,
        AgendaRepository $agendaRepo,
        EventDateRepository $eventDateRepo
    ): Response {
        $eventDates = $eventDateRepo->findBy([], ['startDate' => 'DESC']);
        $currentDate = $agenda->getEventDate();

        $form = $this->createForm(AgendaType::class, $agenda, [
            'action' => $this->generateUrl('admin_agenda_edit', ['id' => $agenda->getId()]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        $agendas = $currentDate
            ? $agendaRepo->findBy(['eventDate' => $currentDate], ['startTime' => 'ASC'])
            : $agendaRepo->findBy([], ['createdAt' => 'DESC']);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', "Élément agenda modifié avec succès.");
            return $this->redirectToRoute('admin_agenda_index', [
                'date' => $agenda->getEventDate()?->getId() ?: 0
            ]);
        }

        return $this->render('admin/agenda.html.twig', [
            'page_title' => "Gestions d'agenda",
            'eventDates' => $eventDates,
            'currentDate' => $currentDate,
            'agendas' => $agendas,
            'form' => $form,
            'is_edit' => true,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(
        Agenda $agenda,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        if (!$this->isCsrfTokenValid('delete_agenda_'.$agenda->getId(), (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }

        $dateId = $agenda->getEventDate()?->getId() ?: 0;

        $em->remove($agenda);
        $em->flush();

        $this->addFlash('success', "Élément agenda supprimé.");
        return $this->redirectToRoute('admin_agenda_index', ['date' => $dateId]);
    }
}
