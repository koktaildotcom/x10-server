<?php
namespace ApplianceBundle\Controller;

use ApplianceBundle\Entity\Appliance;
use ApplianceBundle\Entity\Light;
use ApplianceBundle\Repository\ApplianceRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="appliance_list")
     */
    public function indexAction(Request $request)
    {
        /** @var $repository ApplianceRepository */
        $repository = $this->getDoctrine()->getRepository(Appliance::class);

        $appliances = $repository->findAll();

        $formBuilder = $this->createFormBuilder([]);

        foreach($appliances as $appliance){

            $formBuilder->add(
                $appliance->getCode(),
                CheckboxType::class,
                [
                    'label' => $appliance->getName(),
                    'required' => false,
                    'data' => ($appliance->getStatus() === 'on'),
                ]
            );

            if($appliance instanceof Light){
                $formBuilder->add(
                    $appliance->getCode(),
                    RangeType::class,
                    [
                        'label' => $appliance->getName(),
                        'required' => false,
                        'data' => $appliance->getBrightness(),
                        'attr' => [
                            'min' => 0,
                            'max' => 100
                        ]
                    ]
                );
            }
        }

        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach($form->getData() as $code => $status){

                if($status === true){
                    $this->turnOn($code);
                }

                if($status === false){
                    $this->turnOff($code);
                }

                if(is_string($status)){
                    $this->setDimLevel($code, $status);
                }
            }

        }

        return $this->render('ApplianceBundle:Default:index.html.twig',[
            'form' => $form->createView(),
            'appliances' => $appliances,
        ]);
    }

    private function turnOff($code)
    {
        /** @var $repository ApplianceRepository */
        $repository = $this->getDoctrine()->getRepository(Appliance::class);
        $appliance = $repository->getAppliance($code);

        if($appliance->getStatus()==='off'){
            return;
        }
        $appliance->setStatus('off');

        /** @var $em EntityManager */
        $em = $this->getDoctrine()->getManager();

        $command = ' echo "pl ' . $appliance->getCode() . ' off" | nc localhost 1099';

        echo $command . "<br>";
        exec($command);

        $em->persist($appliance);
        $em->flush();
    }

    private function setDimLevel($code, $level)
    {
        /** @var $repository ApplianceRepository */
        $repository = $this->getDoctrine()->getRepository(Light::class);

        /** @var $appliance Light */
        $appliance = $repository->getAppliance($code);

        if($level < $appliance->getBrightness()){
            $action = 'dim';
            $amount = $appliance->getBrightness() - $level;

            $command = ' echo "pl ' . $appliance->getCode() . ' ' . $action . ' ' . $amount . '" | nc localhost 1099';

            echo $command . "<br>";
            exec($command);
        }
        if($level > $appliance->getBrightness()){
            $action = 'bright';
            $amount = $level - $appliance->getBrightness();

            $command = ' echo "pl ' . $appliance->getCode() . ' ' . $action . ' ' . $amount . '" | nc localhost 1099';

            echo $command . "<br>";
            exec($command);
        }

        /** @var $em EntityManager */
        $em = $this->getDoctrine()->getManager();

        $appliance->setStatus('on');
        $appliance->setBrightness($level);

        if($level == 0){
            $this->turnOff($code);
        }

        $em->persist($appliance);
        $em->flush();
    }

    private function turnOn($code)
    {
        /** @var $repository ApplianceRepository */
        $repository = $this->getDoctrine()->getRepository(Appliance::class);
        $appliance = $repository->getAppliance($code);

        if($appliance->getStatus()==='on'){
            return;
        }
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
        $this->turnOn($code);

        return $this->redirectToRoute('appliance_list');
    }

    /**
     * @Route("/off/{code}", name="appliance_off")
     */
    public function offAction($code)
    {
        $this->turnOff($code);

        return $this->redirectToRoute('appliance_list');
    }
}
