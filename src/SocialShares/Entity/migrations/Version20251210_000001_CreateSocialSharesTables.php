<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251210_000001_CreateSocialSharesTables extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create tables for Social Shares module (Group 3)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE social_shares (
                id INT AUTO_INCREMENT PRIMARY KEY,
                client_id INT NOT NULL,
                quantity INT NOT NULL,
                unit_price DECIMAL(15,2) NOT NULL,
                total_amount DECIMAL(15,2) NOT NULL,
                purchase_date DATETIME NOT NULL,
                INDEX IDX_CLIENT (client_id),
                CONSTRAINT FK_CLIENT FOREIGN KEY (client_id) REFERENCES client(id)
            )
        ');

        $this->addSql('
            CREATE TABLE share_transactions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                client_id INT NOT NULL,
                type VARCHAR(50) NOT NULL,
                shares INT NOT NULL,
                amount DECIMAL(15,2) NOT NULL,
                date DATETIME NOT NULL,
                description VARCHAR(255) DEFAULT NULL,
                INDEX IDX_CLIENT_TRANS (client_id),
                CONSTRAINT FK_CLIENT_TRANS FOREIGN KEY (client_id) REFERENCES client(id)
            )
        ');

        $this->addSql('
            ALTER TABLE client 
            ADD capital DECIMAL(15,2) DEFAULT "0.00" NOT NULL
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE share_transactions');
        $this->addSql('DROP TABLE social_shares');
        $this->addSql('ALTER TABLE client DROP capital');
    }
}
