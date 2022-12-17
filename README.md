# docker-base
Сначала нужно установить docker(https://docs.docker.com/engine/install/ubuntu/#set-up-the-repository) и make (apt install make).
Дальше копируешь в рабочкую папку - git clone ссылка_на_этот_репозиторий.
Заходишь внутрь репозитория (cd base-docker) и выполняешь make
Если этот docker установлен в lxc контейнере, сначала пробровь порты
8080
lxc config device add proc ВНЕШНИЙ_ПОРТ-8080-nginx proxy listen=tcp:0.0.0.0:ВНЕШНИЙ_ПОРТ connect=tcp:127.0.0.1:8080
80
lxc config device add proc ВНЕШНИЙ_ПОРТ-80-nginx proxy listen=tcp:0.0.0.0:ВНЕШНИЙ_ПОРТ connect=tcp:127.0.0.1:80
3306
lxc config device add proc ВНЕШНИЙ_ПОРТ-3306-mysql proxy listen=tcp:0.0.0.0:ВНЕШНИЙ_ПОРТ connect=tcp:127.0.0.1:3306
50600 для phpmyadmin (внутри докера он уже оттуда перенаправится на 80)
lxc config device add proc ВНЕШНИЙ_ПОРТ-50600-phpmyadmin proxy listen=tcp:0.0.0.0:ВНЕШНИЙ_ПОРТ connect=tcp:127.0.0.1:50600
