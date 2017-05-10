<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RequestController extends Controller
{
    /**
     * @Route("/requests", name="requests_list")
     */
    public function indexAction()
    {
        $requests = $this->getDoctrine()->getRepository("AppBundle:Request")->findAll();
        return $this->render('AppBundle:Request:index.html.twig', array(
            'requests' => $requests
        ));
    }

    /**
     * @Route("/requests/create")
     */
    public function createAction()
    {
        $request = new Request();
        $form = $this->createFormBuilder($request)
            ->add('header',TextType::class, array(
                "label" => "Header",
                "attr" => array(
                    'class' => 'form-control'
                )
            ))
            ->add('text',TextType::class, array(
                "label" => "Text",
                "attr" => array(
                    'class' => 'form-control'
                )
            ))
            ->add('category',TextType::class, array(
                "label" => "Category",
                "attr" => array(
                    'class' => 'form-control'
                )
            ))
            ->add('status',EntityType::class, array(
                "class" =>"AppBundle:Status",
                "choice_label" => "status_name",
                "attr" => array(
                    'class' => 'form-control'
                )
            ))
            ->add('submit',SubmitType::class, array(
                "label" => "Add request",
                "attr" => array(
                    "class" => "form-control",
                    "style" => "margin-top: 50px"
                )
            ))->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $header = $form['header']->getData();
            $text = $form['text']->getData();
            $category = $form['category']->getData();

            $request->setHeader($header);
            $request->setText($text);
            $request->setCategory($category);
            $em = $this->getDoctrine()->getManager();
            $em->persist($request);
            $em->flush();

            return $this->redirectToRoute("requests_list");
        }
        return $this->render('AppBundle:Request:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/requests/edit/{id}")
     */
    public function editAction($id, Request $request)
    {
        $event=$this->getDoctrine()->getRepository("AppBundle:Request")->find($id);
        $form = $this->createFormBuilder($request)
            ->add('header',TextType::class, array(
                "label" => "Header",
                "attr" => array(
                    'class' => 'form-control'
                )
            ))
            ->add('text',TextType::class, array(
                "label" => "Text",
                "attr" => array(
                    'class' => 'form-control'
                )
            ))
            ->add('category',TextType::class, array(
                "label" => "Category",
                "attr" => array(
                    'class' => 'form-control'
                )
            ))
            ->add('status',EntityType::class, array(
                "class" =>"AppBundle:Status",
                "choice_label" => "status_name",
                "attr" => array(
                    'class' => 'form-control'
                )
            ))
            ->add('submit',SubmitType::class, array(
                "label" => "Add request",
                "attr" => array(
                    "class" => "form-control",
                    "style" => "margin-top: 50px"
                )
            ))->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $header = $form['header']->getData();
            $text = $form['text']->getData();
            $category = $form['category']->getData();

            $request->setHeader($header);
            $request->setText($text);
            $request->setCategory($category);
            $em = $this->getDoctrine()->getManager();
            $em->persist($request);
            $em->flush();

            return $this->redirectToRoute("requests_list");
        }
        return $this->render('AppBundle:Request:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/requests/delete/{id}")
     */
    public function deleteAction($id)
    {
        $request = $this->getDoctrine()->getRepository("AppBundle:Request")->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($request);
        $em->flush();
        return $this->redirectToRoute("requests_list");
    }

}
