<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190519154642 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE receipt (id INT AUTO_INCREMENT NOT NULL, order_details_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, unit VARCHAR(30) DEFAULT NULL, slug VARCHAR(150) DEFAULT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_description VARCHAR(255) DEFAULT NULL, rating INT DEFAULT NULL, title_photo VARCHAR(255) DEFAULT NULL, status TINYINT(1) DEFAULT NULL, special_proposition TINYINT(1) DEFAULT NULL, special_price DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_5399B6458C0FA77 (order_details_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE receipt_product (receipt_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_C000A1532B5CA896 (receipt_id), INDEX IDX_C000A1534584665A (product_id), PRIMARY KEY(receipt_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE receipt ADD CONSTRAINT FK_5399B6458C0FA77 FOREIGN KEY (order_details_id) REFERENCES order_details (id)');
        $this->addSql('ALTER TABLE receipt_product ADD CONSTRAINT FK_C000A1532B5CA896 FOREIGN KEY (receipt_id) REFERENCES receipt (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE receipt_product ADD CONSTRAINT FK_C000A1534584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE receipt_product DROP FOREIGN KEY FK_C000A1532B5CA896');
        $this->addSql('DROP TABLE receipt');
        $this->addSql('DROP TABLE receipt_product');
    }
}
