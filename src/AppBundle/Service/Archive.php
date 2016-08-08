<?php
namespace AppBundle\Service;

use Symfony\Bundle\TwigBundle\TwigEngine;
use Doctrine\ORM\EntityManager;
use UserBundle\Entity\Team;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Debug\Exception\ContextErrorException;

class Archive
{
	private $em;
	private $dir;
	private $templating;
	
	public function __construct(EntityManager $manager, TwigEngine $twig, $webDir)
	{
		$this->em = $manager;
		$this->templating = $twig;
		$this->dir = $webDir;
	}
	
	public function generate(Team $team)
	{
		$companies = $this->em->getRepository("AppBundle:Company")->findByTeam($team);
		$uniqueId = str_replace('.', '-', uniqid("archive-"));
		$filename = $this->getFilename($uniqueId);
		
		/* Try to access archives directory */
		if (!file_exists($this->dir)) {
			throw new FileException("Cannot access $this->dir directory.");
		}
		
		$file = fopen($filename, "w");
		$view = $this->templating->render('team/archive.html.twig', array(
				'team' => $team,
				'companies' => $companies,
		));
		fwrite($file, $view);
		
		return $uniqueId;
	}
	
	/**
	 * Get the HTML file content for the unique ID
	 * 
	 * @param int|string $uniqueId
	 */
	public function getHtml($uniqueId)
	{
		$filename = $this->getFilename($uniqueId);
		if (!file_exists($filename))
			return null;
		$file = fopen($filename, "r");
		
		return fread($file, filesize($filename));
	}
	
	private function getFilename($id)
	{
		return "$this->dir/$id.html";
	}
	
	public function getDir() {
		return $this->dir;
	}
	
	
	
}