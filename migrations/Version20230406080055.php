<?php

declare(strict_types=1);

namespace Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230406080055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Уникальный индекс на right: item_id + user_id';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            create unique index rights_user_id_item_id_uindex on rights (user_id, item_id);
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            drop index rights_user_id_item_id_uindex;
        ');
    }
}
