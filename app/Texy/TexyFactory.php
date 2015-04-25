<?php

namespace Herecsrymy\Texy;

use Nette\Object;


class TexyFactory extends Object
{

	/**
	 * @return \Texy
	 */
	public function create()
	{
		$texy = new \Texy();

		$texy->setOutputMode(\Texy::HTML5);
		$texy->allowedTags = \Texy::ALL;
		$texy->headingModule->top = 2;

		$texy->allowed['phrase/cite'] = TRUE;
		$texy->addHandler('phrase', [$this, 'chordify']);

		return $texy;
	}


	/**
	 * @param \TexyHandlerInvocation $invocation
	 * @param string $phrase
	 * @param string $content
	 * @param \TexyModifier $modifier
	 * @param \TexyLink|NULL $link
	 * @return \TexyHtml|string|FALSE
	 */
	public function chordify(\TexyHandlerInvocation $invocation, $phrase, $content, $modifier, $link)
	{
		if ($phrase !== 'phrase/cite') {
			return $invocation->proceed();
		}

		$el = \TexyHtml::el('span');
		$strong = $el->create('strong');
		$strong->create('span')->setText('[');
		$strong->add($content);
		$strong->create('span')->setText(']');

		$modifier->decorate($invocation->getTexy(), $el);

		return $el;
	}

}
