# StepMed release

Релиз подготовлен для GitHub → iHor.

- лендинг: `public/index.html`;
- кабинет: `public/login.php` и `public/admin/`;
- API: `public/api/`;
- T‑Bank: заглушка, `TBANK_ENABLED=0`;
- платежи: СБП, ручное подтверждение Ксенией Степановой;
- секреты: только в `.env`, вне Git.

Перед публикацией проверить: HTTPS, корень сайта `public/`, `.env`, `ADMIN_PASSWORD`, подключение MySQL и вход через `/login.php`.

