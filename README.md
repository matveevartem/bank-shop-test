# Информация
- Реализован графический интерфейс
  - Реализована интернационализация с возможностью переключения языков в панели навигации
  - Фронт выполнен на шаблонах Yii2.
- Реализованы REST-API ендпоинты.
  - Для API запросов реализованы пагинация и сортировка
  - Приложен файл запросов для Postman (файл **Postman.json** в корне проекта)
- Реализована контейнеризация с возможностью запуска всего приложения одной командой app.sh
- Все настройки для запуска размещены в файле .env
  - Адрес сайта по умолчанию - http://localhost:8000.
- Ниже описана структура проекта, команды для сборки/запуска/остановки приложения
- Описание работы методов классов можно найти в PHPDoc

# Требования
- Unix подобные системы, Linux, MacOS или Windows 10/11 с установленным WSL.
- Bash
- Установленный docker
- Установленный docker-compose

# Структура приложения
```
├── app
│   ├── assets                                      - initial
│   │   └── AppAsset.php
│   ├── commands                                    - initial
│   │   └── HelloController.php
│   ├── config                                      - initial
│   │   ├── __autocomplete.php
│   │   ├── console.php
│   │   ├── db.php
│   │   ├── params.php
│   │   ├── test_db.php
│   │   ├── test.php
│   │   └── web.php                                 - Добавлен роутинг для модулей и зарегестрированны сами модули
│   ├── controllers                                 - initial
│   │   └── SiteController.php
│   ├── mail                                        - initial
│   │   └── layouts
│   │       ├── html.php
│   │       └── text.php
│   ├── messages                                    - Переводы i18n (глобальные)
│   │   ├── en-US                                     * Переводы на английский (не требуется)
│   │   │    └── yii.php                                ** Файл с переводами (пустой)
│   │   └── ru-RU                                     * Переводы на русский
│   │        └── yii.php                                ** Файл с переводами
│   ├── modules                                     - Модули с реализацией пунктов ТЗ 
│   │   │                                             и с web интерфейсами для визуальной проверки их работы
│   │   │
│   │   ├── hosting                                 - Модуль для работы с изображениями.
│   │   │   ├── assets
│   │   │   │   ├── css
│   │   │   │   │   ├── images                      - Файлы изображений для библиотеки jquery-ui
│   │   │   │   │   ├── jquery-ui.css               - Библиотека jquery-ui
│   │   │   │   │   └── style.css                   - Стили модуля
│   │   │   │   └── js
│   │   │   │       ├── jquery-ui.js                - Скрипты библиотеки jquery-ui
│   │   │   │       └── script.js                   - Скрипты модуля
│   │   │   ├── config
│   │   │   │    └── settings.php                   - Файл с настройками модуля
│   │   │   ├── controllers
│   │   │   │   └── DefaultController.php           - Контроллер модуля
│   │   │   ├── messages                            - Переводы i18n (для модуля)
│   │   │   │   ├── en-US                             * Переводы на английский (не требуется)
│   │   │   │   │    └── hosting.php                    ** Файл с переводами (пустой)
│   │   │   │   └── ru-RU                             * Переводы на русский
│   │   │   │        └── hosting.php                    ** Файл с переводами
│   │   │   ├── migrations                          - Миграции модуля
│   │   │   │   └── m240515_111228_create_table_image.php  - Создает таблицу `image`
│   │   │   ├── models
│   │   │   │   ├── Gallary.php                     - Модель для работы с Галереей (форма загрузки, отображение галереи)
│   │   │   │   └── Image.php                       - Модель для работы с изображениями
│   │   │   ├── views
│   │   │   │   └── default
│   │   │   │       └── gallery.php                 - Реализует web интерфейс со следующим вункционалом:
│   │   │   │                                         * Отображает форму для загрузки файлов JPG, PNG, GIF
│   │   │   │                                         * Отображает загруженные изображения с превью, именем файла
│   │   │   │                                           и возможностью скачать ZIP-архив или
│   │   │   │                                           отобразить изображение в полном размере
│   │   │   ├── ModuleAsset.php                     - AssetBundle модуля
│   │   │   └── Module.php                          - Класс модуля
│   │   │
│   │   ├── api                                     - Модуль REST-API 
│   │   │   ├── actions
│   │   │   │    └── IndexAction.php                - action для отображения списка изображений в JSON
│   │   │   ├── config
│   │   │   │    └── settings.php                   - Файл с настройками модуля
│   │   │   ├── controllers
│   │   │   │   └── DefaultController.php           - Контроллер модуля
│   │   │   └── Module.php                          - Класс модуля
│   │   │
│   ├── tests                                       - initial
│   ├── views
│   │   ├── layouts     
│   │   │   └── main.php                            - Изменена панель навигация и другие надписи.
│   │   │                                             Добавлена интернализация (English, Russian)
│   │   └── site
│   │       └── error.php                           - initial
│   ├── web
│   │   ├── assets                                  - initial
│   │   ├── css                                     - initial
│   │   ├── uploads
│   │   │   ├── images                              - Сюда будут загружатся изображения из формы загрузки модуля Gallery.
│   │   │   │                                         Отсюда берутся изображения для просмотра изображения в полном размере
│   │   │   └── preview                             - Отсюда берутся изображения для просмотра превью изображений в галерее
│   │   │
│   │   ├── favicon.ico
│   │   ├── index.php
│   │   ├── index-test.php
│   │   └── robots.txt
│   ├── codeception.yml
│   ├── composer.json
│   ├── composer.lock
│   ├── docker-compose.yml
│   ├── LICENSE.md
│   ├── README.md
│   ├── requirements.php
│   ├── Vagrantfile
│   ├── yii
│   └── yii.bat
├── docker                                          - Папка с файлами для сборки docker образов и файлы конфигураций
│   ├── conf
│   │   ├── nginx                                   - Конфигурационные файля для Nginx
│   │   │   ├── default.conf
│   │   │   └── default.template.conf
│   │   ├── php                                     - Конфигурационные файля для php
│   │   │   ├── php.ini-development
│   │   │   └── php.ini-production
│   │   └── php-fpm                                - Конфигурационные файлы для php-fpm
│   │       └── www.ini
│   ├── php                                         - Файлы для сборки образа PHP-FPM
│   │   └── Dockerfile
├── docker-compose.yml                              - Конфигурационный файл для docker-compose
│
├── .env                                            - Файл с переменными окружения
│                                                     по умолчанию уже настроен для успешного запуска, но
│                                                     при желании можно изменить любые значения
├── .gitignore
├── app.sh                                          - Файл управления приложением.
│                                                     Позволяет собирать образы, запускать и останавливать контейнеры
├── README.md
```

