#!/usr/bin/env sh
set -e
# Railway injects PORT; default to 8080 if unset (matches Railway proxy).
PORT="${PORT:-8080}"
exec php -S "0.0.0.0:${PORT}" -t public public/server.php
