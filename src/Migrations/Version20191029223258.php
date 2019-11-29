<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191029223258 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
//        $data = ['roles' => json_encode(['ROLE_DEVELOPER']),'pass' => '$2y$13$oxN2pENMR/j17e2bYz5dVeE1lFOiaRoUy1EIUAs49q.iujL2CVKv.','dat' => (new \DateTime())->format('d/m/y H:i:s'),'up' => (new \DateTime())->format('d/m/y H:i:s')];
//        $this->addSql("INSERT INTO user (email, roles, password, name, surname, address, phone, bonuses, unique_id, created_at, updated_at) VALUES('litkovskiy97@gmail.com',:roles,:pass,'Andrew','Litkovskiy','Not','09545',2000,'000',:dat,:up)", $data);
//        $data2 = ['roles' => json_encode(['ROLE_DEVELOPER']), 'pass' => '$2y$13$Brj2s1EtGRFWAG.r3dhcUeojq/lxcDu8PVzLhu/hoTSmgTViKVFmi','dat' => (new \DateTime())->format('d/m/y H:i:s'),'up' => (new \DateTime())->format('d/m/y H:i:s')];
//        $this->addSql("INSERT INTO user (email, roles, password, name, surname, address, phone, bonuses, unique_id, created_at, updated_at) VALUES('vetal119@gmail.com',:roles,:pass,'Vitaliy','Gopkalo','Not','09545',2000,'00',:dat,:up)", $data2);
//        $data3 = ['roles' => json_encode(['ROLE_DEVELOPER']),'pass' => '$2y$13$nqnapA77wSzxkevEpkPi0emX4rI9dBYN2w7KXkvL9ou9rKhU2cJeK','dat' => (new \DateTime())->format('d/m/y H:i:s'),'up' => (new \DateTime())->format('d/m/y H:i:s')];
//        $this->addSql("INSERT INTO user (email, roles, password, name, surname, address, phone, bonuses, unique_id, created_at, updated_at) VALUES('lipinskieraki@gmail.com',:roles,:pass,'Саня','Липинский','Сергея Данченка 12','0636126282','1',2000,:dat,:up)", $data3);
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
