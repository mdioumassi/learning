<?php

namespace App\Controller\Api;

use App\Form\CalendarType;
use DateTime;
use App\Entity\Calendar;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/{id}/edit", name="api_event_edit", methods={"PUT"})
     */
    public function majEvent(?Calendar $calendar, Request $request, EntityManagerInterface $em): Response
    {
        $donnees = json_decode($request->getContent());
        if (
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->end) && !empty($donnees->end) &&
         //   isset($donnees->description) && !empty($donnees->description) &&
            isset($donnees->backgroundColor) && !empty($donnees->backgroundColor)
          //  isset($donnees->borderColor) && !empty($donnees->borderColor) &&
          //  isset($donnees->textColor) && !empty($donnees->textColor)
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
//            if ($donnees->allDay) {
//                $calendar->setEnd(new DateTime($donnees->end));
//            }
           //$calendar->setAllDay($donnees->allDay);
            $calendar->setBackgroundColor($donnees->backgroundColor);
//            $calendar->setBorderColor($donnees->borderColor);
//            $calendar->setTextColor($donnees->textColor);

            $em->persist($calendar);
            $em->flush();

            return new Response('ok', $code);
        } else {
            return new Response('Données incomplètes', 404);
        }
    }

    /**
     * @Route("/api/events/new", name="api_event_new", methods={"POST"})
     */
    public function newEvents(Request $request, EntityManagerInterface $manager)
    {
          $calendar = new Calendar();
          $form = $this->createForm(CalendarType::class);
          $donnees = json_decode($request->getContent());
        if (
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->end) && !empty($donnees->end) &&
            isset($donnees->backgroundColor) && !empty($donnees->backgroundColor)
        ) {
            $calendar->setTitle($donnees->title);
            $calendar->setStart(new DateTime($donnees->start));
            $calendar->setEnd(new DateTime($donnees->end));
            $calendar->setBackgroundColor($donnees->backgroundColor);

            $manager->persist($calendar);
            $manager->flush();

            return new Response('ok', 200);
        } else {
            return new Response('Données incomplètes', 404);
        }
    }
}
