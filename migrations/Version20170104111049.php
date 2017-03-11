<?php

namespace Herecsrymy\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;


class Version20170104111049 extends AbstractMigration
{

	public function up(Schema $schema)
	{
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
		$this->addSql("ALTER TABLE attachment ADD in_player BOOLEAN DEFAULT NULL, ADD playtime INT DEFAULT NULL");
		$this->addSql("UPDATE attachment SET in_player = 't'");

		$this->addSql("UPDATE newsletter_subscription SET active = 'f' AND unsubscribed_on = now()");
	}


	public function down(Schema $schema)
	{
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
		$this->addSql('ALTER TABLE attachment DROP in_player, DROP playtime');
	}

}
