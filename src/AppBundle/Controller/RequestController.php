<?php

namespace AppBundle\Controller;

use AppBundle\Entity\WorkRequest;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;

class RequestController extends Controller
{
    /**
     * @Route("/requests", name="requests_list")
     */
    public function indexAction()
    {
        $workRequests = $this->getDoctrine()->getRepository("AppBundle:WorkRequest")->findAll();
        return $this->render('AppBundle:Request:index.html.twig', array(
            'workRequests' => $workRequests
        ));
    }

    /**
     * @Route("/request/create")
     */
    public function createAction(Request $request)
    {
        $workRequest = new WorkRequest();
        $form = $this->createFormBuilder($workRequest)
            ->add('header',TextType::class, array(
                "label" => "Header",
                "attr" => array(
                    'class' => 'form-control'
                )
            ))
            ->add('text',TextareaType::class, array(
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
            ->add('submit',SubmitType::class, array(
                "label" => "Add request",
                "attr" => array(
                    "class" => "btn btn-success",
                    "style" => "margin-top: 50px"
                )
            ))->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $header = $form['header']->getData();
            $text = $form['text']->getData();
            $category = $form['category']->getData();

            $workRequest->setHeader($header);
            $workRequest->setText($text);
            $workRequest->setCategory($category);
            $workRequest->setStatus($this->getDoctrine()->getRepository("AppBundle:Status")->find(1));
            $em = $this->getDoctrine()->getManager();
            $em->persist($workRequest);
            $em->flush();

            return $this->redirectToRoute("requests_list");
        }
        return $this->render('AppBundle:Request:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/request/edit/{id}")
     */
    public function editAction($id, Request $request)
    {
        $workRequest = $this->getDoctrine()->getRepository("AppBundle:WorkRequest")->find($id);
        $form = $this->createFormBuilder($workRequest)
            ->add('header',TextType::class, array(
                "label" => "Header",
                "attr" => array(
                    'class' => 'form-control'
                )
            ))
            ->add('text',TextareaType::class, array(
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
            ->add('executor', EntityType::class, array(
                "class" =>"AppBundle:User",
                "choice_label" => "username",
                "attr" => array(
                    'class' => 'form-control'
                )
            ))
            ->add('date', DateType::class, array(
                "label" => "Execution date",
            ))
            ->add('status', EntityType::class, array(
                "class" =>"AppBundle:Status",
                "choice_label" => "name",
                "attr" => array(
                    'class' => 'form-control'
                )
            ))
            ->add('submit',SubmitType::class, array(
                "label" => "Save request",
                "attr" => array(
                    "class" => "btn btn-primary",
                    "style" => "margin-top: 50px"
                )
            ))->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($workRequest);
            $em->flush();

            return $this->redirectToRoute("requests_list");
        }
        return $this->render('AppBundle:Request:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/request/delete/{id}")
     */
    public function deleteAction($id)
    {
        $workRequest = $this->getDoctrine()->getRepository("AppBundle:WorkRequest")->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($workRequest);
        $em->flush();
        return $this->redirectToRoute("requests_list");
    }

}
