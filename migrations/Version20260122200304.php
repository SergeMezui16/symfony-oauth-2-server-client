<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260122200304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE oauth2_client_profile DROP CONSTRAINT fk_9b524e1fe77abe2b');
        $this->addSql('DROP INDEX uniq_9b524e1fe77abe2b');
        $this->addSql('ALTER TABLE oauth2_client_profile ALTER name SET NOT NULL');
        $this->addSql('ALTER TABLE oauth2_client_profile ALTER description TYPE TEXT');
        $this->addSql('ALTER TABLE oauth2_client_profile RENAME COLUMN client_identifier TO client_id');
        $this->addSql('ALTER TABLE oauth2_client_profile ADD CONSTRAINT FK_9B524E1F19EB6921 FOREIGN KEY (client_id) REFERENCES oauth2_client (identifier) NOT DEFERRABLE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9B524E1F19EB6921 ON oauth2_client_profile (client_id)');
        $this->addSql('ALTER TABLE oauth2_user_consent DROP CONSTRAINT fk_c8f05d017e3c61f9');
        $this->addSql('ALTER TABLE oauth2_user_consent DROP CONSTRAINT fk_c8f05d01e77abe2b');
        $this->addSql('DROP INDEX idx_c8f05d01e77abe2b');
        $this->addSql('DROP INDEX idx_c8f05d017e3c61f9');
        $this->addSql('ALTER TABLE oauth2_user_consent ADD ip_address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE oauth2_user_consent ADD user_id UUID NOT NULL');
        $this->addSql('ALTER TABLE oauth2_user_consent DROP owner_id');
        $this->addSql('ALTER TABLE oauth2_user_consent RENAME COLUMN created_at TO created');
        $this->addSql('ALTER TABLE oauth2_user_consent RENAME COLUMN expired_at TO expires');
        $this->addSql('ALTER TABLE oauth2_user_consent RENAME COLUMN client_identifier TO client_id');
        $this->addSql('ALTER TABLE oauth2_user_consent ADD CONSTRAINT FK_C8F05D0119EB6921 FOREIGN KEY (client_id) REFERENCES oauth2_client (identifier) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE oauth2_user_consent ADD CONSTRAINT FK_C8F05D01A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_C8F05D0119EB6921 ON oauth2_user_consent (client_id)');
        $this->addSql('CREATE INDEX IDX_C8F05D01A76ED395 ON oauth2_user_consent (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE oauth2_client_profile DROP CONSTRAINT FK_9B524E1F19EB6921');
        $this->addSql('DROP INDEX UNIQ_9B524E1F19EB6921');
        $this->addSql('ALTER TABLE oauth2_client_profile ALTER name DROP NOT NULL');
        $this->addSql('ALTER TABLE oauth2_client_profile ALTER description TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE oauth2_client_profile RENAME COLUMN client_id TO client_identifier');
        $this->addSql('ALTER TABLE oauth2_client_profile ADD CONSTRAINT fk_9b524e1fe77abe2b FOREIGN KEY (client_identifier) REFERENCES oauth2_client (identifier) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_9b524e1fe77abe2b ON oauth2_client_profile (client_identifier)');
        $this->addSql('ALTER TABLE oauth2_user_consent DROP CONSTRAINT FK_C8F05D0119EB6921');
        $this->addSql('ALTER TABLE oauth2_user_consent DROP CONSTRAINT FK_C8F05D01A76ED395');
        $this->addSql('DROP INDEX IDX_C8F05D0119EB6921');
        $this->addSql('DROP INDEX IDX_C8F05D01A76ED395');
        $this->addSql('ALTER TABLE oauth2_user_consent ADD owner_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE oauth2_user_consent DROP ip_address');
        $this->addSql('ALTER TABLE oauth2_user_consent DROP user_id');
        $this->addSql('ALTER TABLE oauth2_user_consent RENAME COLUMN created TO created_at');
        $this->addSql('ALTER TABLE oauth2_user_consent RENAME COLUMN expires TO expired_at');
        $this->addSql('ALTER TABLE oauth2_user_consent RENAME COLUMN client_id TO client_identifier');
        $this->addSql('ALTER TABLE oauth2_user_consent ADD CONSTRAINT fk_c8f05d017e3c61f9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE oauth2_user_consent ADD CONSTRAINT fk_c8f05d01e77abe2b FOREIGN KEY (client_identifier) REFERENCES oauth2_client (identifier) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_c8f05d01e77abe2b ON oauth2_user_consent (client_identifier)');
        $this->addSql('CREATE INDEX idx_c8f05d017e3c61f9 ON oauth2_user_consent (owner_id)');
    }
}
