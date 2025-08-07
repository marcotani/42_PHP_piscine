<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(User::class)->findAll();
        $currentUser = $this->getUser();
        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'currentUser' => $currentUser,
        ]);
    }

    #[Route('/admin/delete/{id}', name: 'admin_delete_user', methods: ['POST'])]
    public function deleteUser(int $id, EntityManagerInterface $em): Response
    {
        $currentUser = $this->getUser();
        if ($currentUser->getId() == $id) {
            $this->addFlash('error', 'You cannot delete your own user.');
            return $this->redirectToRoute('app_admin');
        }
        $user = $em->getRepository(User::class)->find($id);
        if ($user) {
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'User deleted successfully.');
        }
        return $this->redirectToRoute('app_admin');
    }
}
