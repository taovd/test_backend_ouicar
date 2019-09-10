<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190801135109 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, mileage_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_773DE69D14DB6A4E (mileage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car_price (id INT AUTO_INCREMENT NOT NULL, car_day_id INT NOT NULL, car_id INT NOT NULL, value DOUBLE PRECISION DEFAULT NULL, INDEX IDX_1563A70E11AAADD4 (car_day_id), INDEX IDX_1563A70EC3C6F69F (car_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car_day (id INT AUTO_INCREMENT NOT NULL, min_day INT NOT NULL, max_day INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D14DB6A4E FOREIGN KEY (mileage_id) REFERENCES mileage (id)');
        $this->addSql('ALTER TABLE car_price ADD CONSTRAINT FK_1563A70E11AAADD4 FOREIGN KEY (car_day_id) REFERENCES car_day (id)');
        $this->addSql('ALTER TABLE car_price ADD CONSTRAINT FK_1563A70EC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE car_price DROP FOREIGN KEY FK_1563A70EC3C6F69F');
        $this->addSql('ALTER TABLE car_price DROP FOREIGN KEY FK_1563A70E11AAADD4');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE car_price');
        $this->addSql('DROP TABLE car_day');
    }
}
