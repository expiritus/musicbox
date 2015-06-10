<?php

namespace Music\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Music\AdminBundle\Form\SearchType;

class DefaultController extends Controller
{
    /**
     * @Template("MusicMainBundle:Default:index.html.twig")
     */
    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $form = $this->createSearchForm();
        $form->handleRequest($request);
        if($request->getMethod() == 'POST'){

            $word = mb_strtolower($form['search']->getData());
            $_SESSION['search'] = $word;
            $entity = $em->getRepository('MusicAdminBundle:Albums')->search($_SESSION['search']);
//            $category = $em->getRepository('MusicAdminBundle:Category')->findAll();
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $entity,
                $request->query->get('page', 1),3
            );
            return array(
//                'category' => $category,
                'pagination' => $pagination,
                'form' => $form->createView(),
            );
        }

        if($request->getMethod() == 'GET'){
                $word = mb_strtolower($form['search']->getData());
                $entity = $em->getRepository('MusicAdminBundle:Albums')->search($word);
                $paginator = $this->get('knp_paginator');
                $pagination = $paginator->paginate(
                    $entity,
                    $request->query->get('page', 1),3
                );
                return array(
    //                'category' => $category,
                    'pagination' => $pagination,
                    'form' => $form->createView(),
                );
        }else{
            $entity = $em->getRepository('MusicAdminBundle:Albums')->findAll('DESC');
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $entity,
                $request->query->get('page', 1), 3
            );
            return array(
//                'category' => $category,
                'pagination' => $pagination,
                'form' => $form->createView(),
            );
        }


//            $category = $em->getRepository('MusicAdminBundle:Category')->findAll();

//        }
    }

    private function createSearchForm(){
        $form = $this->createForm(new SearchType(), array(
            'action' => $this->generateUrl('main_homepage'),
            'method' => 'GET',
        ));
        $form->add('submit', 'submit',
            array(
                'attr' => array(
                    'class' => 'search_submit'
                ),
                'label' => 'Search'
            ));
        return $form;
    }

    public function downloadAction($psevdo, $name){
        $response = new Response();

// Set headers
        $filename = $this->get('kernel')->getRootDir().'/../web/files/'.$psevdo;
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', mime_content_type($filename));
        $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($name) . '";');
        $response->headers->set('Content-length', filesize($filename));

// Send headers before outputting anything
        $response->sendHeaders();

        $response->setContent(readfile($filename));
    }

}
