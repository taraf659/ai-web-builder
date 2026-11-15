# AI Web Builder

تطبيق Laravel 12 يساعد على إنشاء نماذج صفحات ويب عبر مساعد برمجي، مع واجهات للمشاريع والمساعدة ومحادثة API. يستخدم Laravel وSQLite أثناء التطوير وVite لبناء ملفات الواجهة.

## المتطلبات

- PHP 8.2 أو أحدث مع Composer
- Node.js 18 أو أحدث مع npm
- SQLite أو قاعدة بيانات متوافقة مع إعدادات Laravel

## التشغيل محليًا

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm install
npm run build
php artisan serve
```

بعد ذلك افتح `http://127.0.0.1:8000`.

للتطوير مع إعادة بناء الواجهة تلقائيًا:

```bash
composer run dev
```

يمكن ضبط `OPENAI_API_KEY` في `.env` لتفعيل الردود المعتمدة على OpenAI. يعمل التطبيق بردود احتياطية عند غياب المفتاح أو تعذر الاتصال.

## الاختبارات

```bash
php artisan test
```

يتم تشغيل الاختبارات تلقائيًا عبر GitHub Actions عند كل Pull Request وكل Push إلى الفرع الرئيسي. لا يتم رفع `.env` أو `vendor` أو `node_modules` أو ملفات قاعدة البيانات المحلية إلى GitHub.

## بنية مختصرة

- `app/Http/Controllers`: منطق المحادثة وإدارة المشاريع.
- `app/Models`: نماذج البيانات.
- `resources/views`: صفحات Laravel.
- `resources/js` و`resources/css`: أصول الواجهة التي يعالجها Vite.
- `routes/web.php`: صفحات التطبيق وواجهات API.
- `database/migrations`: مخطط قاعدة البيانات.
- `tests`: اختبارات الوحدة والميزات.
- `docs`: وثائق المشروع الحالية.
