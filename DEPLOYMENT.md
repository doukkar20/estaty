# Estaty / RAFAIMMOBILIER deployment

This is the complete Laravel application, not a static GitHub Pages export.
The repository is intended to stay private. GitHub stores source; Railway runs PHP and MySQL.

## Railway

1. Create a project with a MySQL service and persistent database volume.
2. Add an application service connected to this GitHub repository. Use the included Dockerfile.
3. Attach a persistent application volume at `/data`. It holds uploaded images and Laravel sessions.
4. Set the variables listed in `.env.production.example`. Reference MySQL's private connection variables for `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`.
5. Generate `APP_KEY` once with `php -r 'echo "base64:".base64_encode(random_bytes(32));'`. Save it as a secret environment variable and retain it on redeployments.
6. For a new empty database only, set `SITE_INITIALIZE=true` and provide `INITIAL_ADMIN_USERNAME`, `INITIAL_ADMIN_EMAIL`, and a unique `INITIAL_ADMIN_PASSWORD` of at least 16 characters. Startup imports the bundled starter database and replaces starter login passwords and email addresses. It never resets an existing database. A failed partial import requires review, not an automatic reset.
7. Generate a Railway HTTPS domain, set `APP_URL` to that URL, and deploy. Test `/`, `/properties`, and `/admin` before sharing the link.
8. After installation, set `SITE_INITIALIZE=false` and remove `INITIAL_ADMIN_PASSWORD`. Log in at `/admin` and configure contact details, SMTP, payment gateways, and real property content.

`APP_DEBUG` must remain `false`, `APP_ENV=production`, and `SESSION_SECURE_COOKIE=true`.
The Apache document root is `public/`. Installer assets and SQL are denied by the production web server.
Configure mail delivery in the app before relying on contact emails or password resets.
Review current provider pricing before selecting a paid plan; no subscription is included in this repository.

## Source exclusions

Local `.env`, database exports, credentials, `vendor/`, caches, logs, and sessions are excluded.
The included SQL file is the application's starter installer database, not the local live database.
Composer installs dependencies from the lockfile during the container build.
The existing French/Arabic support and RAFAIMMOBILIER branding are retained.

## Verification

PHP parsing, source exclusions, and shell syntax can be checked locally. An actual Docker build,
first-time MySQL import, persistent-volume restart, and HTTPS browser smoke test must pass on
the hosting provider before the site is considered deployed.
