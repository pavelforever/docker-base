# docker-base
> Если docker будем установливать в lxc контейнере, сначала сделай их privileged и nest 

  - `lxc config set КОНТЕЙНЕР security.privileged true`

  - `lxc config set КОНТЕЙНЕР security.nesting true`
  
> Если docker будем установливать в lxc контейнере, сначала добавь внешние порты (перед этим останови контейнер ```lxc stop КОНТЕЙНЕР```). ЧТОБ БЫСТРЕЕ ДОБАВЛЯТЬ ПОРТЫ, МОЖЕШЬ В SUBLIME TEXT ВСТАВИТЬ ТЕКСТ НИЖЕ И ТАМ ЗАМЕНИТЬ `КОНТЕЙНЕР` на свой. Ну или плейбук написать в ansible.

80 для nginx НЕ НУЖЕН, Т.К. ПЕРЕНАПРАВЛЕНИЕ БУДЕТ ИДТИ ИЗ КОНТЕЙНЕРА nginx НА ПОРТ 80, ТАМ НАДО ДОБАВИТЬ ХОСТ, И КОНТЕЙНЕР, ЗДЕСЬ НЕ НАДО

  - Идём в nginx ```lxc exec nginx bash```
  - ```vim /etc/nginx/conf.d/autogenerator/myconfig.ini```
  - добавляем контейнер
  - ```php /etc/nginx/conf.d/autogenerator/run.php```
  - ```systemctl restart nginx```
  - добавляем в .hosts хост и ip

8080 для nginx

```lxc config device add КОНТЕЙНЕР ВНЕШНИЙ_ПОРТ-8080-nginx proxy listen=tcp:0.0.0.0:ВНЕШНИЙ_ПОРТ connect=tcp:127.0.0.1:8080```


3306 для mysql

```lxc config device add КОНТЕЙНЕР ВНЕШНИЙ_ПОРТ-3306-mysql proxy listen=tcp:0.0.0.0:ВНЕШНИЙ_ПОРТ connect=tcp:127.0.0.1:3306```


50600 для phpmyadmin (внутри докера он уже оттуда перенаправится на 80)

```lxc config device add КОНТЕЙНЕР ВНЕШНИЙ_ПОРТ-50600-phpmyadmin proxy listen=tcp:0.0.0.0:ВНЕШНИЙ_ПОРТ connect=tcp:127.0.0.1:50600```


22 для ssh

```lxc config device add КОНТЕЙНЕР ВНЕШНИЙ_ПОРТ-22-ssh proxy listen=tcp:0.0.0.0:ВНЕШНИЙ_ПОРТ connect=tcp:127.0.0.1:22```

Чтоб дать доступ по ssh, отредактируй настройки. Иди в ```vim /etc/ssh/sshd_config``` и сделай ```PasswordAuthentication yes``` и ```PermitRootLogin Yes```, перезагрузи ```systemctl restart sshd```.
Можешь поменять пароль рута, если зашёл под ним ```passwd```. Теперь можно заходить под рутом.

## ЕСЛИ ЗАХОЧЕШЬ ПОМЕНЯТЬ ПОРТ ПРИЛОЖЕНИЯ

НЕ МЕНЯЙ (РЕДИРЕКТ В LARAVEL ПЕРЕНАПРАВЛЯЕТ БЕЗ ПОРТА, КАК ИСПРАВИТЬ, ЕЩЁ НЕ НАШЁЛ!!!)

НО ЕСЛИ ВСЁ ЖЕ ЗАХОЧЕШЬ МЕНЯТЬ, ВОТ В КАКИХ МЕСТАХ НАДО МЕНЯТЬ:
  - в устройстве lxc (lxc config device .. add) НАПРИМЕР, LISTEN 5555 CONNECT 80. ЕСЛИ СОЕДИНЯЕМСЯ К 80, ТО НИЖЕ НИЧЕГО МЕНЯТЬ НЕ НАДО, НО ВООБЩЕ ДАЛЬШЕ НАСТРОЙКИ ПОРТОВ ИДУТ В СЛЕД ПОРЯДКЕ
  - в docker-compose.json
  - в конфиг nginx gateway там listen и proxy_pass (в контейнер api, по умолчанию порт не указывается, идёт на 80)
  - в конфиг nginx api (listen + передаём на php-fpm)

## ОСНОВНЫЕ ДЕЙСТВИЯ

Сначала нужно установить docker( https://docs.docker.com/engine/install/ubuntu/#set-up-the-repository) и make (```apt install make```).

Дальше копируешь в рабочкую папку - ```git clone ссылка_на_этот_репозиторий```.

## КУДА ЗАЛИВАТЬ КОНТЕНТ

Nginx настроен на чтение из docker-base-my/api/www/project/current/public. Изменить эту настройку можно в ```~/docker-base-my/api/docker/common/nginx/conf.d/default.conf``` . Например, для dev можно изменить на /app/www/public

Заходишь внутрь репозитория (```cd ~/docker-base-my```) и выполняешь ```make init```

## Если заливаешь laravel, скорее всего понадобится запустить после клонирования проекта composer
заходишь внутрь контейнера ```docker compose run api-php-cli bash```

composer install 
если выдаст ошибки, скорее всего, в composer.json что-то изменилось и он не соответствует composer.lock.

Здесь есть 2 пути:

  - проигнорировать ошибки и установить то, что в composer.lock ```composer install --ignore-platform-reqs```
  - установить всё, что в composer.json ```composer update```

Если версия php стоит не та, что в composer.json, тоже будет ругаться. Надо, чтоб была та.

## Права (permissions) при установке laravel

Поскольку мы использует разные контейнеры в api-php-cli (bullseye) и в api-php-fpm (alpine), возникает проблема с правами. Т.к. нам надо установить на некоторые папки владельца (или группу) www-data, а в alpine и в bullseye это разные id пользователей (в alpine - 82, в bullseye - 33). Когда мы идём в api-php-cli (bullseye) и меняем из консоли на www-data, в контейнере api-php-fpm (alpine) пользователи становятся не www-data, а xfs. Чтоб исправить, мы так же заходим в api-php-cli (bullseye) и проставляем пользователя по id, т.е. 82
Заходим внутрь контейнера ```docker compose run api-php-cli bash```

```chmod -R 775 storage bootstrap/cache```

```chown -R $USER:82 storage```

```chown -R $USER:82 bootstrap/cache```


Подробнее можешь почитать здесь https://gist.github.com/zdenekdrahos/53f16cfe902ff5f820a01b79e8c76a01

## Сейчас в docker используется версия php 8.1.12, но скорее всего, надо будет изменить

Чтоб изменить, например, на 7.4.3, надо это сделать в 2-х файлах

  - docker-base-my/api/docker/development/php-cli/Dockerfile
  - docker-base-my/api/docker/development/php-fpm/Dockerfile

Потом идёшь в корень проекта и делаешь ```make docker-build```, потом ```make restart```

## Немного работы с make и docker
Запустить в первый раз ```make init```

Если что-то изменилось в docker файле и нужно перестроить его ```make docker-build```, потом ```make restart```.

```docker compose run контейнер sh``` - войти внутрь контейнера

```docker compose ps``` - посмотреть список запущенных контейнеров

```docker compose ps -all``` - посмотреть список запущенных контейнеров

Есть там и вспомогательный контейнер api-php-cli, в который можно заходить (```docker compose run api-php-cli bash```) и в нём уже запускать artisan, composer. Также там есть mc bash.
