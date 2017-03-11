<?php

declare(strict_types = 1);

namespace Herecsrymy\Console;

use Herecsrymy\Entities\Attachment;
use Herecsrymy\Files\FileUploader;
use Kdyby\Doctrine\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ComputePlaytimeCommand extends Command
{

	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * @var \getID3
	 */
	private $id3;

	/**
	 * @var FileUploader
	 */
	private $uploader;


	public function __construct(EntityManager $em, \getID3 $id3, FileUploader $uploader)
	{
		parent::__construct();
		$this->em = $em;
		$this->id3 = $id3;
		$this->uploader = $uploader;
	}


	protected function configure()
	{
		$this->setName('herecsrymy:compute-playtime');
	}


	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$attachments = $this->em->getRepository(Attachment::class)->findAll();

		/** @var Attachment $attachment */
		foreach ($attachments as $attachment) {
			if ($attachment->type === $attachment::TYPE_AUDIO) {
				$file = reset($attachment->files);
				$path = $this->uploader->getRootPath()
					. DIRECTORY_SEPARATOR
					. $attachment->getDirectoryName()
					. DIRECTORY_SEPARATOR
					. $file->fileName;

				$meta = $this->id3->analyze($path);
				if ($meta['playtime_seconds']) {
					$attachment->playtime = $meta['playtime_seconds'];
				}
			}
		}

		$this->em->flush();
	}

}
