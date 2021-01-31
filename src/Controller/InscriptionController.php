<?php

namespace App\Controller;

use App\Entity\Level;
use App\Form\LevelType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inscription/etudiant", name="inscription_")
 */
class InscriptionController extends AbstractController
{
    /**
     * @Route("/", name="etudiant")
     */
    public function etudiant()
    {
        return $this->render('inscription/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/level", name="level")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addLevel(Request $request, EntityManagerInterface $manager)
    {
       $level = new Level();
       if ($request->getMethod() === 'POST') {
           $level->addUser($this->getUser());
           $level->setLabel($request->request->get('level'));
           $manager->persist($level);
           $manager->flush();

           return $this->redirectToRoute("inscription_level_training", ['level' => $level->getId()]);
       }
        return $this->render('inscription/forms/level/add.html.twig', [
                'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/level/{level}/training", name="level_training")
     * @param $level
     * @param Request $request
     */
    public function addTraining($level, Request $request)
    {
        dd($level);
    }
}
