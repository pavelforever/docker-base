# docker-base
> Если docker будем установливать в lxc контейнере, сначала добавь внешние порты (перед этим останови контейнер ```lxc stop КОНТЕЙНЕР```). ЧТОБ БЫСТРЕЕ ДОБАВЛЯТЬ ПОРТЫ, МОЖЕШЬ В SUBLIME TEXT ВСТАВИТЬ ТЕКСТ НИЖЕ И ТАМ ЗАМЕНИТЬ `КОНТЕЙНЕР` на свой. Ну или плейбук написать в ansible.

80 для nginx

```lxc config device add КОНТЕЙНЕР ВНЕШНИЙ_ПОРТ-80-nginx proxy listen=tcp:0.0.0.0:ВНЕШНИЙ_ПОРТ connect=tcp:127.0.0.1:80```


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



## ОСНОВНЫЕ ДЕЙСТВИЯ

Сначала нужно установить docker( https://docs.docker.com/engine/install/ubuntu/#set-up-the-repository) и make (```apt install make```).

Дальше копируешь в рабочкую папку - ```git clone ссылка_на_этот_репозиторий```.

Заходишь внутрь репозитория (```cd docker-base-my```) и выполняешь ```make init```
## КУДА ЗАЛИВАТЬ КОНТЕНТ

Nginx настроен на чтение из docker-base-my/api/www/project/current/public. Изменить эту настройку можно в ```~/docker-base-my/api/docker/common/nginx/conf.d/default.conf```

Если что-то надо изменить, идёшь в ~/docker-base-my/

## Если заливаешь laravel, скорее всего понадобится запустить после клонирования проекта composer

composer install --ignore-platform-reqs

## Сейчас в docker используется версия php 8.1.12, но скорее всего, надо будет изменить

Чтоб изменить, например, на 7.4.33, надо это сделать в 2-х файлах

  - docker-base-my/api/docker/development/php-cli/Dockerfile
  - docker-base-my/api/docker/development/php-fpm/Dockerfile

Причём меняешь просто цифры. Оставь постфиксы.

## Немного работы с make и docker
Запустить в первый раз ```make init```

Если что-то изменилось в docker файле и нужно перестроить его ```make up``` - перестроится только тот докер контейнер, в котором были изменения.

Если где-то изменились конфиги, нужно уже указать, какой файл надо перестроить ```docker compose build КОНТЕЙНЕР``` если не сработало ```make restart```

```docker compose run контейнер sh``` - войти внутрь контейнера

```docker compose ps``` - посмотреть список запущенных контейнеров

```docker compose ps -all``` - посмотреть список запущенных контейнеров

Есть там и вспомогательный контейнер api-php-cli, в который можно заходить (```docker compose run api-php-cli bash```) и в нём уже запускать artisan, composer. Также там есть mc bash.
