<?php

namespace App\Controller;

use App\Controller\Form\MatcherForm;
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
        $form = $this->createForm(
            MatcherForm::class,
            [
                'value' => \file_get_contents(__DIR__.'/Default/value.json'),
                'pattern' => \file_get_contents(__DIR__.'/Default/pattern.json'),
            ]
        );

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
