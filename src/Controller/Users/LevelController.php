<?php

namespace App\Controller\Users;

use App\Entity\Training;
use App\Form\TrainingType;
use App\Repository\LevelRepository;
use App\Repository\TrainingRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/users", name="users_")
 * Class LevelController
 * @package App\Controller\users
 */
class LevelController extends AbstractController
{
    /**
     * @Route("/level", name="level", methods={"POST", "GET"})
     * @param UsersRepository $usersRepository
     * @return Response
     */
    public function index(UsersRepository $usersRepository): Response
    {
        $trainings = $usersRepository->getTrainingByLevelsByUsers($this->getUser());

        if (empty($trainings)) {
            $this->createNotFoundException("Vous n'avez pas de formations");
        }

        foreach ($trainings as $key => $training) {
            if ($training['label'] != null) {
                $datas['trainings'][$training['levelId']][$training['level']][$training['trainingId']] = $training['label'];
            }
            if ($training['label_avance'] != null) {
                $datas['trainings'][$training['levelId']][$training['level']][$training['trainingId']] = $training['label_avance'];
            }
            if ($training['label_expert'] != null) {
                $datas['trainings'][$training['levelId']][$training['level']][$training['trainingId']] = $training['label_expert'];
            }
        }

        $form = $this->createForm(TrainingType::class, new Training());


        return $this->render('/users/level/index.html.twig', [
            'datas' => $datas ?? '',
            'form' => $form->createView(),
            'formAvance' => $form->createView(),
            'formExpert' => $form->createView()
        ]);
    }

    /**
     * @Route("/training/{id}/add", name="training_add", methods={"POST", "GET"})
     * @param $id
     * @param Request $request
     * @param LevelRepository $levelRepository
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newTraining($id, Request $request, LevelRepository $levelRepository, EntityManagerInterface $manager)
    {
       $training = new Training();
       $level = $levelRepository->find($id);

      if ($request->getMethod() == 'POST' && !empty($level)) {
          $data = json_decode($request->getContent());

          if ($data->label) {
              $training->setLabel($data->label);
          }

          if ($data->label_avance) {
              $training->setLabelAvance($data->label_avance);
          }
          if ($data->label_expert) {
                $training->setLabelExpert($data->label_expert);
          }

          $training->setLevel($level);
          $manager->persist($training);
          $manager->flush();
      }
       return $this->redirectToRoute('users_level');
    }

    /**
     * @Route("/training/{id}/delete" , name="training_delete")
     * @param $id
     * @param TrainingRepository $trainingRepository
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteTraining($id, TrainingRepository $trainingRepository, EntityManagerInterface $manager)
    {
        $training = $trainingRepository->find($id);
        if ($training) {
            $manager->remove($training);
            $manager->flush();
        }
        return $this->redirectToRoute('users_level');
    }
}
