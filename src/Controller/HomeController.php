<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @Route("/",name="root") 
     */
    public function root()
    {
        return $this->redirectToRoute('home');
    }
    
    /**
     * @Route("/home", name="home")
     * @Route("/home/{page}", name="home_paginated")
     */
    public function index(ProductRepository $productRepo, $page = 1)
    {
        $products = $productRepo->findPaginated($page);
        return $this->render("home.html.twig", [
            'products' => $products
        ]);
    }
}

