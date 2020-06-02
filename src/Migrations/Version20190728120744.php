<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190728120744 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE receipt DROP FOREIGN KEY FK_5399B6458C0FA77');
        $this->addSql('DROP TABLE order_details');
        $this->addSql('DROP INDEX UNIQ_5399B6458C0FA77 ON receipt');
        $this->addSql('ALTER TABLE receipt DROP order_details_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE order_details (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, order_info_id INT DEFAULT NULL, quantity INT DEFAULT NULL, UNIQUE INDEX UNIQ_845CA2C14584665A (product_id), INDEX IDX_845CA2C1ABF168B3 (order_info_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C14584665A FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C1ABF168B3 FOREIGN KEY (order_info_id) REFERENCES order_info (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE receipt ADD order_details_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE receipt ADD CONSTRAINT FK_5399B6458C0FA77 FOREIGN KEY (order_details_id) REFERENCES order_details (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5399B6458C0FA77 ON receipt (order_details_id)');
    }
}
