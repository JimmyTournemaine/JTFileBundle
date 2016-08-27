<?php
namespace JT\FileBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use JT\Filebundle\Uploader\FileDeleter;
use JT\Filebundle\Uploader\FileUploader;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class FileToUploadListener
{
	private $uploader;
	private $deleter;

	public function __construct(FileUploader $uploader, FileDeleter $deleter)
	{
		$this->uploader = $uploader;
		$this->deleter = $deleter;
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
