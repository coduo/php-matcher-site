<?php

namespace App\Controller;

use Coduo\PHPMatcher\Backtrace\InMemoryBacktrace;
use Coduo\PHPMatcher\PHPMatcher;
use Composer\InstalledVersions;
use Norzechowicz\AceEditorBundle\Form\Extension\AceEditor\Type\AceEditorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PHPMatcherController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request) : Response
    {
        $form = $this->createFormBuilder([
                'value' => \file_get_contents(__DIR__.'/Default/value.json'),
                'pattern' => \file_get_contents(__DIR__.'/Default/pattern.json'),
            ])
            ->setMethod('GET')
            ->add('value', AceEditorType::class, ['height' => 450, 'font_size' => 14,])
            ->add('pattern', AceEditorType::class, ['height' => 450, 'font_size' => 14,])
            ->add('match', SubmitType::class, ['label' => 'Match'])
            ->getForm();

        $match = null;
        $matcher = new PHPMatcher(new InMemoryBacktrace());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $match = $matcher->match($form->get('value')->getData(), $form->get('pattern')->getData());
        }


        return $this->render('index.html.twig', [
            'form' => $form->createView(),
            'match' => $match,
            'matcher' => $matcher,
            'version' => InstalledVersions::getPrettyVersion('coduo/php-matcher')
        ]);
    }
}
