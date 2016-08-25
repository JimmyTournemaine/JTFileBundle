<?php
namespace JT\FileBundle\Model;

/**
 * @author Jimmy Tournemaine
 */
interface UploadableFile
{
	/**
	 * Get the original name of the file
	 * Save the original name for displaying
	 * @return string
	 */
	public function getOriginalName();

	/**
	 * Get the filename
	 * @return string
	 */
	public function getFilename();

	/**
	 * Get the file
	 * @return File|UploadedFile
	 */
	public function getFile();

	/**
	 * Get the folder where upload the file at persist
	 * @return string
	 */
	public function getTargetDirectory();

	/**
	 * Get the absolute pathname
	 */
	public function getAbsolutePathname();
}


