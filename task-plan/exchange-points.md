# План: Точки обміну (множинні записи)

## Мета
Замінити одиночну таблицю `contact_info` на список точок обміну. Кожна точка має свою адресу, координати та телефон. У адмінці вкладка перейменовується на "Точки обміну".

---

## Крок 1 — Міграція: нова таблиця `exchange_points`

Створити нову міграцію, яка:
- Додає таблицю `exchange_points` з полями:
  - `id` (bigint, PK)
  - `name` (varchar 255) — назва точки, наприклад "Центр", "ТЦ Клімат"
  - `address` (varchar 255)
  - `coordinates` (varchar 255) — для Google Maps embed
  - `telephone` (varchar 255)
  - `is_active` (tinyint/boolean, default 1)
  - `created_at`, `updated_at`

> Стару таблицю `contact_info` **не чіпаємо** — залишаємо як є. Якщо дані в ній вже не потрібні, видалимо окремою міграцією після підтвердження.

---

## Крок 2 — Модель `ExchangePoint`

Новий файл `app/Models/ExchangePoint.php`:
- `$table = 'exchange_points'`
- `$fillable` — всі поля крім id/timestamps
- Cast `is_active` → boolean

---

## Крок 3 — Filament ресурс `ExchangePointResource`

Новий ресурс замість (або поруч з) `ContactInfoResource`:

**Лейби навігації:**
- `$navigationLabel = 'Точки обміну'`
- `$modelLabel = 'Точка обміну'`
- `$pluralModelLabel = 'Точки обміну'`
- `$navigationSort = 100` (залишаємо місце)
- Іконка: `heroicon-o-map-pin`

**Форма (form):**
- `name` — Назва точки (required)
- `address` — Адреса (required)
- `coordinates` — Координати (required, hint: формат `lat,lng`)
- `telephone` — Телефон (required, tel)
- `is_active` — Toggle (активна/неактивна)

**Таблиця (table):**
- Колонки: name, address, telephone, is_active (badge), updated_at
- Дії: EditAction, DeleteAction

---

## Крок 4 — Прибрати або приховати старий `ContactInfoResource`

Два варіанти:
- **Варіант A (рекомендований):** Видалити `ContactInfoResource.php` і його Pages, залишити модель `ContactInfo` щоб не ламати старий код.
- **Варіант B:** Приховати зі навігації через `protected static bool $shouldRegisterNavigation = false`.

Варіант A чистіший.

---

## Крок 5 — Оновити `HomeController`

Замість `ContactInfo::first()` передавати в шаблон:
```php
$exchangePoints = ExchangePoint::where('is_active', true)->get();
```

Видалити `$contactInfo` з контролера і шаблону.

---

## Крок 6 — Оновити `home.blade.php`

Поточна карта рендерить одну точку через `$contactInfo->coordinates`. При кількох точках потрібно вирішити як відображати:

Для кожної активної точки обміну — окремий блок:

```
[Назва точки] | [Адреса] | [Телефон]
[Google Maps iframe з координатами цієї точки]
```

Цикл `@foreach($exchangePoints as $point)` по всіх активних точках.

---

## Файли, які зміняться

| Файл | Дія |
|------|-----|
| `database/migrations/xxxx_create_exchange_points_table.php` | Новий |
| `app/Models/ExchangePoint.php` | Новий |
| `app/Filament/Resources/ExchangePointResource.php` | Новий |
| `app/Filament/Resources/ExchangePointResource/Pages/*.php` | Нові (3 файли) |
| `app/Filament/Resources/ContactInfoResource.php` | Видалити |
| `app/Filament/Resources/ContactInfoResource/Pages/*.php` | Видалити (3 файли) |
| `app/Http/Controllers/HomeController.php` | Оновити |
| `resources/views/home.blade.php` | Оновити |

---

## Що НЕ змінюється
- Модель `ContactInfo` і таблиця `contact_info` — залишаємо (не ламаємо нічого зайвого)
- Всі інші ресурси Filament
- Логіка Telegram-нотифікацій (вони для `ContactRequest` і `Customer`)
