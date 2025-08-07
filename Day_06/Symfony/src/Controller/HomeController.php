<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Vote;
use Symfony\Component\HttpFoundation\Request;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $posts = $em->getRepository(Post::class)->findBy([], ['created' => 'DESC']);
        $voteRepo = $em->getRepository(Vote::class);
        $postVotes = [];
        $authorReputations = [];
        foreach ($posts as $post) {
            $likes = $voteRepo->count(['post' => $post, 'value' => 1]);
            $dislikes = $voteRepo->count(['post' => $post, 'value' => -1]);
            $postVotes[$post->getId()] = ['likes' => $likes, 'dislikes' => $dislikes];
            $author = $post->getAuthor();
            if (!isset($authorReputations[$author->getId()])) {
                $authorPosts = $em->getRepository(Post::class)->findBy(['author' => $author]);
                $rep = 0;
                foreach ($authorPosts as $ap) {
                    $rep += $voteRepo->count(['post' => $ap, 'value' => 1]);
                    $rep -= $voteRepo->count(['post' => $ap, 'value' => -1]);
                }
                $authorReputations[$author->getId()] = $rep;
            }
        }

    $session = $request->getSession();
        $anonymousName = null;
        $secondsSinceLastRequest = null;
        if (!$user) {
            $animals = ['dog', 'cat', 'fox', 'bear', 'owl', 'wolf', 'lion', 'tiger', 'rabbit', 'panda'];
            if (!$session->has('anonymous_name')) {
                $animal = $animals[array_rand($animals)];
                $session->set('anonymous_name', 'Anonymous ' . $animal);
            }
            $anonymousName = $session->get('anonymous_name');

            $now = time();
            $lastRequest = $session->get('last_request_time', $now);
            $secondsSinceLastRequest = $now - $lastRequest;
            $session->set('last_request_time', $now);
        }

        return $this->render('home/index.html.twig', [
            'user' => $user,
            'posts' => $posts,
            'postVotes' => $postVotes,
            'authorReputations' => $authorReputations,
            'anonymousName' => $anonymousName,
            'secondsSinceLastRequest' => $secondsSinceLastRequest,
        ]);
    }
}
