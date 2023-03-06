Инструкция по разворачиванию проекта:

Установить php версии 8.1 или выше
Установить Composer, который используется для установки PHP-пакетов.
В терминале выполнить команду git clone https://github.com/irtq/test_task_owl_agency.git
Установить и развернуть веб-сервер (я использовал OpenServer). Прописать папку домена - папка проекта/public, перезапустить сервер
Выполнить команду composer require symfony/runtime
Установить ORM DOctrine и создать базу данных для проекта, выполнив следующие команды в терминале, находясь в папке проекта - composer require symfony/orm-pack composer require --dev symfony/maker-bundle php bin/console doctrine:database:create --if-not-exists
Выполнить миграцию, чтобы создать в базе данных таблицу согласно сущности FormData php bin/console doctrine:migrations:migrate
