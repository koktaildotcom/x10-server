<?php
namespace ApplianceBundle\Controller;

use ApplianceBundle\Entity\Appliance;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository(Appliance::class);

        $appliances = $repository->findAll();


        dump($appliances);
        exit;

        return $this->render('ApplianceBundle:Default:index.html.twig');
    }
}