# Установка

### Скачайте приложение
```
$ git clone https://github.com/matveevartem/bank-shop-test.git matveev-bank-shop-test
$ cd matveev-bank-shop-test
```
При желании можете отредактировать файл **.env**
```
$ nano ./.env
```

# Запуск

### Первый запуск
- Linux/MacOS/Unix с docker-compose.yml последней версии
  ```
  $ ./app.sh init
  ```
- Linux/MacOS/Unix с docker-compose.yml конкретной версии  (от 3 до 3.9)
  ```
  $ ./app.sh init 3.5
  ```
- Windows WSL
  ```
  $ ./app.sh init wsl
  ```

Будут собраны и запущены все контейнеры, создана база данных и установленны все зависимости.  
Может занять несколько минут.

### Последующе запуски
- Linux/MacOS/Unix с **docker-compose.yml** последней версии
  ```
  $ ./app.sh start
  ```
- Linux/MacOS/Unix с **docker-compose.yml** конкретной версии  (от 3 до 3.9)
  ```
  $ ./app.sh start 3.5
  ```
- Windows WSL
  ```
  $ ./app.sh start wsl
  ```
### Остановка приложения
- Linux/MacOS/Unix с **docker-compose.yml** последней версии
  ```
  $ ./app.sh stop
  ```
- Linux/MacOS/Unix с **docker-compose.yml** конкретной версии (от 3 до 3.9)
  ```
  $ ./app.sh stop 3.5
  ```
- Windows WSL
  ```
  $ ./app.sh stop wsl
  ```

# Описание API (Результат возвращается в виде массива JSON объектов)

#### Получение списка загруженных сообщений постранично
- По умолчанию 10 объектов на одну страницу
  * первая страница  

    ```GET http://localhost:8000/api```
  * произвольная страница  
    ```GET http://localhost:8000/api?page=2```

- Что бы отключить пагинацию, нужно изменить настройки модуля API, файл **app/modules/api/config/settings.php**  
  Параметру **pageSize** нужно задать значение **0**

#### Сортировка

- по времени загрузки изображения (сначала старые)  
  ```GET http://localhost:8000/api?sort=created_at```

- по времени загрузки изображения (сначала новые)  

  ```GET http://localhost:8000/api?sort=-created_at```

- по оригинальному имени файла (A-Z)  

  ```GET http://localhost:8000/api?sort=original_name```

- по оригинальному имени файла (Z-A)  

  ```GET http://localhost:8000/api?sort=-original_name```

#### Вывод информации об одном изображенн
- ```GET http://localhost:8000/api/1``` - вернет JSON с информацией для изображения с id=1
- ```GET http://localhost:8000/api/5``` - вернет JSON с информацией для изображения с id=5

# Примечание

Постарался везде в основном функционале описать PHPDoc и максимально типизировать переменные, но мог что-то упустить.  
Начал разворачивать swager и swager-ui, но понял, что это займет время, по этому сдаю работу с описанием API в README.md  
и приложенным файлом с запросами для Postman. Это файл Postman.json в корне проекта  
Прошу понять и простить