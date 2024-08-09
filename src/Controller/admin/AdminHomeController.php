<?php
namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminHomeController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(){
        return $this->render('admin/admin-profil.html.twig');
    }
}