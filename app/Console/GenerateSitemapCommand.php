<?php

namespace Herecsrymy\Console;

use Herecsrymy\Sitemap\SitemapGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class GenerateSitemapCommand extends Command
{

	/** @var SitemapGenerator */
	private $generator;


	public function __construct(SitemapGenerator $generator)
	{
		parent::__construct();
		$this->generator = $generator;
	}


	protected function configure()
	{
		$this->setName('herecsrymy:generate-sitemap');
	}


	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->generator->generateSitemap();
	}

}
