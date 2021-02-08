<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Entity\Level;
use App\Entity\Training;
use App\Form\CalendarType;
use App\Form\LevelType;
use App\Repository\CalendarRepository;
use App\Repository\LevelRepository;
use App\Repository\ParameterRepository;
use App\Repository\UsersRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inscription", name="inscription_")
 */
class InscriptionController extends AbstractController
{
    /**
     * @Route("/etudiant", name="etudiant")
     */
    public function etudiant()
    {
        return $this->render('inscription/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/level/", name="level")
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

            return $this->redirectToRoute("inscription_horaires");
        }

        return $this->render('inscription/forms/training/add.html.twig', [
            'user' => $this->getUser(),
            'trainings' => $trainings,
            'levels' => $levels
        ]);
    }

    /**
     * @Route("/horaires", name="horaires", methods={"GET", "POST"})
     */
    public function planning(CalendarRepository $calendar, Request $request): Response
    {
        $events = $calendar->findBy(['user' => $this->getUser()]);
        $rdvs = [];
        foreach ($events as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->getAllDay()
            ];
        }
        $data = json_encode($rdvs);
        return $this->render('inscription/forms/planning/index.html.twig', compact('data'));
    }

    /**
     * @Route("/horaires/new", name="horaires_new", methods={"GET", "POST"})
     */
    public function newEvents(
        ?Calendar $calendar,
        Request $request,
        EntityManagerInterface $manager,
        CalendarRepository $calendarRepository
    )
    {
       // $session->start();
       // $level = $levelRepository->find($session->get('levelId'));
      //  $calendar = new Calendar();
        $events = $calendarRepository->findBy(['user' => $this->getUser()]);
        $rdvs = [];
        foreach ($events as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->getAllDay()
            ];
        }
        $data = json_encode($rdvs);

      $donnees = json_decode($request->getContent());
   //  dd($donnees);
        if ($donnees) {
            if (
                isset($donnees->title) && !empty($donnees->title) &&
                isset($donnees->start) && !empty($donnees->start) &&
                isset($donnees->end) && !empty($donnees->end) &&
                isset($donnees->description) && !empty($donnees->description) &&
                isset($donnees->backgroundColor) && !empty($donnees->backgroundColor) &&
                isset($donnees->borderColor) && !empty($donnees->borderColor) &&
                isset($donnees->textColor) && !empty($donnees->textColor)
            ) {
                $code = 200;
                if (!$calendar) {
                    $calendar = new Calendar;

                    $code = 201;
                }
                $calendar->setTitle($donnees->title);
                $calendar->setDescription($donnees->description);
                $calendar->setStart(new DateTime($donnees->start));
                $calendar->setEnd(new DateTime($donnees->end));
//                if ($donnees->allDay) {
//                    $calendar->setEnd(new DateTime($donnees->end));
//                }
//                $calendar->setAllDay($donnees->allDay);
                $calendar->setBackgroundColor($donnees->backgroundColor);
                $calendar->setBorderColor($donnees->borderColor);
                $calendar->setTextColor($donnees->textColor);
                $calendar->setUser($this->getUser());

                $manager->persist($calendar);
                $manager->flush();

                return new Response('ok', $code);
            } else {
                return new Response('Données incomplètes', 404);
            }
        }
        $form = $this->createForm(CalendarType::class, $calendar);

        return $this->render('inscription/forms/planning/index.html.twig', [
            'form' => $form->createView(),
            'data' => $data
        ]);
    }

    /**
     * @Route("/horaire/{id}/edit", name="horaire_edit", methods={"PUT"})
     */
    public function majEvent(?Calendar $calendar, Request $request, EntityManagerInterface $em): Response
    {
        $donnees = json_decode($request->getContent());
        if (
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->end) && !empty($donnees->end) &&
            isset($donnees->backgroundColor) && !empty($donnees->backgroundColor)
        ) {
            $code = 200;
            if (!$calendar) {
                $calendar = new Calendar;
                $code = 201;
            }
            $calendar->setTitle($donnees->title);
            //   $calendar->setDescription($donnees->description);
            $calendar->setStart(new DateTime($donnees->start));
            $calendar->setEnd(new DateTime($donnees->end));
            $calendar->setBackgroundColor($donnees->backgroundColor);
            $em->persist($calendar);
            $em->flush();

            return new Response('ok', $code);
        } else {
            return new Response('Données incomplètes', 404);
        }
    }

    /**
     * @Route("/recapitulatif", name="recap")
     */
    public function recapitulatif(UsersRepository $repository)
    {
     //   $levels = $repository->getLevelsByUsers($this->getUser());
        $trainings = $repository->getTrainingByLevelsByUsers($this->getUser());
        $planning = $repository->getPlanningByUser($this->getUser());
        if (empty($trainings) && empty($planning)) {
            $this->createNotFoundException('Pas de données');
        }
        $datas = [
            'user' => $this->getUser(),
            'planning' => $planning
        ];
        foreach ($trainings as $training) {
           $datas['trainings'][$training['level']][] = $training['training'];
        }
        if (empty($datas)) {
            $this->createNotFoundException('Pas de données');
        }

//        $rdvs = [];
//        foreach ($planning as $event) {
//            $rdvs[] = [
//
//                'start' => $event['start']->format('Y-m-d H:i:s'),
//                'end' => $event['end']->format('Y-m-d H:i:s'),
//            ];
//        }
//        $hours = json_encode($rdvs);
        //dd($data);
        return $this->render("inscription/recapitulatif.html.twig", [
            'datas' => $datas,
           // 'hours' => $hours
        ]);
    }
}
