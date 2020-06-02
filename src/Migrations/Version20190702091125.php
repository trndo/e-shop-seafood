<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190702091125 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE special_proposition ADD gift_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE special_proposition ADD CONSTRAINT FK_C4810FB97A95A83 FOREIGN KEY (gift_id) REFERENCES product (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C4810FB97A95A83 ON special_proposition (gift_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE special_proposition DROP FOREIGN KEY FK_C4810FB97A95A83');
        $this->addSql('DROP INDEX UNIQ_C4810FB97A95A83 ON special_proposition');
        $this->addSql('ALTER TABLE special_proposition DROP gift_id');
    }
}
