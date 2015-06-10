<?php

namespace Music\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Music\AdminBundle\Entity\Albums;
use Music\AdminBundle\Form\AlbumsType;
use Music\AdminBundle\Form\SearchType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\File;

/**
 * Albums controller.
 *
 */
class AlbumsController extends Controller
{

    /**
     * Lists all Albums entities.
     *
     */
    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $form = $this->createSearchForm();
        $form->handleRequest($request);
        if($request->getMethod() == 'POST'){
            $word = mb_strtolower($form['search']->getData());
            $_SESSION['admin_search'] = $word;
            $entity = $em->getRepository('MusicAdminBundle:Albums')->search($word);
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $entity,
                $request->query->get('page', 1),2
            );
            return $this->render('MusicAdminBundle:Albums:index.html.twig', array(
                'pagination' => $pagination,
                'form' => $form->createView(),
            ));
        }
        if(isset($_SESSION['admin_search']) and !empty($_SESSION['admin_search'])){
            $entity = $em->getRepository('MusicAdminBundle:Albums')->search($_SESSION['search']);
        }else{
            $entity = $em->getRepository('MusicAdminBundle:Albums')->findAll('DESC');
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entity,
            $request->query->get('page', 1),2
        );

        return $this->render('MusicAdminBundle:Albums:index.html.twig', array(
            'pagination' => $pagination,
            'form' => $form->createView(),
        ));
    }

    private function createSearchForm(){
        $form = $this->createForm(new SearchType(), array(
            'action' => $this->generateUrl('admin_homepage'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Search'));

        return $form;
    }
    /**
     * Creates a new Albums entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Albums();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $date = new \DateTime();
            $dir = $this->get('kernel')->getRootDir().'/../web/files';
            $file_psevdo = uniqid().'.mp3';
            $file = new File(array('mimeTypes' => array("audio/mpeg")));
            $file_name = $entity->getTrackName();
            $file_name = strtolower($this->translit($file_name).'.mp3');
            $form['file_name']->getData()->move($dir, $file_psevdo);
            $group_name = mb_strtolower($form['group_name']->getData());
            $track_name = mb_strtolower($form['track_name']->getData());
            $entity->setGroupName($group_name);
            $entity->setTrackName($track_name);
            $entity->setFileName($file_name);
            $entity->setFilePsevdo($file_psevdo);
            $entity->setUpdatedAt($date);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_albums_show', array('id' => $entity->getId())));
        }

        return $this->render('MusicAdminBundle:Albums:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    private function translit($str)
    {
        $tr = array(
            "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
            "Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
            "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
            "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
            "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
            "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
            "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
            "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
            "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
            "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
            "ы"=>"yi","ь"=>"'","э"=>"e","ю"=>"yu","я"=>"ya",
            "."=>"_"," "=>"_","?"=>"_","/"=>"_","\\"=>"_",
            "*"=>"_",":"=>"_","*"=>"_","\""=>"_","<"=>"_",
            ">"=>"_","|"=>"_"
        );
        return strtr($str,$tr);
    }

    /**
     * Creates a form to create a Albums entity.
     *
     * @param Albums $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Albums $entity)
    {
        $form = $this->createForm(new AlbumsType(), $entity, array(
            'action' => $this->generateUrl('admin_albums_create'),
            'method' => 'POST',
        ));

        $form->add('file_name', 'file');
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Albums entity.
     *
     */
    public function newAction()
    {
        $entity = new Albums();
        $form   = $this->createCreateForm($entity);

        return $this->render('MusicAdminBundle:Albums:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Albums entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MusicAdminBundle:Albums')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Albums entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MusicAdminBundle:Albums:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Albums entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MusicAdminBundle:Albums')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Albums entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MusicAdminBundle:Albums:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Albums entity.
    *
    * @param Albums $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Albums $entity)
    {
        $form = $this->createForm(new AlbumsType(), $entity, array(
            'action' => $this->generateUrl('admin_albums_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Albums entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MusicAdminBundle:Albums')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Albums entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $date = new \DateTime();
            $group_name = mb_strtolower($entity->getGroupName());
            $track_name = mb_strtolower($entity->getTrackName());
            $entity->setTrackName($track_name);
            $track_name = $this->translit($track_name);
            $track_name .= '.mp3';
            $entity->setFileName($track_name);
            $entity->setGroupName($group_name);
            $entity->setUpdatedAt($date);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_albums_edit', array('id' => $id)));
        }

        return $this->render('MusicAdminBundle:Albums:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Albums entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MusicAdminBundle:Albums')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Albums entity.');
            }

            $dir = $this->get('kernel')->getRootDir().'/../web/files';
            $file_name = $entity->getFilePsevdo();
            $file = $dir.'/'.$file_name;
            $em->remove($entity);
            $em->flush();
            unlink($file);
        }

        return $this->redirect($this->generateUrl('admin_albums'));
    }

    /**
     * Creates a form to delete a Albums entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_albums_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
