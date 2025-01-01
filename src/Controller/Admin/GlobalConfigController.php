<?php

namespace App\Controller\Admin;

use App\Form\GlobalConfigType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_SUPER_ADMIN')]
class GlobalConfigController extends AbstractController
{
    #[Route('/admin/config', name: 'app_admin_global_config')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(GlobalConfigType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'common.success');
            return $this->redirectToRoute('app_admin_global_config');
        }

        return $this->render('pages/admin/config/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
