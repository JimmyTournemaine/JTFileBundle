<?php
namespace JT\FileBundle\Model;

use Symfony\Component\HttpFoundation\File\File;

abstract class File implements UploadableFile
{
	protected $id;
	protected $originalName;
	protected $filename;
	protected $file;

	public function getAbsolutePathname()
	{
		return $this->getTargetDirectory() . '/' . $this->getFilename();
	}

	public function getId()
	{
		return $this->id;
	}

	public function setOriginalName($name)
	{
		$this->originalName = $name;
		return $this;
	}

	public function getOriginalName()
	{
		return $this->originalName;
	}

	public function setFilename($filename)
	{
		$this->filename = $filename;
		return $this;
	}

	public function getFilename()
	{
		return $this->filename;
	}

	public function getFile()
	{
		if($this->file !== null){
			return $this->file;
		}

		return $this->file = new File($this->getAbsolutePathname(), true);
	}

}


