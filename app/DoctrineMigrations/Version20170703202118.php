<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170703202118 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE twitch_user (id VARCHAR(64) NOT NULL, twitch_userid VARCHAR(64) NOT NULL, local_userid VARCHAR(64) NOT NULL, display_name VARCHAR(128) NOT NULL, channel VARCHAR(128) NOT NULL, oauth_token VARCHAR(64) NOT NULL, oauth_refresh_token VARCHAR(256) NOT NULL, scope VARCHAR(256) NOT NULL, created BIGINT NOT NULL, last_login BIGINT NOT NULL, INDEX idx_local_user (local_userid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE twitch_channels (id VARCHAR(64) NOT NULL, twitch_user_id INT NOT NULL, twitch_channel_name VARCHAR(128) NOT NULL, added INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE twitch_schedules (id VARCHAR(64) NOT NULL, local_channel_id VARCHAR(64) NOT NULL, twitch_user_id INT NOT NULL, day INT NOT NULL, time_start INT NOT NULL, time_end INT NOT NULL, topic VARCHAR(128) NOT NULL, INDEX IDX_881DB6610D6FBFA (local_channel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE twitch_schedules ADD CONSTRAINT FK_881DB6610D6FBFA FOREIGN KEY (local_channel_id) REFERENCES twitch_channels (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE twitch_schedules DROP FOREIGN KEY FK_881DB6610D6FBFA');
        $this->addSql('DROP TABLE twitch_user');
        $this->addSql('DROP TABLE twitch_channels');
        $this->addSql('DROP TABLE twitch_schedules');
    }
}
