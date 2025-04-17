<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>laravel-modules</title>
    <link rel="stylesheet" href="./assets/style.css">
</head>

<body>

    <h3>🚀 منشئ موديولات Laravel (معتمد على nwidart/laravel-modules)</h3>

    <div class="note">
        ⚠️ <strong>تحذير:</strong> يُفضل تجربة السكريبت في مشروع او مسار تجريبي أولًا، ومراجعة الملفات بعد النقل.
    </div>

    <?php if (isset($_GET['message'])): ?>
        <div class="msg"><?= htmlspecialchars($_GET['message']) ?></div>
        <script>
            setTimeout(() => {
                document.querySelector('.msg').remove();
            }, 7000);

            if (history.replaceState) {
                const url = new URL(window.location);
                url.searchParams.delete('message');
                history.replaceState(null, '', url);
            }
        </script>
    <?php endif; ?>

    <form method="POST" action="./script.php">
        <label>📁 مسار ال folder المستهدف (اللي هيتحول ل module):</label>
        <input type="text" name="targetSource" placeholder="مثال: /base-folder/target-project" required>

        <label>📂 المكان اللي هيتعمل فيه ال module الجديد:</label>
        <input type="text" name="destinationSource" placeholder="مثال: /base-project/Modules" required>

        <button type="submit">إنشاء الموديول</button>
    </form>

    <div class="note">
        ⚠️ تأكد إنك مثبت الباكيج <strong>nwidart/laravel-modules</strong> في المشروع المستهدف علشان ال <strong>Modules</strong> تشتغل بشكل صحيح.<br>
        <div class="codes">
            <code>composer require nwidart/laravel-modules</code><br>
            <code>php artisan vendor:publish --provider="Nwidart\Modules\LaravelModulesServiceProvider"</code>
        </div>
    </div>

    <p style="margin-top: 30px; font-size: 14px; color: gray;">🔧 تم تطوير هذا السكربت بواسطة <strong><a href="https://github.com/IslamAlsayed">اسلام السيد</a></strong> - Full Stack Developer 💻</p>
</body>

</html>