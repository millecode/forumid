<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InscriptionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('', name: 'dashboard')]
    public function dashboard(): Response
    {
        // Placeholders (tu brancheras Repository->count([]) plus tard)
        $kpis = [
            'partners' => 0,
            'registrations' => 0,
            'agenda_items' => 0,
        ];

        return $this->render('admin/dashboard.html.twig', [
            'page_title' => 'Tableau de bord',
            'kpis' => $kpis,
        ]);
    }

    

    

    #[Route('/inscriptions', name: 'registrations', methods: ['GET'])]
    public function registrations(InscriptionRepository $repo): Response
    {
        $inscriptions = $repo->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/inscriptions.html.twig', [
            'page_title' => "Les inscriptions",
            'inscriptions' => $inscriptions,
        ]);
    }

    #[Route('/inscriptions/{id}/delete', name: 'registrations_delete', methods: ['POST'])]
    public function deleteRegistration(
        int $id,
        Request $request,
        InscriptionRepository $repo,
        EntityManagerInterface $em
    ): Response {
        $inscription = $repo->find($id);
        if (!$inscription) {
            $this->addFlash('error', "Inscription introuvable.");
            return $this->redirectToRoute('admin_registrations');
        }

        if (!$this->isCsrfTokenValid('delete_inscription_'.$id, (string) $request->request->get('_token'))) {
            $this->addFlash('error', "Token CSRF invalide.");
            return $this->redirectToRoute('admin_registrations');
        }

        $em->remove($inscription);
        $em->flush();

        $this->addFlash('success', "Inscription supprimée avec succès.");
        return $this->redirectToRoute('admin_registrations');
    }

    #[Route('/partenaires', name: 'partners')]
    public function partners(): Response
    {
        return $this->render('admin/placeholder.html.twig', [
            'page_title' => "Partenaires",
        ]);
    }
}
