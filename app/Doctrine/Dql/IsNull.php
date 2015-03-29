<?php

namespace Slovotepec\Doctrine\Dql;

use Doctrine\ORM\Query;


class IsNull extends Query\AST\Functions\FunctionNode
{

	private $expression;


	public function getSql(Query\SqlWalker $sqlWalker)
	{
		return $sqlWalker->walkArithmeticPrimary($this->expression) . " IS NULL";
	}


	public function parse(Query\Parser $parser)
	{
		$parser->match(Query\Lexer::T_IDENTIFIER);
		$parser->match(Query\Lexer::T_OPEN_PARENTHESIS);

		$this->expression = $parser->ArithmeticPrimary();

		$parser->match(Query\Lexer::T_CLOSE_PARENTHESIS);
	}

}
