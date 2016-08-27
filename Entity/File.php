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
	protected $originalName;

	/**
	 * @ORM\Column(name="filename")
	 */
	protected $filename;
}
