<?php
namespace JT\FileBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;

class FileToUploadListener
{
	private $uploader;

	public function __construct(FileUploader $uploader)
	{
		$this->uploader = $uploader;
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
