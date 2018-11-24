<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180406122955 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE loan (id INT AUTO_INCREMENT NOT NULL, loaner_id INT DEFAULT NULL, product_id INT DEFAULT NULL, date_start DATETIME NOT NULL, date_end DATETIME DEFAULT NULL, status VARCHAR(20) NOT NULL, INDEX IDX_C5D30D03434E717A (loaner_id), UNIQUE INDEX UNIQ_C5D30D034584665A (product_id), INDEX status_idx (status), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D03434E717A FOREIGN KEY (loaner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D034584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE loan');
    }
}
