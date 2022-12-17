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

Заходишь внутрь репозитория (```cd base-docker```) и выполняешь ```make init```
## КУДА ЗАЛИВАТЬ КОНТЕНТ

Nginx настроен на чтение из docker-base/api/www. Там сейчас лежит файл deployer-а deploy.php. 

Если у проекта уже есть файл deploy.php, можешь этот удалить и в папку www заливать проект. 

Если проект новый - сначала можешь залить проект в другую новую папку, например, в docker-base/api/project, потом туда закинуть deploy.php, далее www папку удаляем, а project переименовываем в www. Должно работать
