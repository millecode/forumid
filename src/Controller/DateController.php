<?php

namespace App\Controller;

use App\Entity\EventDate;
use App\Form\Admin\EventDateType;
use App\Repository\EventDateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/dates', name: 'admin_dates_')]
class DateController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        EventDateRepository $repo
    ): Response {
        $date = new EventDate();
        $form = $this->createForm(EventDateType::class, $date);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($date);
            $em->flush();

            $this->addFlash('success', 'Date ajoutée avec succès.');
            return $this->redirectToRoute('admin_dates_index');
        }

        return $this->render('admin/date.html.twig', [
            'page_title' => "Gestions des dates",
            'form' => $form->createView(),
            'dates' => $repo->findBy([], ['createdAt' => 'DESC']),
            'edit_mode' => false,
            'page_title' => "Gestions des dates",

        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        EventDate $date,
        Request $request,
        EntityManagerInterface $em,
        EventDateRepository $repo
    ): Response {
        $form = $this->createForm(EventDateType::class, $date);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Date modifiée avec succès.');
            return $this->redirectToRoute('admin_dates_index');
        }

        return $this->render('admin/date.html.twig', [
            'page_title' => "Gestions des dates",
            'form' => $form->createView(),
            'dates' => $repo->findBy([], ['createdAt' => 'DESC']),
            'edit_mode' => true,
            'edit_id' => $date->getId(),
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(
        EventDate $date,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        if (!$this->isCsrfTokenValid('delete_date'.$date->getId(), (string) $request->request->get('_token'))) {
            $this->addFlash('danger', 'Token CSRF invalide.');
            return $this->redirectToRoute('admin_dates_index');
        }

        $em->remove($date);
        $em->flush();

        $this->addFlash('success', 'Date supprimée.');
        return $this->redirectToRoute('admin_dates_index');
    }

    #[Route('/activate', name: 'activate', methods: ['POST'])]
    public function activate(
        Request $request,
        EntityManagerInterface $em,
        EventDateRepository $repo
    ): Response {
        if (!$this->isCsrfTokenValid('activate_date', (string) $request->request->get('_token'))) {
            $this->addFlash('danger', 'Token CSRF invalide.');
            return $this->redirectToRoute('admin_dates_index');
        }

        $id = (int) $request->request->get('active_id', 0);
        if ($id <= 0) {
            $this->addFlash('danger', 'Aucune date sélectionnée.');
            return $this->redirectToRoute('admin_dates_index');
        }

        $date = $repo->find($id);
        if (!$date) {
            $this->addFlash('danger', 'Date introuvable.');
            return $this->redirectToRoute('admin_dates_index');
        }

        $em->getConnection()->beginTransaction();
        try {
            $repo->deactivateAll();
            $date->setIsActive(true);
            $em->flush();
            $em->getConnection()->commit();
        } catch (\Throwable $e) {
            $em->getConnection()->rollBack();
            $this->addFlash('danger', 'Erreur lors de l’activation.');
            return $this->redirectToRoute('admin_dates_index');
        }

        $this->addFlash('success', 'Date activée.');
        return $this->redirectToRoute('admin_dates_index');
    }
}
