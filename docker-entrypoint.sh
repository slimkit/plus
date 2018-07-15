#!/bin/bash
set -euo pipefail

PLUS_NAME=Plus\(ThinkSNS\+\)
PLUS_SRC_PATH=/var/src/plus
PLUS_TARGET_PATH=/var/www
PHP_FPM_RUNNING_USER=www-data
PHP_FPM_RUNNING_GROUP=www-data

if ! [ -e $PLUS_SRC_PATH/public/index.php -o "$(ls -A $PLUS_SRC_PATH)" ]; then
	echo >&2 "\033[31m ERROR: \033[0m The $PLUS_NAME code was not found in PHP-FPM container path \"$PLUS_SRC_PATH\""
	exit 1
fi

if ! [ -e $PLUS_TARGET_PATH/plus/index -o "$(ls -A $PLUS_TARGET_PATH)" ]; then
	echo >&2 "$PLUS_NAME not found in \"$PLUS_TARGET_PATH\" - copying now..."

	if [ "$(ls -A $PLUS_TARGET_PATH)" ]; then
		echo >&2 "\033[33m WARNING: \033[0m \"$PLUS_TARGET_PATH\" is not empty - press \033[42;30m Ctrl+C \033[0m now if this is an error!"
		( set -x; ls -A $PLUS_TARGET_PATH; sleep 10 )
	fi

	cd $PLUS_TARGET_PATH
	tar \
		--create \
		--file - \
		--one-file-system \
		--directory "$PLUS_SRC_PATH" \
		--owner "$PHP_FPM_RUNNING_USER" \
		--group "$PHP_FPM_RUNNING_GROUP" \
	. | tar --extract --file -

	echo >&2 "\033[32m SUCCESS: \033[0m Complete! $PLUS_NAME has been successfully copied to \"$PLUS_TARGET_PATH\""
fi

if ! [ -e $PLUS_TARGET_PATH/storage/configure/.env ]; then
	echo >&2 "Copying \`.env\` file to \"$PLUS_TARGET_PATH/storage/configure/\" path"
	cp $PLUS_SRC_PATH/storage/configure/.env.example $PLUS_TARGET_PATH/storage/configure/.env

	echo >&2 "Generating $PLUS_NAME App encryption key and JWT Authentication Secret"
	cd $PLUS_TARGET_PATH
	php artisan app:key-generate
fi

exec "$@"
