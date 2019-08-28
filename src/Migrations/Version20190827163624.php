<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190827163624 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product ADD percent DOUBLE PRECISION DEFAULT NULL, ADD addition_price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE receipt ADD percent DOUBLE PRECISION DEFAULT NULL, ADD additional_price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE register_with_unique_id register_with_unique_id VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP percent, DROP addition_price');
        $this->addSql('ALTER TABLE receipt DROP percent, DROP additional_price');
        $this->addSql('ALTER TABLE user CHANGE register_with_unique_id register_with_unique_id TINYINT(1) DEFAULT NULL');
    }
}
