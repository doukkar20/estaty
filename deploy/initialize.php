<?php

// Deployment initialization uses environment variables, never committed secrets.
declare(strict_types=1);

function setting(string $name, string $default = ''): string
{
    // Shared hosts can disable putenv; Dotenv still populates these arrays.
    $value = $_ENV[$name] ?? $_SERVER[$name] ?? getenv($name);
    return $value === false ? $default : $value;
}

try {
    $appKey = setting('APP_KEY');
    if (!str_starts_with($appKey, 'base64:') || strlen((string) base64_decode(substr($appKey, 7), true)) !== 32) {
        throw new RuntimeException('Configure a persistent, randomly generated APP_KEY before deployment.');
    }
    $database = setting('DB_DATABASE');
    $host = setting('DB_HOST');
    $port = setting('DB_PORT', '3306');
    if ($database === '' || $host === '' || preg_match('/[;\x00-\x1f]/', $database . $host) || !ctype_digit($port)) {
        throw new RuntimeException('Configure valid DB_HOST, DB_PORT and DB_DATABASE variables.');
    }
    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
    $connection = null;
    for ($attempt = 0; $attempt < 30; $attempt++) {
        try {
            $connection = new PDO($dsn, setting('DB_USERNAME'), setting('DB_PASSWORD'), [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_MULTI_STATEMENTS => true,
            ]);
            break;
        } catch (PDOException $exception) {
            if ($attempt === 29) {
                throw new RuntimeException('Database connection failed. Check the private MySQL connection settings.');
            }
            sleep(2);
        }
    }

    $tableCount = (int) $connection->query('SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE()')->fetchColumn();
    if ($tableCount === 0) {
        if (setting('SITE_INITIALIZE') !== 'true') {
            throw new RuntimeException('Database is empty. Explicitly set SITE_INITIALIZE=true for first installation.');
        }
        $password = setting('INITIAL_ADMIN_PASSWORD');
        $email = setting('INITIAL_ADMIN_EMAIL');
        $username = setting('INITIAL_ADMIN_USERNAME', 'admin');
        if (strlen($password) < 16 || !filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[A-Za-z0-9_.-]{3,40}$/', $username)) {
            throw new RuntimeException('Set INITIAL_ADMIN_EMAIL, INITIAL_ADMIN_USERNAME, and INITIAL_ADMIN_PASSWORD (at least 16 characters).');
        }

        $starter = dirname(__DIR__) . '/public/installer/database.sql';
        if (!is_file($starter)) {
            throw new RuntimeException('Bundled installation database is missing.');
        }
        $connection->exec(file_get_contents($starter));

        // Disable all starter account passwords and recovery email addresses.
        foreach (['admins', 'vendors', 'agents', 'users'] as $table) {
            $statement = $connection->prepare("UPDATE `$table` SET password = ?, email = CONCAT('demo-', id, '@example.invalid')");
            $statement->execute([password_hash(bin2hex(random_bytes(32)), PASSWORD_BCRYPT)]);
        }
        $connection->exec('UPDATE admins SET status = 0');
        $statement = $connection->prepare("UPDATE admins SET username = ?, email = ?, password = ?, first_name = 'Site', last_name = 'Owner', status = 1, role_id = NULL WHERE id = 1");
        $statement->execute([$username, $email, password_hash($password, PASSWORD_BCRYPT)]);
        $connection->exec("UPDATE basic_settings SET smtp_status = 0, timezone = 'Africa/Casablanca'");
        fwrite(STDOUT, "Installed starter data and secured demo accounts.\n");
    }

    // Refuse to mark a partial or unrelated database as an installed site.
    $connection->query('SELECT id FROM basic_settings LIMIT 1')->fetchColumn();
    $connection->query('SELECT id FROM languages LIMIT 1')->fetchColumn();
    if (!is_file(dirname(__DIR__) . '/storage/installed')) {
        file_put_contents(dirname(__DIR__) . '/storage/installed', 'Installed ' . gmdate(DATE_ATOM));
    }
    fwrite(STDOUT, "Database ready.\n");
} catch (Throwable $exception) {
    // Do not print PDO exception strings: they can contain connection details.
    $message = $exception instanceof PDOException ? 'Database initialization failed; check schema compatibility.' : $exception->getMessage();
    fwrite(STDERR, $message . "\n");
    exit(1);
}
