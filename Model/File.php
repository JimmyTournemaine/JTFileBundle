<?php
namespace JT\FileBundle\Model;

use Symfony\Component\HttpFoundation\File\File;

<<<<<<< HEAD
abstract class File implements UploadableFile
=======
class File implements UploadableFile
>>>>>>> ad591436b40a24ad124f92f9b9ce4ad77bf3920c
{
	protected $originalName;
	protected $filename;
	protected $file;

	public function getAbsolutePathname()
	{
		return $this->getTargetDirectory() . '/' . $this->getFilename();
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

<<<<<<< HEAD
		return $this->file = new File($this->getAbsolutePathname(), true);
	}

=======
		return $this->file = new File($this->getAbsolutePathname(), true)
	}
>>>>>>> ad591436b40a24ad124f92f9b9ce4ad77bf3920c
}


