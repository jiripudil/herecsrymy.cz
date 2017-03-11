<?php

namespace Herecsrymy\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;


class Version20170306164049 extends AbstractMigration
{

	public function up(Schema $schema)
	{
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
		$this->addSql('DROP SEQUENCE newsletter_subscription_id_seq CASCADE');
		$this->addSql('DROP TABLE newsletter_subscription');
		$this->addSql('DROP INDEX idx_3bae0aa77c4fb0f9');
		$this->addSql('ALTER TABLE event DROP canceled_for');
	}


	public function down(Schema $schema)
	{
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

		$this->addSql('CREATE SEQUENCE newsletter_subscription_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
		$this->addSql('CREATE TABLE newsletter_subscription (id INT NOT NULL, email VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, subscribed_on TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, unsubscribed_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, unsubscribe_hash VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
		$this->addSql('CREATE INDEX idx_a82b55ad4b1efc02 ON newsletter_subscription (active)');
		$this->addSql('CREATE INDEX idx_a82b55ade7927c74 ON newsletter_subscription (email)');
		$this->addSql('CREATE INDEX idx_a82b55ad513b2e8b ON newsletter_subscription (unsubscribe_hash)');
		$this->addSql('ALTER TABLE event ADD canceled_for VARCHAR(255) DEFAULT NULL');
		$this->addSql('CREATE INDEX idx_3bae0aa77c4fb0f9 ON event (canceled_for)');
	}

}
