#!/bin/sh

# Get the UID and GID of the working directory (mounted from host)
USER_ID=${DOCKER_UID:-$(stat -c '%u' /var/www)}
GROUP_ID=${DOCKER_GID:-$(stat -c '%g' /var/www)}

if [ "$USER_ID" != "0" ]; then
    # Change the UID/GID of www-data to match the host user
    # We use usermod/groupmod if they exist or manually edit /etc/passwd if needed.
    # But since we installed gosu, we can just create a user if it doesn't exist
    # or just use gosu with the ID directly.
    
    # Ensure current user matches the host UID
    echo "Sincronizando permisos para UID: $USER_ID y GID: $GROUP_ID"
    
    # Update www-data to match host UID/GID
    sed -i "s/^www-data:x:33:33:/www-data:x:$USER_ID:$GROUP_ID:/" /etc/passwd
    sed -i "s/^www-data:x:33:/www-data:x:$GROUP_ID:/" /etc/group
fi

# Ensure storage and cache folders have the right owner internaly
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Execute the CMD as root (PHP-FPM will drop privileges to www-data internally)
exec "$@"

