<?php
namespace JT\FileBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
<<<<<<< HEAD
use JT\Filebundle\Uploader\FileDeleter;
use JT\Filebundle\Uploader\FileUploader;
use Doctrine\ORM\Event\PreUpdateEventArgs;
=======
>>>>>>> ad591436b40a24ad124f92f9b9ce4ad77bf3920c

class FileToUploadListener
{
	private $uploader;
<<<<<<< HEAD
	private $deleter;

	public function __construct(FileUploader $uploader, FileDeleter $deleter)
	{
		$this->uploader = $uploader;
		$this->deleter = $deleter;
=======

	public function __construct(FileUploader $uploader)
	{
		$this->uploader = $uploader;
>>>>>>> ad591436b40a24ad124f92f9b9ce4ad77bf3920c
	}

	public function prePersist(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();
		if(!$entity instanceof FileInterface) {
			return;
		}

		$this->uploader->upload($entity);
	}

	public function preUpdate(PreUpdateEventArgs $args)
	{
		$entity = $args->getEntity();
		if(!$entity instanceof FileInterface) {
			return;
		}

		$this->uploader->upload($entity);		
	}

	public function postRemove(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();
		$entity = $args->getEntity();
		if(!$entity instanceof FileInterface) {
			return;
		}

		$this->uploader->remove($entity);
	}

}
