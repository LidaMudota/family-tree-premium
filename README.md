# Family Tree Premium

Production-baseline Laravel 12 приложение для создания семейных деревьев (public + private + admin), совместимое с shared hosting (PHP + MySQL + Blade).

## Реализовано
- Публичные страницы: Home, How it works, FAQ, Privacy, Terms, Contact, Support, robots/sitemap.
- Auth: регистрация, login/logout, верификация email, reset password.
- Кабинет: список деревьев, CRUD дерева, архивирование.
- Редактор дерева: SVG-визуализация, pan/zoom, fit/center, поиск с центрированием, сохранение viewport.
- Персоны: CRUD, неполные даты, фото на private disk.
- Связи: father/mother/brother/sister/partner/child + валидации самоссылки/дубликата/цикла.
- Экспорт: PNG (серверная выдача файла), PDF (печать-экспорт через print layout).
- Профиль/безопасность: смена профиля, пароля, удаление аккаунта.
- Админка: users list, trees list, block/unblock, audit/security events.
- Security baseline: CSRF, policy checks, IDOR protection, throttling, audit trail, blocked middleware.

## Архитектура (кратко)
- Domain модели: `Tree`, `Person`, `Relationship`, `ExportJob`, `AuditEvent`.
- HTTP слой: FormRequest + Controllers + Middleware + Policy.
- Service слой: `AuditService`, `PhotoService`, `RelationshipGuard`.
- Frontend: Blade + собственный JS/SVG рендер дерева.

## Локальный запуск (без переустановки)
1. Заполнить `.env` на основе `.env.example`.
2. `php artisan key:generate`
3. `php artisan migrate --force`
4. `php artisan storage:link` (только для public-диска).
5. `php artisan serve`
6. (frontend) локально собрать ассеты: `npm run build` и деплоить собранные файлы.

## Deploy на shared hosting
1. Загрузить проект, `public/` направить в web root.
2. Настроить PHP 8.2+, extensions (pdo_mysql, mbstring, openssl, fileinfo, gd).
3. Заполнить `.env` (MySQL, mail, APP_URL, APP_KEY).
4. Выполнить миграции: `php artisan migrate --force`.
5. Включить cron: `php artisan schedule:run` каждую минуту (опционально).
6. В production: `APP_DEBUG=false`, `LOG_LEVEL=warning`.

## Что заполнить в `.env`
См. комментарии `# ВСТАВЬТЕ ВРУЧНУЮ` в `.env.example` (DB, APP_URL, MAIL, APP_KEY, debug).

## После деплоя проверить вручную
- Регистрация и email verification.
- Создание дерева, добавление персоны, добавление связи.
- Загрузка/удаление фото.
- Экспорт PNG и print->PDF.
- Ограничение доступа к чужому дереву.
- Блокировка пользователя в админке.

## Роли и доступы
- `user`: только свои деревья/персоны/связи/экспорты.
- `admin`: обзор пользователей, деревьев, блокировка, аудит.

## Базовые маршруты
- Public: `/`, `/how-it-works`, `/faq`, `/privacy`, `/terms`, `/contact`, `/support`.
- Private: `/dashboard`, `/trees`, `/profile`.
- Admin: `/admin`.

## Backup/restore
- Ежедневный дамп MySQL (mysqldump) + копия storage/app/private.
- Проверка restore на staging минимум раз в месяц.

## Release checklist
- Миграции применены.
- APP_DEBUG=false.
- Проверен login/reset/verification.
- Проверен IDOR и admin guard.
- Проверен экспорт и загрузка фото.
