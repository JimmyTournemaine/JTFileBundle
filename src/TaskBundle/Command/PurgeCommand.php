<?php
namespace TaskBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PurgeCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('task:purge')
			->setDescription('Purge over-30-days tasks')
		;
	}
	
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$em = $this->getContainer()->get('doctrine.orm.entity_manager');
		$results = $em
			->createQuery("SELECT t FROM TaskBundle:Task t WHERE t.status = true AND t.deadline <= CURRENT_TIMESTAMP()-2592000")
			->getResult()
		;
		$output->writeln(sprintf("The system is removing %d tasks.", sizeof($results)));
		foreach ($results as $result){
			$em->remove($result);
		}
		$em->flush();
		$output->writeln("Done.");
	}
}