<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190717192442 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE order_info (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, total_price INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, order_date DATETIME DEFAULT NULL, order_time DATETIME DEFAULT NULL, status TINYINT(1) DEFAULT NULL, INDEX IDX_86780B40A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_info ADD CONSTRAINT FK_86780B40A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE order_details ADD order_info_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C1ABF168B3 FOREIGN KEY (order_info_id) REFERENCES order_info (id)');
        $this->addSql('CREATE INDEX IDX_845CA2C1ABF168B3 ON order_details (order_info_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C1ABF168B3');
        $this->addSql('DROP TABLE order_info');
        $this->addSql('DROP INDEX IDX_845CA2C1ABF168B3 ON order_details');
        $this->addSql('ALTER TABLE order_details DROP order_info_id');
    }
}
