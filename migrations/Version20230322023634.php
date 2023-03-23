<?php

declare(strict_types=1);

namespace Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230322023634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Добавление поля description для описания и хэштегов';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            alter table items add description text;
        ');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            alter table items drop column description;
        ');

    }
}
