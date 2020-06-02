<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190907131425 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE receipt_receipt (receipt_source INT NOT NULL, receipt_target INT NOT NULL, INDEX IDX_40D313BB79B7D76B (receipt_source), INDEX IDX_40D313BB605287E4 (receipt_target), PRIMARY KEY(receipt_source, receipt_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE receipt_receipt ADD CONSTRAINT FK_40D313BB79B7D76B FOREIGN KEY (receipt_source) REFERENCES receipt (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE receipt_receipt ADD CONSTRAINT FK_40D313BB605287E4 FOREIGN KEY (receipt_target) REFERENCES receipt (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE receipt_receipt');
    }
}
