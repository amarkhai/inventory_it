<?php

declare(strict_types=1);

namespace Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230327150757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Функция + триггер для обновления поля path во всех 
        дочерних узлах при изменении path коревого узла';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE OR REPLACE FUNCTION update_children_items_path() RETURNS TRIGGER AS $update_children_items_path$
                BEGIN
                    IF (TG_OP = \'UPDATE\' AND NEW.path != OLD.path) THEN
                        UPDATE items
                        SET path = NEW.path || subpath(path, nlevel(OLD.path))
                        WHERE path <@ OLD.path;
                    END IF;
                    RETURN NULL;
                END;
            $update_children_items_path$ LANGUAGE plpgsql;
        ');

        $this->addSql('
            CREATE TRIGGER tr_update_children_items_path
                AFTER UPDATE OF path ON items
                FOR EACH ROW
            EXECUTE FUNCTION update_children_items_path();
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('drop trigger tr_update_children_items_path on items;');
        $this->addSql('drop function update_children_items_path();');
    }
}
