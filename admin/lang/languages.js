var languages = [
    {code: 'en', name: 'English'},
    {code: 'ru', name: 'Русский'},
    //{code: 'de', name: 'Deutsch'}
]

var langContent = {
    'Go to the site': {
        en: 'Go to the site',
        ru: 'Перейти на сайт',
        de: 'Gehe zur Website'
    },
    'Project management': {
        en: 'Project management',
        ru: 'Управление проектами',
        de: 'Projektmanagement'
    },
    'Back': {
        ru: 'Назад'
    },
    'Next': {
        ru: 'Вперед'
    },
    'Up': {
        ru: 'Вверх'
    },
    'Down': {
        ru: 'Вниз'
    },
    'Exit': {
        ru: 'Выход',
    },
    'Add': {
        ru: 'Добавить',
    },
    'Delete': {
        ru: 'Удалить'
    },
    'Cancel': {
        ru: 'Отмена'
    },
    'Close': {
        ru: 'Закрыть',
    },
    'Total': {
        ru: 'Всего'
    },
    'Select': {
        ru: 'Выбрать'
    },
    'Structure': {
        ru: 'Структура',
    },
    'Pages': {
        ru: 'Страницы',
    },
    'Page': {
        ru: 'Страница'
    },
    'Name': {
        ru: 'Наименование'
    },
    'Link': {
        ru: 'Ссылка'
    },
    'Find': {
        ru: 'Найти'
    },
    'Update': {
        ru: 'Обновить'
    },
    'Sections': {
        ru: 'Разделы',
    },
    'Section': {
        ru: 'Раздел'
    },
    'Subsections': {
        ru: 'Подразделы'
    },
    'Users': {
        ru: 'Пользователи',
    },
    'Accounts': {
        ru: 'Аккаунты'
    },
    'Social Accounts': {
        ru: 'Социальные аккаунты'
    },
    'Default language': {
        ru: 'Язык по умолчанию'
    },
    'Are you sure you want to delete this language?': {
        ru: 'Вы точно хотите удалить этот язык?'
    },
    'The language was successfully deleted!': {
        ru: 'Выбранный язык успешно удален!'
    },
    'Language Name': {
        ru: 'Наименование языка'
    },
    'Social networks': {
        ru: 'Социальные сети'
    },
    'Edit menu': {
        ru: 'Изменить меню'
    },
    'Edit file': {
        ru: 'Редактировать файл'
    },
    'New folder': {
        ru: 'Новая папка'
    },
    'Rename': {
        ru: 'Переименовать'
    },
    'Username': {
        ru: 'Имя пользователя'
    },
    'Settings': {
        ru: 'Настройки',
    },
    'Authorization required': {
        ru: 'Требуется авторизация',
    },
    'Home': {
        ru: 'Домой'
    },
    'Logs': {
        ru: 'Логи'
    },
    'Full description': {
        ru: 'Полное описание'
    },
    'Enabled': {
        ru: 'Включено'
    },
    'Triggers': {
        ru: 'Триггеры'
    },
    'Insert template': {
        ru: 'Вставить шаблон'
    },
    'Insert file': {
        ru: 'Вставить файл'
    },
    'Select menu': {
        ru: 'Выбрать меню'
    },
    'Insert menu': {
        ru: 'Вставить меню'
    },
    'Mark the triggers': {
        ru: 'Отметьте триггеры'
    },
    'The notification template is saved!': {
        ru: 'Шаблон уведомления сохранен!'
    },
    'The trigger is saved!': {
        ru: 'Триггер сохранен!'
    },
    'Invalid or inactive login or password!': {
        ru: 'Неправильный или неактивный логин или пароль!',
    },
    'Recipient': {
        ru: 'Получатель'
    },
    'Message': {
        ru: 'Сообщение'
    },
    'Subject': {
        ru: 'Тема'
    },
    'Appointment': {
        ru: 'Назначение'
    },
    'Status': {
        ru: 'Статус'
    },
    'Mark the notices': {
        ru: 'Отметить уведомления'
    },
    'Service information': {
        ru: 'Служебная информация'
    },
    'Page title': {
        ru: 'Заголовок страницы'
    },
    'Notifications': {
        ru: 'Уведомления'
    },
    'New notification': {
        ru: 'Новое уведомление'
    },
    'Edit notification': {
        ru: 'Редактирование уведомления'
    },
    'Notification Log': {
        ru: 'Лог уведомлений'
    },
    'New trigger': {
        ru: 'Новый триггер'
    },
    'Edit trigger': {
        ru: 'Редактировать триггер'
    },
    'User name': {
        ru: 'Логин',
    },
    'Password': {
        ru: 'Пароль',
    },
    'Remember me': {
        ru: 'Запомнить меня',
    },
    'Continue': {
        ru: 'Продолжить',
    },
    'Create a new project': {
        ru: 'Создать новый проект',
    },
    'Project Name': {
        ru: 'Наименование проекта'
    },
    'Default SMS Sender': {
        ru: 'Отправитель СМС по умолчанию'
    },
    'Sender': {
        ru: 'Отправитель'
    },
    'robots.txt file': {
        ru: 'Файл robots.txt'
    },
    'Folder Name': {
        ru: 'Название папки'
    },
    'Domain Name': {
        ru: 'Доменное имя'
    },
    'Rores': {
        ru: 'Роли'
    },
    'Phones for SMS informing': {
        ru: 'Телефоны для СМС информирования'
    },
    'Administrator E-mail': {
        ru: 'E-mail администратора'
    },
    'Create': {
        ru: 'Создать'
    },
    'Save': {
        ru: 'Сохранить'
    },
    'The project has been added!': {
        ru: 'Проект добавлен'
    },
    'Object': {
        ru: 'Объект'
    },
    'Full access': {
        ru: 'Полный доступ'
    },
    'Read': {
        ru: 'Чтение'
    },
    'Editing': {
        ru: 'Редактирование'
    },
    'Deleted': {
        ru: 'Удаление'
    },
    'Added': {
        ru: 'Добавление'
    },
    'Editing permissions': {
        ru: 'Редактирование прав'
    },
    'Access rights reserved!': {
        ru: 'Права доступа сохранены!'
    },
    'Page not found': {
        ru: 'Страница не найдена'
    },
    'Module Editor': {
        ru: 'Редактор модуля'
    },
    'Alias': {
        ru: 'Псевдоним'
    },
    'Module alias': {
        ru: 'Псевдоним модуля'
    },
    'Module Description50': {
        en: 'Module Description (50 char.)',
        ru: 'Описание модуля (50 симв.)',
    },
    'mess.check string lat': {
        en: 'The text can contain the Latin alphabet, numbers and the "-"',
        ru: 'Текст может содержать латинский алфавит, цифры и знак "-" ',
    },
    'mess.check string length2': {
        en: 'The number of characters must be more than 2!',
        ru: 'Число символов должно быть более 2!'
    },
    'mess.check string lat point': {
        en: 'The text should contain only the Latin alphabet and delimiter "."',
        ru: 'Текст должен содержать только латинский алфавит и разделитель "."'
    },
    'mess.check string length3': {
        en: 'The number of characters must be more than 3!',
        ru: 'Число символов должно быть более 3!'
    },
    'Template Editor': {
        ru: 'Редактор шаблона'
    },
    'Template Name': {
        ru: 'Имя шаблона'
    },
    'Create a new page with this name': {
        ru: 'Создать новую страницу с этим именем'
    },
    'Select a link': {
        ru: 'Выбрать ссылку'
    },
    'Delete a section': {
        ru: 'Удаление раздела'
    },
    'Templates': {
        ru: 'Шаблоны'
    },
    'Value': {
        ru: 'Значение'
    },
    'Are you sure you want to delete this section?': {
        ru: 'Вы точно хотите удалить этот раздел ?'
    },
    'Editing a record': {
        ru: 'Редактирование записи'
    },
    'Description': {
        ru: 'Описание'
    },
    'Content': {
        ru: 'Содержание'
    },
    'Module source code': {
        ru: 'Исходный код модуля'
    },
    'Source code': {
        ru: 'Исходный код'
    },
    'Code': {
        ru: 'Код'
    },
    'Images': {
        ru: 'Изображения'
    },
    'Picture file': {
        ru: 'Файл изображения'
    },
    'Variable': {
        ru: 'Переменная'
    },
    'Variable name': {
        ru: 'Имя переменной'
    },
    'Variables': {
        ru: 'Переменные'
    },
    'Title': {
        ru: 'Заголовок'
    },
    'Template': {
        ru: 'Шаблон'
    },
    'URL link': {
        ru: 'URL ссылка'
    },
    'Language of the page': {
        ru: 'Язык страницы'
    },
    'Not specified': {
        ru: 'Не указан'
    },
    'Access': {
        ru: 'Доступ'
    },
    'Access rights': {
        ru: 'Права доступа'
    },
    'Level': {
        ru: 'Уровень'
    },
    'МЕТА-title': {
        ru: 'МЕТА-заголовок'
    },
    'Keywords': {
        ru: 'Ключевые слова'
    },
    'META-description': {
        ru: 'META-описание'
    },
    'Success!': {
        ru: 'Успех!'
    },
    'Changes saved!': {
        ru: 'Изменения сохранены!'
    },
    'Error!': {
        ru: 'Ошибка!'
    },
    'Warning': {
        ru: 'Предупреждение'
    },
    'Can not load template list!': {
        ru: 'Не могу загрузить список шаблонов!'
    },
    'Can not load language list!': {
        ru: 'Не могу загрузить список языков!'
    },
    'Multi-page editing': {
        ru: 'Мультиредактирование страниц'
    },
    'Are you sure you want to delete this page?': {
        ru: 'Вы точно хотите удалить эту страницу?'
    },
    'Functional': {
        ru: 'Функционал'
    },
    'Records':{
        ru: 'Записей'
    },
    'Record`s': {
        en: 'Records',
        ru: 'Записи'
    },
    'More sections': {
        ru: 'Еще разделы'
    },
    'Multi-editing categories': {
        ru: 'Мультиредактирование категорий'
    },
    'Category added!': {
        ru: 'Категория добавлена!'
    },
    'Category editing': {
        ru: 'Редактирование категории'
    },
    'Can not load the list of modules!': {
        ru: 'Не могу загрузить список модулей!'
    },
    'Successfully deleted!': {
        ru: 'Успешно удалено!'
    },
    'Allocated': {
        ru: 'Выделено'
    },
    'Rename folder': {
        ru: 'Переименовать папку'
    },
    'Rename file': {
        ru: 'Переименовать файл'
    },
    'Not supported by the editor!': {
        ru: 'Не поддерживается редактором!'
    },
    'Create new file': {
          ru: 'Создать новый файл'
    },
    'Add new menu': {
        ru: 'Добавить новое меню'
    },
    'Link to section': {
        ru: 'Связать с разделом'
    },
    'Select module template': {
        ru: 'Шаблон модуля',
        en: 'Module Template'
    },
    'Basic information': {
        ru: 'Основная информация'
    },
    'List of pages': {
        ru: 'Список страниц'
    },
    'Manager': {
        ru: 'Менеджер'
    },
    'Person name': {
        ru: 'Имя заявителя'
    },
    'Insert': {
        ru: 'Вставить'
    },
    'Links': {
        ru: 'Ссылки'
    },
    'Options': {
        ru: 'Параметры'
    },
    'Request': {
        ru: 'Запрос'
    },
    'Commentary': {
        ru: 'Коментарий'
    },
    'Add new page': {
        ru: 'Добавить новую страницу'
    },
    'Page name': {
        ru: 'Наименование страницы'
    },
    'Delete section': {
        ru: 'Удалить раздел'
    },
    'Parent': {
        ru: 'Родитель'
    },
    'Parent page': {
        ru: 'Родительская страница'
    },
    'Page code': {
        ru: 'Код страницы'
    },
    'Editor': {
        ru: 'Редактор'
    },
    'Similar': {
        ru: 'Похожие'
    },
    'Requests': {
        ru: 'Заявки'
    },
    'Template for item': {
        ru: 'Шаблон страницы записей'
    },
    'Template for category': {
        ru: 'Шаблон для категории'
    },
    'Display on site': {
        ru: 'Отображать на сайте'
    },
    'Show all': {
        ru: 'Показать все'
    },
    'Change name': {
        ru: 'Изменить наименование'
    },
    'Short description': {
        ru: 'Краткое описание'
    },
    'is Main': {
        ru: 'Главная'
    },
    'Full text': {
        ru: 'Полный текст'
    },
    'Category saved!': {
        ru: 'Категория сохранена!'
    },
    'Index': {
        ru: 'Порядок'
    },
    'Showing': {
        ru: 'Отобразить'
    },
    'Add folder': {
        ru: 'Добавить папку'
    },
    'Edit folder': {
        ru: 'Изменить имя папки'
    },
    'Delete folder': {
        ru: 'Удалить папку'
    },
    'Add template': {
        ru: 'Добавить шаблон'
    },
    'Delete template': {
        ru: 'Удалить шаблон'
    },
    'Modules': {
        ru: 'Модули'
    },
    'Link template to record pages': {
        ru: 'Шаблон ссылки на страницы записей'
    },
    'Category Link Template': {
        ru: 'Шаблон ссылки категорий'
    },
    'Add to search': {
        ru: 'Добавить в поиск'
    },
    'The text should contain the Latin alphabet, numbers and a "-"':{
        ru: 'Текст должен содержать латинский алфавит, цивры и знак "-"'
    },
    'Are you sure you want to delete this section?': {
        ru: 'Вы точно хотите удалить этот раздел?'
    },
    'The settings site is saved!': {
        ru: 'Настройки сайта сохранены!'
    },
    'Choose a template': {
        ru: 'Выберите шаблон'
    },
    'Related': {
        ru: 'Связать'
    },
    'Format code': {
        ru: 'Отформатировать код'
    },
    'The options is saved!Choose a template': {
        ru: 'Параметры сохранены!'
    },
    'page': {
        ru: 'стр'
    },
    'from': {
        ru: 'из'
    },
    'From date': {
        ru: 'От даты'
    },
    'To date': {
        ru: 'До даты'
    },
    'Type': {
        ru: 'Тип'
    },
    'Parametrs': {
        ru: 'Параметры'
    },
    'Contacts': {
        ru: 'Контакты'
    },
    'Contact': {
        ru: 'Контакт'
    },
    'Invited': {
        ru: 'Приглашенные'
    },
    'Inv.': {
        ru: 'Пригл.'
    },
    'Editing a contact': {
        ru: 'Редактирование контакта'
    },
    'Full name': {
        ru: 'ФИО'
    },
    'Date': {
        ru: 'Дата'
    },
    'Date of Birth': {
        ru: 'Дата рождения'
    },
    'Switch': {
        ru: 'Переключатель'
    },
    'Sex': {
        ru: 'Пол'
    },
    'Man': {
        ru: 'Мужчина'
    },
    'Woman': {
        ru: 'Женщина'
    },
    'Phone': {
        ru: 'Телефон'
    },
    'Recommendation': {
        ru: 'Рекомендатель'
    },
    'Additional Information': {
        ru: 'Дополнительная информация'
    },
    'Active': {
        ru: 'Активен'
    },
    'Registered': {
        ru: 'Зарегистрирован'
    },
    'Roles': {
        ru: 'Роли'
    },
    'Role added!': {
        ru: 'Роль добавлена!'
    },
    'There are blank text fields': {
        ru: 'Имеются незаполненые поля текста'
    },
    'Contact saved!': {
        ru: 'Контакт сохранен!'
    },
    'Contact added!': {
        ru: 'Контакт добавлен!'
    },
    'Menu': {
        ru: 'Меню'
    },
    'Actions': {
        ru: 'Действия'
    },
    'Groups': {
        ru: 'Группы'
    },
    'Add link': {
        ru: 'Добавить ссылку'
    },
    'Add to groups': {
        ru: 'Добавить в группы'
    },
    'Group': {
        ru: 'Группа'
    },
    'Without group': {
        ru: 'Без группы'
    },
    'Item type': {
        ru: 'Тип элемента'
    },
    'Override groups': {
        ru: 'Переопределить группы'
    },
    'Item card': {
        ru: 'Карточка элемента'
    },
    'Extra field saved!': {
        ru: 'Дополнительное поле сохранено!'
    },
    'Additionally':{
        ru: 'Дополнительно'
    },
    'Variable type': {
        ru: 'Тип переменной'
    },
    'Is a list group': {
        ru: 'Является группой списка'
    },
    'Required': {
        ru: 'Обязательное'
    },
    'Hint': {
        ru: 'Подсказка'
    },
    'String': {
        ru: 'Строка'
    },
    'Number': {
        ru: 'Число'
    },
    'Text': {
        ru: 'Текст'
    },
    'List': {
        ru: 'Список'
    },
    'Media': {
        ru: 'Медия(видео, аудио)'
    },
    'File': {
        ru: 'Файл'
    },
    'Image': {
        ru: 'Изображение'
    },
    'Checkbox': {
        ru: 'Флажок'
    },
    'Management of languages': {
        ru: 'Управление языками'
    },
    'Default values': {
        ru: 'Значения по умолчанию'
    },
    'Default size': {
        ru: 'Размер по умолчанию'
    },
    'Possible values (separated by commas)': {
        ru: 'Возможные значения (через запятую)'
    },
    'Are you sure you want to delete this project?': {
        ru: 'Вы точно хотите удалить этот проект?'
    },
    'Are you sure you want to delete these records?': {
        ru: 'Вы точно хотите удалить эти записи?'
    },
    'Are you sure you want to delete this entry?': {
        ru: 'Вы точно хотите удалить эти запись?'
    },
    'The project was successfully deleted!': {
        ru: 'Проект успешно удален!'
    },
    'Address of the link': {
        ru: 'Адрес ссылки'
    },
    'Download': {
        ru: 'Загрузить'
    },
    'Tune': {
        ru: 'Настроить'
    },
    'Owner': {
        ru: 'Владелец'
    },
    'Resources': {
        ru: 'Ресурсы'
    },
    'These files are already on the server': {
        ru: 'Данные файлы уже есть на сервере'
    },
    'Configure advanced options': {
        ru: 'Настроить дополнительные параметры'
    },
    'Files': {
        ru: 'Файлы'
    },
    'Apply': {
        ru: 'Применить'
    },
    'Icon': {
        ru: 'Иконка'
    },
    'Text editor': {
        ru: 'Текстовый редактор'
    },
    'Min. value': {
        ru: 'Мин.значение'
    },
    'Max. value': {
        ru: 'Макс.значение'
    },
    'Values': {
        ru: 'Переменные'
    },
    'Expand': {
        ru: 'Развернуть'
    },
    'Collapse': {
        ru: 'Свернуть'
    },
    'Controller': {
        ru: 'Контроллер'
    },
    'Plugins': {
        ru: 'Плагины'
    },
    'Prices': {
        ru: 'Цены'
    },
    'The text has been changed! Save it?': {
        ru: 'Текст был изменен! Сохранить?'
    },
    'Are you sure you want to delete this entry?': {
        ru: 'Вы точно хотите удалить эту запись?'
    },
    'Are you sure you want to delete the selected items?': {
        ru: 'Вы точно хотите удалить выделенные элементы?'
    },
    'The name': {
        ru: 'Имя'
    },
    'is already in use! Select another': {
        ru: 'уже используется! Выберите другое'
    },
    'File saved!': {
        ru: 'Файл сохранен!'
    },
    'The file was not saved!': {
        ru: 'Файл не сохранен!'
    },
    'Can not load template list!': {
        ru: 'Не могу загрузить список шаблонов!'
    },
    'Select module': {
        ru: 'Выбрать модуль'
    },
    'Edit': {
        ru: 'Редактировать'
    },
    'Insert link': {
        ru: 'Вставить ссылку'
    },
    'Insert module': {
        ru: 'Вставить модуль'
    },
    'Adding a module': {
        ru: 'Выбать модуль'
    },
    'Create a new language': {
        ru: 'Создать новый язык'
    },
    'New item': {
        ru: 'Новый пункт'
    },
    'Select page': {
        ru: 'Выбрать страницу'
    },
    'Adding a record': {
        ru: 'Добавление записи'
    },
    'Add a section': {
        ru: 'Добавление раздела'
    },
    'Unique code': {
        ru: 'Уникальный код'
    },
    'Create new module': {
        ru: 'Создать новый модуль'
    },
    'Create new template': {
        ru: 'Создать новый шаблон'
    },
    'Alternate URL': {
        ru: 'Альтернативный URL'
    },
    'Yes': {
        ru: 'Да'
    },
    'No': {
        ru: 'Нет'
    },
    'Category': {
        ru: 'Категория'
    },
    'Categories': {
        ru: 'Категории'
    },
    'Add a new category': {
        ru: 'Добавить новую категорию'
    },
    'Multi-Editing Partitions': {
        ru: 'Мультиредактирование разделов'
    },
    'Refresh': {
        ru: 'Обновить'
    },
    'Clear': {
        ru: 'Очистить'
    },
    'Regions': {
        ru: 'Регионы'
    },
    'Alternative link': {
        ru: 'Алтернативная ссылка'
    },
    'Add a new contact': {
        ru: 'Добавить новый контакт'
    },
    'Site settings': {
        ru: 'Настройки сайта'
    },
    'The field can not be empty': {
        ru: 'Поле не должно быть пустым'
    },
    'The checkbox must be enabled': {
        ru: 'Флажок должен быть включен'
    },
    'Enter the correct e-mail address': {
        ru: 'Введите правильный e-mail'
    },
    'Navigation': {
        ru: 'Навигация'
    }


}