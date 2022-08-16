Тестовое задание для Барс. Литвинцев Михаил.
Установка:
1. Скачать репозиторий: git clone https://github.com/Mikhail-Litvintsev/bars_lite testCase
2. В папке с проектом выполнить: docker compose build && docker compose up -d
3. Скопировать файл src/.env.example в src/.env
4. В контейнере php (docker compose exec php sh) выполнить команду: composer install. Можно снаружи запустить, находясь в папке src (если композер установлен глобально);
5. В контейнере php выполнить: 
   * php artisan key:generate ;
   * php artisan migrate ;
   * php artisan optimize ;
6. При наличии установленного пакета npm, рекомендуется в папке src (снаружи контейнеров) выполнить команду 
    npm install && npm run dev. 
   Если такой возможности нет, то возможно запустить изнутри php контейнера.
7. Eсли у вас защищенные файловые системы (Linux), то выдайте, пожалуйста, разрешения на папку с проектом (как минимум, на src/storage)

Приложение готово к работе. По умолчанию http://localhost

Немного о задании. Требовалось создать CRUD-приложение, позволяющее в удобном для пользователя виде редактировать json файл.
Реализовал возможность создания файла с нуля, импорта из файла или редактирования уже созданной версии. Версии также можно удалять. 
При редактировании номера версии - создается новый экземпляр, когда как, редактирование полей (без изменения версии), обновляет текущую версию. 
Изначально, выводится свернутый список учреждений, по плюсу можно развернуть список подразделений (если они есть). Можно добавлять, редактировать и удалять
как учреждения, так и подразделения. Можно свернуть или развернуть все (кликом по соответствующей надписи). При любом получении файла производится проверка 
его валидности.

Использовал связку Laravel + Vue. Взаимодействие через api. Для целей CRUD использовал PostgreSQl, формат jsonb. Для выгрузки хранилище (storage).

В реальном проекте, я как работник запросил бы больше исходных данных:
* Какая структура у файла?
* Файл всегда один, или несколько? Один / несколько для одного пользователя или для разных?
* Насколько большим предполагается файл? Может ли потребоваться оптимизация скорости (с частичной подгрузкой)? 
* Планируются ли поиск, сортировка..
* Авторизация, шифрование, ..





    
