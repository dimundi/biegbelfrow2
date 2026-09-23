#!/bin/sh
set -eu
cd /var/www/html
if ! wp core is-installed --allow-root; then
    wp core install --allow-root --url="$WORDPRESS_URL" --title="Bieg Belfrów — dev" --admin_user="$WORDPRESS_ADMIN_USER" --admin_password="$WORDPRESS_ADMIN_PASSWORD" --admin_email="$WORDPRESS_ADMIN_EMAIL" --skip-email
    wp theme activate biegbelfrow --allow-root
    wp option update blog_public 0 --allow-root
fi
wp core is-installed --allow-root
if ! wp plugin is-active biegbelfrow --allow-root; then
    wp plugin activate biegbelfrow --allow-root
fi
