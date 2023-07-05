<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230704115036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE purchase_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tax_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE purchase (id INT NOT NULL, product_id INT NOT NULL, coupon_id INT DEFAULT NULL, tax_number VARCHAR(255) NOT NULL, payment_processor VARCHAR(255) NOT NULL, cost INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6117D13B4584665A ON purchase (product_id)');
        $this->addSql('CREATE INDEX IDX_6117D13B66C5951B ON purchase (coupon_id)');
        $this->addSql('COMMENT ON COLUMN purchase.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE tax (id INT NOT NULL, country_iso VARCHAR(100) NOT NULL, format VARCHAR(255) NOT NULL, percent INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B66C5951B FOREIGN KEY (coupon_id) REFERENCES coupon (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE purchase_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tax_id_seq CASCADE');
        $this->addSql('ALTER TABLE purchase DROP CONSTRAINT FK_6117D13B4584665A');
        $this->addSql('ALTER TABLE purchase DROP CONSTRAINT FK_6117D13B66C5951B');
        $this->addSql('DROP TABLE purchase');
        $this->addSql('DROP TABLE tax');
    }
}
