<?php

declare(strict_types=1);

namespace Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230408063711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('alter table rights add path ltree not null;');
        $this->addSql('
            alter table rights
                add constraint rights_items_path_fk
                    foreign key (path) references items (path)
                        on update cascade on delete cascade;
        ');
        $this->addSql('alter table rights drop constraint rights_items_id_fk;');
        $this->addSql('alter table rights drop column item_id;');
    }

    public function down(Schema $schema): void
    {
        //@todo сделать миграцию down
        // this down() migration is auto-generated, please modify it to your needs
    }
}
