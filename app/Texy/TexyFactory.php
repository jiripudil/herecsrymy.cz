<?php

namespace Herecsrymy\Texy;

use Nette\Object;
use Texy;


class TexyFactory extends Object
{

	/**
	 * @return Texy\Texy
	 */
	public function create()
	{
		$texy = new Texy\Texy();

		$texy->setOutputMode(Texy\Texy::HTML5);
		$texy->allowedTags = Texy\Texy::ALL;
		$texy->headingModule->top = 2;

		$texy->allowed['phrase/cite'] = TRUE;
		$texy->addHandler('phrase', [$this, 'chordify']);

		return $texy;
	}


	/**
	 * @param Texy\HandlerInvocation $invocation
	 * @param string $phrase
	 * @param string $content
	 * @param Texy\Modifier $modifier
	 * @param Texy\Link|NULL $link
	 * @return Texy\HtmlElement|string|FALSE
	 */
	public function chordify(Texy\HandlerInvocation $invocation, $phrase, $content, $modifier, $link)
	{
		if ($phrase !== 'phrase/cite') {
			return $invocation->proceed();
		}

		$el = Texy\HtmlElement::el('span');
		$el->class = 'chord';

		$strong = $el->create('strong');
		$strong->create('span')->setText('[');
		$strong->add($content);
		$strong->create('span')->setText(']');

		$modifier->decorate($invocation->getTexy(), $el);

		return $el;
	}

}
