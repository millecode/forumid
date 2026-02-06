<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    public function __construct(
        #[Autowire('%env(bool:APP_REGISTER_ENABLED)%')]
        private readonly bool $registerEnabled,

        #[Autowire('%env(APP_REGISTER_TOKEN)%')]
        private readonly string $registerToken,
    ) {}

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): Response {
        // Feature flag OFF => on cache la page (404)
        if (!$this->registerEnabled) {
            throw $this->createNotFoundException();
        }

        // Token obligatoire => on cache la page (404)
        $token = (string) $request->query->get('token', '');
        if ($token === '' || !hash_equals($this->registerToken, $token)) {
            throw $this->createNotFoundException();
        }

        $user = new User();

        // IMPORTANT: pendant le bootstrap, on crée des admins
        $user->setRoles(['ROLE_ADMIN']);
        $user->setIsActive(true);

        $form = $this->createForm(RegistrationFormType::class, $user, [
            'attr' => ['class' => 'auth-form', 'novalidate' => 'novalidate'],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = (string) $form->get('plainPassword')->getData();

            $user->setPassword(
                $passwordHasher->hashPassword($user, $plainPassword)
            );

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Compte admin créé avec succès.');

            // On reste sur la même page (pratique pour créer plusieurs users)
            return $this->redirectToRoute('app_register', ['token' => $token]);
        }

        return $this->render('register/index.html.twig', [
            'registrationForm' => $form,
            'registerToken' => $token,
        ]);
    }
}
