<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190909111627 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE receipt_product_sales (receipt_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_B74BC6E72B5CA896 (receipt_id), INDEX IDX_B74BC6E74584665A (product_id), PRIMARY KEY(receipt_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE receipt_product_sales ADD CONSTRAINT FK_B74BC6E72B5CA896 FOREIGN KEY (receipt_id) REFERENCES receipt (id)');
        $this->addSql('ALTER TABLE receipt_product_sales ADD CONSTRAINT FK_B74BC6E74584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE receipt_product_sales');
    }
}
