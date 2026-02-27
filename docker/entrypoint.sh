#!/bin/bash

# Get the UID and GID of the working directory (mounted from host)
USER_ID=${DOCKER_UID:-$(stat -c '%u' /var/www)}
GROUP_ID=${DOCKER_GID:-$(stat -c '%g' /var/www)}

if [ "$USER_ID" != "0" ]; then
    # Ensure current user matches the host UID
    echo "Sincronizando permisos para UID: $USER_ID y GID: $GROUP_ID"
    
    # Update www-data to match host UID/GID
    sed -i "s/^www-data:x:33:33:/www-data:x:$USER_ID:$GROUP_ID:/" /etc/passwd
    sed -i "s/^www-data:x:33:/www-data:x:$GROUP_ID:/" /etc/group
fi

# Ensure storage and cache folders have the right owner internaly
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Wait for database to be ready
if [ "$DB_CONNECTION" = "pgsql" ]; then
    echo "Esperando a PostgreSQL en $DB_HOST:$DB_PORT..."
    while ! bash -c "exec 3<>/dev/tcp/$DB_HOST/$DB_PORT" 2>/dev/null; do
        echo "PostgreSQL no disponible todavía, reintentando en 2 segundos..."
        sleep 2
    done
    echo "¡PostgreSQL detectado y listo!"
fi

# Ensure the database exists (Zero-Config Robustness)
echo "Asegurando la existencia de la base de datos: $DB_DATABASE"
gosu www-data php -r "
    \$host = '$DB_HOST';
    \$port = '$DB_PORT';
    \$user = '$DB_USERNAME';
    \$pass = '$DB_PASSWORD';
    \$db   = '$DB_DATABASE';
    try {
        \$pdo = new PDO(\"pgsql:host=\$host;port=\$port;dbname=postgres\", \$user, \$pass);
        \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        \$exists = \$pdo->query(\"SELECT 1 FROM pg_database WHERE datname = '\$db'\")->fetchColumn();
        if (!\$exists) {
            echo \"Creando base de datos '\$db'...\n\";
            \$pdo->exec(\"CREATE DATABASE \\\"\$db\\\"\");
        } else {
            echo \"La base de datos '\$db' ya existe.\n\";
        }
    } catch (Exception \$e) {
        echo \"Error al verificar/crear la BD: \" . \$e->getMessage() . \"\n\";
        exit(1);
    }
"

# Run migrations as www-data
echo "Ejecutando migraciones automáticas..."
gosu www-data php artisan migrate --force --no-interaction


# Execute the CMD as root (PHP-FPM will drop privileges to www-data internally)
exec "$@"


