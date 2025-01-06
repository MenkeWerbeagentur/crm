<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
   
    public function configureDashboard(): Dashboard
    {
        #[Route('/admin', name: 'admin')]
        public function index(): Response
        {
            // Hier könntest du z. B. auf eine andere Seite weiterleiten
            return $this->render('admin/dashboard.html.twig');
        }
    
        public function configureDashboard(): Dashboard
        {
            return Dashboard::new()
                ->setTitle('Meine Admin-Seite');
        }
    
        public function configureMenuItems(): iterable
        {
            yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
            yield MenuItem::linkToCrud('Benutzer', 'fa fa-user', User::class);
        }
    }
    