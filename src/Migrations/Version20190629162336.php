<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190629162336 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_product (product_source INT NOT NULL, product_target INT NOT NULL, INDEX IDX_2931F1D3DF63ED7 (product_source), INDEX IDX_2931F1D24136E58 (product_target), PRIMARY KEY(product_source, product_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_receipt_sales (product_id INT NOT NULL, receipt_id INT NOT NULL, INDEX IDX_55F4D8864584665A (product_id), INDEX IDX_55F4D8862B5CA896 (receipt_id), PRIMARY KEY(product_id, receipt_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_product ADD CONSTRAINT FK_2931F1D3DF63ED7 FOREIGN KEY (product_source) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_product ADD CONSTRAINT FK_2931F1D24136E58 FOREIGN KEY (product_target) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_receipt_sales ADD CONSTRAINT FK_55F4D8864584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_receipt_sales ADD CONSTRAINT FK_55F4D8862B5CA896 FOREIGN KEY (receipt_id) REFERENCES receipt (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE product_product');
        $this->addSql('DROP TABLE product_receipt_sales');
    }
}
