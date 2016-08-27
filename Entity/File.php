<?php
namespace JT\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JT\FileBundle\Model\File as BaseFile;

/**
 * @ORM\MappedSuperclass
 */
class File extends BaseFile
{
	/**
	 * @ORM\Column(name="original")
	 */
<<<<<<< HEAD
	protected $originalName;
=======
	protected originalName;
>>>>>>> ad591436b40a24ad124f92f9b9ce4ad77bf3920c

	/**
	 * @ORM\Column(name="filename")
	 */
<<<<<<< HEAD
	protected $filename;
=======
	protected filename;
>>>>>>> ad591436b40a24ad124f92f9b9ce4ad77bf3920c
}
