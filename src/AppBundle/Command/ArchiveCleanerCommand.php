<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ArchiveCleanerCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('app:archive:clean')
			->setDescription('Remove 7-days-old archives')
		;
	}
	
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$directory = $this->getContainer()->get('app.archive')->getDir();
		if (file_exists($directory) && is_dir($directory)){
			foreach (scandir($directory) as $file)
			{
				if (substr($file, strpos($file, '.')+1) == 'html'){
					$pathname = $directory .'/'. $file;
					if(stat($pathname)['mtime'] < (time() - 7*24*60*60))
						unlink($pathname);
				}
			}
		} else {
			throw new FileException("$directory cannot be accessed.");
		}
	}
}