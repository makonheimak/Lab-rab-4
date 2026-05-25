# TechNovaShop PWA

Лабораторная работа №4: PWA-интернет-магазин электроники на Laravel REST API и Vue.

## Состав проекта

- `electriz-backend` - Laravel API, MySQL-модели, миграции, сиды, Sanctum, Swagger, заготовки Socialite, платежей, push и чата.
- `electriz/frontend` - Vue PWA с каталогом, товаром, корзиной, оплатой, профилем, новостями, платежами, чатом и desktop-страницей через iframe.
- `docker-compose.yml` - локальный запуск MySQL, backend и frontend.

## Что реализовано по заданию

- БД интернет-магазина: пользователи, категории, товары, корзина, заказы, позиции заказа, новости, push-подписки, история платежей, чат.
- REST API на Laravel с авторизацией Laravel Sanctum.
- Resource/Collection для основных сущностей: товары, категории, новости, корзина, заказы, позиции заказа, пользователь, push-подписки, платежи, чат.
- Swagger-аннотации и security scheme для Bearer/Sanctum.
- Vue + Vue Router + Vuex + axios.
- PWA manifest, service worker и кеширование GET-запросов.
- Desktop-режим: переход на `/desktop`, где мобильное PWA отображается в iframe.
- Социальная авторизация через Socialite: Google, GitHub, VK. Для реального входа нужно заполнить OAuth-ключи.
- Push-демонстрация: браузерное уведомление и API для сохранения подписки.
- Чат с администратором: локальная демонстрация с автоответом администратора. Для реального Pusher нужно заполнить ключи Pusher и подключить broadcasting.
- Stripe sandbox демонстрация: при создании заказа создается запись платежа, история платежей, график расходов и PDF-чек.
- Локализация подготовлена на уровне русскоязычного интерфейса; полноценное переключение языков можно расширить через профиль.

## Запуск через Docker

```powershell
docker compose up -d
```

После запуска:

```text
Frontend: http://localhost:3000
Backend:  http://localhost:8000
```

Если добавлялись новые миграции:

```powershell
docker compose exec backend php artisan migrate --seed
```

## Ручной запуск backend

```powershell
cd electriz-backend
composer install
copy env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Ручной запуск frontend

```powershell
cd electriz/frontend
npm install
copy .env.example .env
npm run dev
```

## Тестовый аккаунт

```text
email: demo@example.com
password: password
```

## Проверка работы

1. Открыть `http://localhost:3000`.
2. Войти в профиль под тестовым аккаунтом.
3. Открыть каталог, найти товар и добавить его в корзину.
4. Перейти в корзину и оформить заказ.
5. Открыть `Платежи`, проверить историю, график и PDF-чек.
6. Открыть `Чат`, отправить сообщение и увидеть ответ администратора.
7. Нажать `Проверить push` на главной странице.
8. Открыть `http://localhost:8000/api/products`, `http://localhost:8000/api/categories`, `http://localhost:8000/api/news`.

## Социальный вход

В backend `.env` нужно заполнить:

```env
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT="${APP_URL}/api/auth/google/callback"

GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
GITHUB_REDIRECT="${APP_URL}/api/auth/github/callback"

VK_CLIENT_ID=
VK_CLIENT_SECRET=
VK_REDIRECT="${APP_URL}/api/auth/vkontakte/callback"
```

Без этих ключей кнопки Google/GitHub/VK показывают наличие реализации, но реальный OAuth не завершится.
