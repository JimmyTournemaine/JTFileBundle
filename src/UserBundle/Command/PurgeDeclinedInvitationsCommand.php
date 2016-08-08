<?php
namespace UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PurgeDeclinedInvitationsCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('user:invitations:purge')
			->setDescription('Purge all declined invitations sent for 24 hours ago')
		;
	}
	
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$em = $this->getContainer()->get('doctrine.orm.entity_manager');
		$results = $em
			->createQuery("SELECT i FROM UserBundle:Invitation i WHERE i.status = false AND i.sendAt < CURRENT_TIMESTAMP()-86400")
			->getResult()
		;
		foreach ($results as $result){
			$em->remove($result);
		}
		$em->flush();
	}
}