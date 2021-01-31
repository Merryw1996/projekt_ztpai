<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210123113918 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE koszyk_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE koszyk (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE koszyk_product (koszyk_id INT NOT NULL, product_id INT NOT NULL, PRIMARY KEY(koszyk_id, product_id))');
        $this->addSql('CREATE INDEX IDX_62CE1089C728B34F ON koszyk_product (koszyk_id)');
        $this->addSql('CREATE INDEX IDX_62CE10894584665A ON koszyk_product (product_id)');
        $this->addSql('CREATE TABLE product (id INT NOT NULL, pid VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, kid_id INT DEFAULT NULL, uid VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6496A973770 ON "user" (kid_id)');
        $this->addSql('ALTER TABLE koszyk_product ADD CONSTRAINT FK_62CE1089C728B34F FOREIGN KEY (koszyk_id) REFERENCES koszyk (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE koszyk_product ADD CONSTRAINT FK_62CE10894584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6496A973770 FOREIGN KEY (kid_id) REFERENCES koszyk (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE koszyk_product DROP CONSTRAINT FK_62CE1089C728B34F');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6496A973770');
        $this->addSql('ALTER TABLE koszyk_product DROP CONSTRAINT FK_62CE10894584665A');
        $this->addSql('DROP SEQUENCE koszyk_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE koszyk');
        $this->addSql('DROP TABLE koszyk_product');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE "user"');
    }
}
