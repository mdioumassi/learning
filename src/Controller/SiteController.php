<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/site", name="homepage")
     */
    public function index()
    {
        return $this->render('site/index.html.twig', [
            'controller_name' => 'HomepageController',
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contact()
    {
        return $this->render('site/contact.html.twig');
    }
}
