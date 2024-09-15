<?php
namespace App\Controller\admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminHomeController extends AbstractController
{
    #[Route('/admin/', name: 'admin')]
    public function profilAdmin(){
        $admin = $this->getUser();
        return $this->render('admin/admin-profil.html.twig',[
            'admin' => $admin
        ]);
    }
}