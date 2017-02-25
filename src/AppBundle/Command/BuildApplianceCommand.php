<?php
namespace AppBundle\Command;

use AppBundle\Entity\Appliance;
use AppBundle\Entity\Light;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildApplianceCommand extends ContainerAwareCommand
{
    // ...
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('build:appliance')

            // the short description shown while running "php bin/console list"
            ->setDescription('Creates new appliance.')

            ->addArgument('type', InputArgument::REQUIRED)

            ->addArgument('name', InputArgument::REQUIRED)

            ->addArgument('code', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $type = $input->getArgument('type');
        $code = $input->getArgument('code');

        switch($type){
            case 'light':
                $appliance = new Light();
                break;
            default:
                $appliance = new Appliance();
        }

        $appliance->setName($name);
        $appliance->setCode($code);

        /** @var $em EntityManager */
        $em = $this->getContainer()->get('doctrine')->getManager();

        $em->persist($appliance);
        $em->flush();

        $output->writeln(sprintf('The appliance %s is build.', $type));
    }
}