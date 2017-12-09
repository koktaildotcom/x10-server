<?php
namespace ApplianceBundle\Command;

use ApplianceBundle\Entity\Appliance;
use ApplianceBundle\Repository\ApplianceRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use DateTime;

class RunCronCommand extends ContainerAwareCommand
{
    // ...
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('cron:run')
            // the short description shown while running "php bin/console list"
            ->setDescription('Run the cronjob.')
            ->addArgument('code', InputArgument::REQUIRED)
            ->addArgument('on', InputArgument::REQUIRED)
            ->addArgument('off', InputArgument::REQUIRED);
    }


    private function turnOff(Appliance $appliance)
    {
        $appliance->setStatus('off');

        /** @var $em EntityManager */
        $em = $this->getContainer()->get('doctrine')->getManager();

        $command = ' echo "pl ' . $appliance->getCode() . ' off" |  /usr/local/bin/nc localhost 1099';

        echo $command . "<br>";
        exec($command);

        $em->persist($appliance);
        $em->flush();
    }

    private function turnOn(Appliance $appliance)
    {
        $appliance->setStatus('on');

        /** @var $em EntityManager */
        $em = $this->getContainer()->get('doctrine')->getManager();

        $command = ' echo "pl ' . $appliance->getCode() . ' on" | /usr/local/bin/nc localhost 1099';

        echo $command . "<br>";
        exec($command);

        $em->persist($appliance);
        $em->flush();
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $code = $input->getArgument('code');
        $on = $input->getArgument('on');
        $off = $input->getArgument('off');

        /** @var $em EntityManager */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var $repository ApplianceRepository */
        $repository = $em->getRepository(Appliance::class);

        $appliance = $repository->getAppliance($code);

	if(null === $appliance){
           $output->writeln(sprintf('@%s No appliance found for code:%s.', $now->format("d-m-Y H:i:s"), $code));
           exit;
	}

        $now = new DateTime();

        $start = new DateTime();
        $start->setTime($on,0);

        $end = new DateTime();
        $end->setTime($off,0);

        if ($start <= $now && $end >= $now) {
            $output->writeln(sprintf('@%s The appliance %s is in the active range.', $now->format("d-m-Y H:i:s"), $appliance->getName()));
            if ($appliance->getStatus() === 'off') {
                $this->turnOn($appliance);
                $output->writeln(sprintf('The appliance %s is switched on.', $appliance->getName()));
            }
        } else {
            $output->writeln(sprintf('@%s The appliance %s is in the inactive range.', $now->format("d-m-Y H:i:s"), $appliance->getName()));
            if ($appliance->getStatus() === 'on') {
                $this->turnOff($appliance);
                $output->writeln(sprintf('The appliance %s is switched off.', $appliance->getName()));
            }
        }

    }
}