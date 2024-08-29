<?php

namespace App\Controller\admin;

use App\Entity\Measure;
use App\Form\MeasureType;
use App\Repository\MeasureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminMesureController extends AbstractController
{
    #[Route('/admin/measure-list', name: 'admin_measure')]
    public function listMeasure(MeasureRepository $measureRepository)
    {
        $measures = $measureRepository->findAll();
        return $this->render('admin/recipe/measure/measure-list.html.twig', [
            'measures' => $measures
        ]);
    }

    #[Route('/admin/measure-delete/{id}', name: 'admin_measure_delete')]
    public function deleteMeasure(EntityManagerInterface $entityManager, MeasureRepository $measureRepository, int $id)
    {
        $measure = $measureRepository->find($id);
        try{
            $entityManager->remove($measure);
            $entityManager->flush();

            $this->addFlash('sucess', 'La mesure à bien été supprimé.');
        }catch (\Exception $e){
            $this->addFlash('error', 'Une erreur est survenue lors de la suppression');

        }
         return $this->redirectToRoute('admin_measure_list');
    }

    #[Route('/admin/measure-add', name: 'admin_measure_add')]
    public function insertMeasure(EntityManagerInterface $entityManager, Request $request)
    {
        $measure = new Measure();

        $measureForm = $this->createForm(MeasureType::class, $measure);

        $measureForm->handleRequest($request);

        if ($measureForm->isSubmitted() && $measureForm->isValid()) {
            $entityManager->persist($measure);
            $entityManager->flush();

            $this->addFlash('sucess', 'La mesure à bien été ajouté.');
            return $this->redirectToRoute('admin_measure');
        }
        return $this->render('admin/recipe/measure/measure-add.html.twig', [
            'measureForm' => $measureForm->createView()
        ]);

    }

    #[Route('/admin/measure-update/{id}', name: 'admin_measure_update')]
    public function updateMeasure(int $id, Request $request, EntityManagerInterface $entityManager,  MeasureRepository $measureRepository){
        $measure = $measureRepository->find($id);
        $measureUpdateForm = $this->createForm(MeasureType::class, $measure);
        $measureUpdateForm->handleRequest($request);

        if($measureUpdateForm->isSubmitted() && $measureUpdateForm->isValid()){
            $entityManager->persist($measure);
            $entityManager->flush();

            $this->addFlash('succes', 'La mesure à bien été mise à jour.');
            return $this->redirectToRoute('admin_category');
        }
        return $this->render('admin/recipe/measure/measure-update.html.twig', [
            'measureUpdateForm' => $measureUpdateForm->createView(),
        ]);
    }
}
