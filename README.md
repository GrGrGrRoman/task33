# Продвинутый Backend. Практическая работа 33 #
## Мессенджер ##

Браузерный клиент, позволяющий пользователям оперативно вступать в диалог с зарегистрированными пользователями:

- ООП.
- Защита от SQL-injectin, CSRF и XSS уязвимостей.  
- Система регистраций с помощью пары логин-пароль, в качестве логина выступает email, при регистрации на почту приходит письмо с подтверждением регистрации.
- Пользователь внутри системы характеризуется при помощи email или никнейма на выбор, при желанн отображение email можно скрыть.
- Сделана возможность использование персонального аватара.
- Общение между пользователями производится в окне чата, чат общий, список пользователей общий.
- Обмен сообщениями в реальном времени с помощью WebSocket с применением пакета composer Ratchet.

Для работы приложения необходим MySQL, composer и PHP.

Тип БД в проекте: MySQL. Выполните экспорт БД websocket.sql, настройки доступа в db\Db.php

- В корневом каталоге приложения выполните команду composer update.
- Запустите web-сервер, адрес по умолчанию 127.0.0.1:8000. Это адрес указан в ссылке для подтверждения регистрации в db\Users.php строка 167.
- Выполните php bin\server.php
- Откройте два экземпляра приложения мессенджер, лучше в разных браузерах, чтобы не было проблем с данными в сессиях, зарегистрируйтесь, войдите и попробуйте.

TO DO:
- групповой и приватный чаты
- редактирование и удаление сообщений
- пересылка сообщений
- звуковой и бесшумный режим оповещения при поступлении сообщения
- отправа сообщения с помощью клавиши Enter


