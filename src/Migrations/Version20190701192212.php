<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190701192212 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE special_proposition ADD receipt_id INT DEFAULT NULL, ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE special_proposition ADD CONSTRAINT FK_C4810FB2B5CA896 FOREIGN KEY (receipt_id) REFERENCES receipt (id)');
        $this->addSql('ALTER TABLE special_proposition ADD CONSTRAINT FK_C4810FB4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_C4810FB2B5CA896 ON special_proposition (receipt_id)');
        $this->addSql('CREATE INDEX IDX_C4810FB4584665A ON special_proposition (product_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE special_proposition DROP FOREIGN KEY FK_C4810FB2B5CA896');
        $this->addSql('ALTER TABLE special_proposition DROP FOREIGN KEY FK_C4810FB4584665A');
        $this->addSql('DROP INDEX IDX_C4810FB2B5CA896 ON special_proposition');
        $this->addSql('DROP INDEX IDX_C4810FB4584665A ON special_proposition');
        $this->addSql('ALTER TABLE special_proposition DROP receipt_id, DROP product_id');
    }
}
