<?php


namespace Music\AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller{

    public function indexAction(){
        return $this->render('MusicAdminBundle:Default:index.html.twig');
    }
}