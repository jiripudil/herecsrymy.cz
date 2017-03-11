<?php

declare(strict_types = 1);

namespace Herecsrymy\FrontModule\Components\Player;

use Herecsrymy\Entities\Attachment;
use Herecsrymy\Entities\File;
use Herecsrymy\Entities\Queries\PlayableAttachmentsQuery;
use Herecsrymy\Files\FileUploader;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;
use Nette\Http\IRequest;
use Nette\Utils\Html;
use Nette\Utils\Json;


class PlayerControl extends Control
{

	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * @var IRequest
	 */
	private $httpRequest;

	/**
	 * @var FileUploader
	 */
	private $uploader;


	public function __construct(EntityManager $em, IRequest $httpRequest, FileUploader $uploader)
	{
		$this->em = $em;
		$this->httpRequest = $httpRequest;
		$this->uploader = $uploader;
	}


	public function render()
	{
		$query = new PlayableAttachmentsQuery();
		$attachments = $this->em->getRepository(Attachment::class)->fetch($query);

		$songs = array_map(function (Attachment $attachment) {
			$path = $this->httpRequest->getUrl()->getBasePath()
				. $this->uploader->getDirName()
				. DIRECTORY_SEPARATOR
				. $attachment->getDirectoryName();

			return [
				'id' => $attachment->getId(),
				'src' => array_map(function (File $file) use ($path) {
					return $path . DIRECTORY_SEPARATOR . $file->fileName;
				}, $attachment->files),
				'title' => $attachment->post->title,
				'playtime' => $attachment->playtime,
			];
		}, array_values(iterator_to_array($attachments)));

		echo Html::el('div', [
			'id' => 'audio-player',
			'data-songs' => Json::encode($songs),
		]);
	}

}
