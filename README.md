# PHP ядро для сайтов SiteEdit-pro

## Редирект URL ссылок
Иногда для SEO требуется убрать некоторые паразитные ссылки, то это делается формированием файла urlredirect.dat
Пример:
```
/petronas-urania-3000-10w-40/*	/petronas-urania-3000-10w-40/
/selenia/*	/selenia/
/syntium-1000-10w-40/*	/syntium-1000-10w-40/
/analitika/*	/analitika/
https://www.mysite.ru/prisadki-dlya-benzinovogo-dvigatelya/*  https://www.mysite.ru/prisadki-dlya-benzinovogo-dvigatelya/
```
Разделителем между подсторокой патерна и подстрокой перенаправления является табулятор. Звездочка отсекает все следующие  параметры URL.
