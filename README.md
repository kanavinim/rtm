# RTM — staging deployable code

Репозиторий кода staging-сайта [rtm.kanavinim.ru](https://rtm.kanavinim.ru)  
(темы + плагины + языковые файлы). **Production `rtm-a.ru` сюда не деплоится.**

Repository tracks deployable WordPress code for **staging only**.  
Production `rtm-a.ru` is never touched by this repo.

## Что в git / What is tracked

```
wp-content/themes/
wp-content/plugins/
wp-content/languages/
.github/workflows/deploy-staging.yml
```

**Не в git:** `uploads/`, `wp-config.php`, секреты, полный WP core.

## Autodeploy (staging)

При каждом push в `main` GitHub Actions:

1. checkout репозитория
2. rsync `wp-content/themes`, `plugins`, `languages` на staging docroot
3. **не трогает** `uploads/` и `wp-config.php` на сервере
4. `--delete` только внутри themes/plugins/languages (чтобы удалённые плагины/темы пропали со staging)

Секреты репозитория: `STAGING_HOST`, `STAGING_USER`, `STAGING_SSH_KEY`, `STAGING_PATH`.

Ручной запуск: Actions → «Deploy to staging» → Run workflow.

## Откат / Rollback (после неудачного обновления плагина)

### Вариант A — revert коммита и push

```bash
git revert HEAD --no-edit   # или конкретный SHA
git push origin main        # Actions задеплоит предыдущее состояние
```

### Вариант B — checkout тега/коммита и force-push ветки rollback

```bash
git checkout pre-git-baseline   # или нужный commit/tag
# создать ветку или reset main (осторожно):
git checkout -b rollback-fix
git push origin rollback-fix:main
```

Проще для «вернуть как было до обновления»:

```bash
# 1. Найти коммит до обновления
git log --oneline

# 2. Откатить main к нему и задеплоить
git reset --hard <good-commit>
git push --force origin main   # только если вы уверены; лучше revert
```

**Рекомендация:** перед обновлением плагинов на staging сделайте тег:

```bash
git tag pre-plugin-update-$(date +%Y%m%d)
git push origin --tags
```

Потом откат: `git checkout pre-plugin-update-YYYYMMDD` → push в `main` (через revert/reset) → Actions задеплоит.

### Вариант C — Re-run предыдущего успешного workflow

GitHub → Actions → выбрать успешный Deploy → Re-run jobs  
(восстановит файлы из того checkout SHA).

## Локальная разработка

```bash
git clone https://github.com/kanavinim/rtm.git
cd rtm
# правки тем/плагинов → commit → push → автодеплой на staging
```

## Важно

- Staging only: https://rtm.kanavinim.ru  
- Production `rtm-a.ru` — **вне scope** этого репозитория.
