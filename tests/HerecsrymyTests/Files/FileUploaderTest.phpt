<?php

/**
 * @testCase
 */

namespace HerecsrymyTests\Files;

use Herecsrymy\Entities\Attachment;
use Herecsrymy\Entities\Post;
use Herecsrymy\Files\FileUploader;
use Nette\Http\FileUpload;
use Tester\Assert;
use Tester\Helpers;
use Tester\TestCase;


require_once __DIR__ . '/../../bootstrap.php';


class FileUploaderTest extends TestCase
{

	protected function setUp()
	{
		Helpers::purge(__DIR__ . '/files');
		file_put_contents(__DIR__ . '/files/readme.txt', 'foo bar baz\n');

		Helpers::purge(__DIR__ . '/uploads');
	}


	public function testFileUpload()
	{
		$size = filesize(__DIR__ . '/files/readme.txt');
		$upload = new FileUpload([
			'name' => 'readme.txt',
			'type' => 'text/plain',
			'size' => $size,
			'tmp_name' => __DIR__ . '/files/readme.txt',
			'error' => UPLOAD_ERR_OK,
		]);

		$post = new Post('Title');
		$attachment = new Attachment('Foo', Attachment::TYPE_DOCUMENT, $post);

		$idProp = (new \ReflectionClass(Attachment::class))->getProperty('id');
		$idProp->setAccessible(TRUE);
		$idProp->setValue($attachment, 42);

		$uploader = new FileUploader(__DIR__, 'uploads', new \getID3());
		$file = $uploader->upload($upload, $attachment);

		Assert::same('readme.txt', $file->fileName);
		Assert::same('text/plain', $file->fileType);
		Assert::same($size, $file->fileSize);

		Assert::true(file_exists($path = __DIR__ . '/uploads/a1d0/readme.txt'));
		Assert::same('foo bar baz\n', file_get_contents($path));
	}

}


(new FileUploaderTest())->run();
