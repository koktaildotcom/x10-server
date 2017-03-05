<?php
namespace ApplianceBundle\Controller;

use ApplianceBundle\Entity\Appliance;
use ApplianceBundle\Repository\ApplianceRepository;
use Doctrine\ORM\EntityManager;
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

    private function turnOff(Appliance $appliance)
    {
        $appliance->setStatus('off');

        /** @var $em EntityManager */
        $em = $this->getDoctrine()->getManager();

        $command = ' echo "pl ' . $appliance->getCode() . ' off" | nc localhost 1099';

        echo $command . "<br>";
        exec($command);

        $em->persist($appliance);
        $em->flush();
    }

    private function turnOn(Appliance $appliance)
    {
        $appliance->setStatus('on');

        /** @var $em EntityManager */
        $em = $this->getDoctrine()->getManager();

        $command = ' echo "pl ' . $appliance->getCode() . ' on" | nc localhost 1099';

        echo $command . "<br>";
        exec($command);

        $em->persist($appliance);
        $em->flush();
    }

    /**
     * @Route("/on/{code}", name="appliance_on")
     */
    public function onAction($code)
    {
        /** @var $repository ApplianceRepository */
        $repository = $this->getDoctrine()->getRepository(Appliance::class);
        $appliance = $repository->getAppliance($code);

        $this->turnOn($appliance);

        return $this->redirectToRoute('appliance_list');
    }

    /**
     * @Route("/off/{code}", name="appliance_off")
     */
    public function offAction($code)
    {
        /** @var $repository ApplianceRepository */
        $repository = $this->getDoctrine()->getRepository(Appliance::class);
        $appliance = $repository->getAppliance($code);

        $this->turnOff($appliance);

        return $this->redirectToRoute('appliance_list');
    }
}
