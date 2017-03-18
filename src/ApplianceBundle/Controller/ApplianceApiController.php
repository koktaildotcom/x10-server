<?php
namespace ApplianceBundle\Controller;

use ApplianceBundle\Entity\Appliance;
use ApplianceBundle\Entity\Light;
use ApplianceBundle\Repository\ApplianceRepository;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\FOSRestController;;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplianceApiController extends FOSRestController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function getAppliancesAction(Request $request)
    {
        /** @var $repository ApplianceRepository */
        $repository = $this->getDoctrine()->getRepository(Appliance::class);

        $appliances = $repository->findAll();

        $view = $this->view($appliances, 200);

        return $this->handleView($view);
    }

    /**
     * @param $slug
     * @return Response
     */
    public function getApplianceOffAction($slug)
    {
        /** @var $repository ApplianceRepository */
        $repository = $this->getDoctrine()->getRepository(Appliance::class);
        $appliance = $repository->getAppliance($slug);

        if($appliance->getStatus()==='off'){
            return new Response(json_encode([$appliance->getCode() => 'failed']), 200);
        }
        $appliance->setStatus('off');

        /** @var $em EntityManager */
        $em = $this->getDoctrine()->getManager();

        $command = ' echo "pl ' . $appliance->getCode() . ' off" | nc localhost 1099';

        exec($command);

        $em->persist($appliance);
        $em->flush();

        return new Response(json_encode([$appliance->getCode() => 'success']), 200);
    }

    /**
     * @param $slug
     *
     * @return Response
     */
    public function getApplianceOnAction($slug)
    {
        /** @var $repository ApplianceRepository */
        $repository = $this->getDoctrine()->getRepository(Appliance::class);
        $appliance = $repository->getAppliance($slug);

        if($appliance->getStatus()==='on'){
            return new Response(json_encode([$appliance->getCode() => 'failed']), 200);
        }
        $appliance->setStatus('on');

        /** @var $em EntityManager */
        $em = $this->getDoctrine()->getManager();

        $command = ' echo "pl ' . $appliance->getCode() . ' on" | nc localhost 1099';

        exec($command);

        $em->persist($appliance);
        $em->flush();

        return new Response(json_encode([$appliance->getCode() => 'success']), 200);
    }
}
