<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190715171103 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
//        $data2 = ['roles' => json_encode(['ROLE_DEVELOPER']), 'pass' => '$2y$13$Brj2s1EtGRFWAG.r3dhcUeojq/lxcDu8PVzLhu/hoTSmgTViKVFmi','dat' => (new \DateTime())->format('d/m/y H:i:s'),'up' => (new \DateTime())->format('d/m/y H:i:s')];
//        $this->addSql("INSERT INTO user (email, roles, password, name, surname, address, phone, bonuses,created_at,updated_at) VALUES('vetal11@gmail.com',:roles,:pass,'Vitaliy','Gopkalo','Not','09545',2000,:dat,:up)", $data2);
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
