<?php
namespace ApplianceBundle\Controller;

use ApplianceBundle\Entity\Appliance;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="appliance_list")
     */
    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Appliance::class);

        $appliances = $repository->findAll();
        
        return $this->render('ApplianceBundle:Default:index.html.twig',
            [
                'appliances' => $appliances,
            ]
        );
    }

    /**
     * @Route("/on/{code}", name="appliance_on")
     */
    public function onAction($code)
    {
        $repository = $this->getDoctrine()->getRepository(Appliance::class);

        
        $command = ' echo "pl ' . $code . ' on" | nc localhost 1099';

        echo $command . "\n";
        exec($command);


        $appliances = $repository->findAll();

        return $this->render('ApplianceBundle:Default:index.html.twig',
            [
                'appliances' => $appliances,
            ]
        );
    }

    /**
     * @Route("/off/{code}", name="appliance_off")
     */
    public function offAction($code)
    {
        $repository = $this->getDoctrine()->getRepository(Appliance::class);


        $command = ' echo "pl ' . $code . ' off" | nc localhost 1099';

        echo $command . "\n";
        exec($command);


        $appliances = $repository->findAll();

        return $this->render('ApplianceBundle:Default:index.html.twig',
            [
                'appliances' => $appliances,
            ]
        );
    }
}
