<?php
namespace JT\FileBundle\Model;

abstract class File implements UploadableFile
{
	protected $id;
	protected $originalName;
	protected $filename;
	protected $file;

	public function __toString()
	{
	    return $this->originalName;
	}

	public function getWebPathname()
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
		return $this->file;
	}

	public function setFile(\Symfony\Component\HttpFoundation\File\File $file)
	{
	    $this->file = $file;
	    return $this;
	}

}


