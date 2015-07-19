<?php

/**
 * @testCase
 */

namespace HerecsrymyTests\Texy;

use Herecsrymy\Texy\TexyFactory;
use Tester\Assert;
use Tester\TestCase;


require_once __DIR__ . '/../../bootstrap.php';


class TexyTest extends TestCase
{

	public function testChordify()
	{
		/** @var \Texy $texy */
		$texy = (new TexyFactory())->create();

		$output = $texy->process(file_get_contents(__DIR__ . '/chordify.texy'));
		Assert::same(file_get_contents(__DIR__ . '/chordify.html'), $output);
	}

}


(new TexyTest())->run();
