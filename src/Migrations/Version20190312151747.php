<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190312151747 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, place VARCHAR(100) NOT NULL, date DATE NOT NULL, mailorga VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, nom VARCHAR(100) NOT NULL, mail VARCHAR(100) NOT NULL, phone VARCHAR(15) DEFAULT NULL, departement VARCHAR(50) NOT NULL, ville VARCHAR(100) NOT NULL, lieu VARCHAR(100) DEFAULT NULL, conducteur TINYINT(1) DEFAULT NULL, passager TINYINT(1) DEFAULT NULL, INDEX IDX_D79F6B1171F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scheduled_command (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, command VARCHAR(100) NOT NULL, arguments VARCHAR(250) DEFAULT NULL, cron_expression VARCHAR(100) DEFAULT NULL, last_execution DATETIME NOT NULL, last_return_code INT DEFAULT NULL, log_file VARCHAR(100) DEFAULT NULL, priority INT NOT NULL, execute_immediately TINYINT(1) NOT NULL, disabled TINYINT(1) NOT NULL, locked TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B1171F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B1171F7E88B');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE scheduled_command');
    }
}
