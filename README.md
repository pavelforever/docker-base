# docker-base
> Если docker будем установливать в lxc контейнере, сначала добавь внешние порты (останови контейнер сначала ```lxc stop КОНТЕЙНЕР```). ЧТОБ БЫСТРЕЕ БЫЛО, МОЖЕШЬ В SUBLIME TEXT ВСТАВИТЬ И ТАМ ЗАМЕНИТЬ `КОНТЕЙНЕР` на свой. Ну или плейбук написать в ansible.

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


## ОСНОВНЫЕ ДЕЙСТВИЯ

Сначала нужно установить docker( https://docs.docker.com/engine/install/ubuntu/#set-up-the-repository) и make (```apt install make```).

Дальше копируешь в рабочкую папку - ```git clone ссылка_на_этот_репозиторий```.

Заходишь внутрь репозитория (```cd base-docker```) и выполняешь ```make init```
