<?php

namespace App\Controller;

use App\Entity\Thumbnails;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ImagesController extends Controller
{

    public function index()
    {
        return $this->render('images/paris/paris.html.twig', [
            'controller_name' => 'ImagesController',
        ]);
    }



    public function ville($ville)
    {
            $dir = 'img/'.$ville.'/thumbs/';
            $thumbs = $this->getDoctrine()
                ->getRepository(Thumbnails::class)
                ->findByDirname($dir);
           return $this->render('images/'.$ville.'/'.$ville.'.html.twig',[

               'thumbs' => $thumbs,

           ]);
    }

    public function besse()
    {
        return ($this->ville('Besse'));
    }

    public function sigale()
    {
        return ($this->ville('Sigale'));
    }

    public function paris()
    {
        return ($this->ville('Paris'));
    }

}
