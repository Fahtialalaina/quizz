<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200605032556 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE answers (id INT AUTO_INCREMENT NOT NULL, questions_id INT NOT NULL, title VARCHAR(255) NOT NULL, is_answer TINYINT(1) NOT NULL, INDEX IDX_50D0C606BCB134CE (questions_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competences (id INT AUTO_INCREMENT NOT NULL, families_id INT NOT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_DB2077CE5DFECCD4 (families_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE families (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, parent_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_995F3FCC67B3B43D (users_id), INDEX IDX_995F3FCC727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE questions (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, competences_id INT NOT NULL, title VARCHAR(255) NOT NULL, attached VARCHAR(255) DEFAULT NULL, texte_complementaire VARCHAR(255) DEFAULT NULL, autre_texte VARCHAR(255) DEFAULT NULL, etat VARCHAR(255) NOT NULL, motif VARCHAR(255) DEFAULT NULL, create_at DATETIME NOT NULL, INDEX IDX_8ADC54D567B3B43D (users_id), INDEX IDX_8ADC54D5A660B158 (competences_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE questions_types (questions_id INT NOT NULL, types_id INT NOT NULL, INDEX IDX_DC48194CBCB134CE (questions_id), INDEX IDX_DC48194C8EB23357 (types_id), PRIMARY KEY(questions_id, types_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE types (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, activation_token VARCHAR(50) DEFAULT NULL, reset_token VARCHAR(50) DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C606BCB134CE FOREIGN KEY (questions_id) REFERENCES questions (id)');
        $this->addSql('ALTER TABLE competences ADD CONSTRAINT FK_DB2077CE5DFECCD4 FOREIGN KEY (families_id) REFERENCES families (id)');
        $this->addSql('ALTER TABLE families ADD CONSTRAINT FK_995F3FCC67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE families ADD CONSTRAINT FK_995F3FCC727ACA70 FOREIGN KEY (parent_id) REFERENCES families (id)');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D567B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D5A660B158 FOREIGN KEY (competences_id) REFERENCES competences (id)');
        $this->addSql('ALTER TABLE questions_types ADD CONSTRAINT FK_DC48194CBCB134CE FOREIGN KEY (questions_id) REFERENCES questions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE questions_types ADD CONSTRAINT FK_DC48194C8EB23357 FOREIGN KEY (types_id) REFERENCES types (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D5A660B158');
        $this->addSql('ALTER TABLE competences DROP FOREIGN KEY FK_DB2077CE5DFECCD4');
        $this->addSql('ALTER TABLE families DROP FOREIGN KEY FK_995F3FCC727ACA70');
        $this->addSql('ALTER TABLE answers DROP FOREIGN KEY FK_50D0C606BCB134CE');
        $this->addSql('ALTER TABLE questions_types DROP FOREIGN KEY FK_DC48194CBCB134CE');
        $this->addSql('ALTER TABLE questions_types DROP FOREIGN KEY FK_DC48194C8EB23357');
        $this->addSql('ALTER TABLE families DROP FOREIGN KEY FK_995F3FCC67B3B43D');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D567B3B43D');
        $this->addSql('DROP TABLE answers');
        $this->addSql('DROP TABLE competences');
        $this->addSql('DROP TABLE families');
        $this->addSql('DROP TABLE questions');
        $this->addSql('DROP TABLE questions_types');
        $this->addSql('DROP TABLE types');
        $this->addSql('DROP TABLE users');
    }
}
