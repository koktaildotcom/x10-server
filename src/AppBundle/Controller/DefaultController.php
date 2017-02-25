<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Appliance;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
//        $repository = $this->getDoctrine()->getRepository(Appliance::class);
//
//        $appliances = $repository->findAll();
//
//
//        dump($appliances);
//        exit;

        return $this->renderView('AppBundle:Index:index.html.twig');
    }
}
