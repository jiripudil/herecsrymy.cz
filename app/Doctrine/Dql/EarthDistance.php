<?php

namespace Slovotepec\Doctrine\Dql;

use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use Doctrine\ORM\Query;
use Slovotepec\NotImplementedException;


/**
 * EarthDistanceFunction ::= "DISTANCE" "(" point1 "," point2 ")"
 */
class EarthDistance extends Query\AST\Functions\FunctionNode
{

	/** @var Query\AST\Node */
	private $from;

	/** @var Query\AST\Node */
	private $to;


	public function getSql(Query\SqlWalker $sqlWalker)
	{
		$platform = $sqlWalker->getConnection()->getDatabasePlatform();

		if ($platform instanceof PostgreSqlPlatform) {
			return $this->from->dispatch($sqlWalker)
				. " <@> "
				. $this->to->dispatch($sqlWalker);
		}

		throw new NotImplementedException;
	}


	public function parse(Query\Parser $parser)
	{
		$parser->match(Query\Lexer::T_IDENTIFIER);
		$parser->match(Query\Lexer::T_OPEN_PARENTHESIS);

		$this->from = $parser->ArithmeticPrimary();
		$parser->match(Query\Lexer::T_COMMA);
		$this->to = $parser->ArithmeticPrimary();

		$parser->match(Query\Lexer::T_CLOSE_PARENTHESIS);
	}

}
