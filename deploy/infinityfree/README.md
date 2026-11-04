# InfinityFree preparation

This host runs PHP/MySQL, but has no SSH, Docker, cron jobs or queue workers.
Use file sessions/cache and the synchronous queue driver.

## Account setup

Create a free hosting account/subdomain in the InfinityFree dashboard, not just
an InfinityFree login. Create an empty MySQL database in the hosting control panel.
Record the website URL, hosting username, FTP hostname, MySQL hostname and full
database name. Never commit the hosting password or populated `.env` to GitHub.

## Upload layout

The prepared `htdocs` folder contains the application plus production Composer
dependencies. Upload its contents to the site's `htdocs` using FTP. The root
`.htaccess` sends requests into `public/` and denies private application paths.
Use `deny.htaccess` inside `public/installer` and private runtime directories.
Uploaded images in this app are already stored under `public/assets/img`.
No publicly accessible installer or SQL import script should be uploaded.

Install dependencies locally from `composer.lock`, without development packages.
Do not copy local dependency modifications or cached PHP configuration. Keep all
PHP files below the host's 1 MB limit; run Composer without autoloader optimization
if its generated class map exceeds that limit. No Node build is needed when the
existing compiled `public/assets` files are retained unchanged.

Import the prepared starter SQL through phpMyAdmin. Use a unique owner password
and disable starter account access before the site is shared. Keep the SQL outside
`htdocs`. The import initializes a new database; it must never overwrite existing
data without review. The `storage/installed` marker belongs in the upload only
after the database has been initialized successfully.

Configure `.env` privately with the real domain and hosting database settings.
Use `APP_ENV=production`, `APP_DEBUG=false`, and a unique persistent `APP_KEY`.
Set `SESSION_SECURE_COOKIE=true` once HTTPS is available. Configure SSL in the
InfinityFree dashboard, then test the homepage, search, login, and uploaded images.
Verify `.env`, logs, application source, dependencies, and installer SQL cannot be
retrieved by URL. Email delivery needs a supported external provider and testing.

Official host instructions:
https://forum.infinityfree.com/t/how-to-install-a-laravel-site-on-infinityfree/118578
