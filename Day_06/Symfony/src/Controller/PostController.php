<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;
use App\Form\PostType;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Vote;

final class PostController extends AbstractController
{
    #[Route('/post/{id}/edit', name: 'post_edit')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function edit(Post $post, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if ($user !== $post->getAuthor()) {
            $this->addFlash('error', 'You can only edit your own posts.');
            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setLastEditedAt(new \DateTime());
            $post->setLastEditedBy($user);
            $em->flush();
            $this->addFlash('success', 'Post updated successfully.');
            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }
        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }
    #[Route('/post/new', name: 'post_new')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setAuthor($this->getUser());
            $post->setCreated(new \DateTime());
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->render('post/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/post/{id}', name: 'post_show')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function show(Post $post, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $voteRepo = $em->getRepository(Vote::class);
        $likes = $voteRepo->count(['post' => $post, 'value' => 1]);
        $dislikes = $voteRepo->count(['post' => $post, 'value' => -1]);
        $userVote = null;
        if ($user) {
            $userVote = $voteRepo->findOneBy(['post' => $post, 'user' => $user]);
        }
        // Reputation for author
        $author = $post->getAuthor();
        $authorPosts = $em->getRepository(Post::class)->findBy(['author' => $author]);
        $authorReputation = 0;
        foreach ($authorPosts as $ap) {
            $authorReputation += $voteRepo->count(['post' => $ap, 'value' => 1]);
            $authorReputation -= $voteRepo->count(['post' => $ap, 'value' => -1]);
        }
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'likes' => $likes,
            'dislikes' => $dislikes,
            'userVote' => $userVote,
            'authorReputation' => $authorReputation,
        ]);
    }

    #[Route('/post/{id}/vote/{value}', name: 'post_vote', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function vote(Post $post, int $value, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!in_array($value, [1, -1])) {
            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }
        $voteRepo = $em->getRepository(Vote::class);
        $vote = $voteRepo->findOneBy(['post' => $post, 'user' => $user]);
        if (!$vote) {
            $vote = new Vote();
            $vote->setPost($post);
            $vote->setUser($user);
        }
        $vote->setValue($value);
        $em->persist($vote);
        $em->flush();
        return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
    }
}
