<?php

namespace App\Controller;

use App\Entity\Level;
use App\Entity\Training;
use App\Form\LevelType;
use App\Repository\LevelRepository;
use App\Repository\ParameterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
    public function addLevel(Request $request, EntityManagerInterface $manager, ParameterRepository  $repository, SessionInterface $session)
    {
        $session->start();
       $levels = $repository->findBy(['categories' => 'NIVEAU']);
      if (empty($levels)) {
          $this->createNotFoundException('Pas de données');
      }
       $level = new Level();
       if ($request->getMethod() === 'POST') {
           $level->addUser($this->getUser());
           $level->setLabel($request->request->get('level'));
           $manager->persist($level);
           $manager->flush();
           $session->set('level', $level->getLabel());
           $session->set('levelId', $level->getId());

           return $this->redirectToRoute("inscription_level_training");
       }
        return $this->render('inscription/forms/level/add.html.twig', [
                'user' => $this->getUser(),
                'levels' => $levels
        ]);
    }

    /**
     * @Route("/level/training", name="level_training")
     * @param $level
     * @param Request $request
     */
    public function addTraining(
        Request $request,
        ParameterRepository $repository,
        LevelRepository $levelRepository,
        SessionInterface $session,
        EntityManagerInterface $manager
    )
    {
        $session->start();
        $level = $levelRepository->find($session->get('levelId'));

        $trainings = $repository->findBy(['categories' => $session->get('level')]);
        $levels = $repository->findBy(['categories' => 'NIVEAU']);
        if (empty($training)) {
            $this->createNotFoundException('Pas de données');
        }

        if ($request->getMethod() === 'POST') {
            $datas = $request->request->all();

            foreach ($datas as $data) {
                $training = new Training();
                $training->setLabel($data);
                $training->setLevel($level);
                $manager->persist($training);
            }



            $manager->flush();
//            $session->set('level', $level->getLabel());
//            $session->set('levelId', $level->getId());

            return $this->redirectToRoute("inscription_etudiant");
        }

        return $this->render('inscription/forms/training/add.html.twig', [
            'user' => $this->getUser(),
            'trainings' => $trainings,
            'levels' => $levels
        ]);
    }
}
