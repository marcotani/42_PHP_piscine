<?php

namespace E02Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="e02_form")
     */
    public function indexAction(Request $request)
    {
        // 1. Build the form
        $form = $this->createFormBuilder()
            ->add('message', TextType::class, [
                'label' => 'Message',
                'required' => true,
            ])
            ->add('includeTimestamp', ChoiceType::class, [
                'label' => 'Include timestamp',
                'choices' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                ],
                'expanded' => false,
                'multiple' => false,
                'data' => 'no',
            ])
            ->add('save', SubmitType::class, ['label' => 'Submit'])
            ->getForm();

        // 2. Handle the request
        $form->handleRequest($request);

        $lastLine = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Debug - check submitted value of includeTimestamp
            // Remove this line after verifying
            dump($data['includeTimestamp']);

            $message = trim($data['message']);
            $includeTimestamp = ($data['includeTimestamp'] === 'yes');

            if ($includeTimestamp) {
                $line = sprintf("[%s] %s", date('Y-m-d H:i:s'), $message);
            } else {
                $line = $message;
            }

            $filePath = $this->getParameter('notes_file');

            file_put_contents($filePath, $line . PHP_EOL, FILE_APPEND | LOCK_EX);

            $lastLine = $line;

            // Optional: redirect to avoid resubmission on page refresh
            // return $this->redirectToRoute('e02_form');
        } else {
            $filePath = $this->getParameter('notes_file');
            if (file_exists($filePath)) {
                $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $lastLine = end($lines);
            }
        }

        return $this->render('E02Bundle:Default:index.html.twig', [
            'form' => $form->createView(),
            'lastLine' => $lastLine,
        ]);
    }
}

