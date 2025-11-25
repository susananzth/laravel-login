#!/bin/sh
set -e

## El archivo debe ser ejecutable: chmod +x docker-entrypoint.sh

# Función para imprimir con timestamp (buena práctica para logs)
log() {
    echo "[$(date +'%Y-%m-%d %H:%M:%S')] $1"
}

log "🚀 Iniciando contenedor para entorno: $APP_ENV"

# ---------------------------------------------------------
# 1. WAIT-FOR-IT (Estrategia de espera de Base de Datos)
# ---------------------------------------------------------
# Intentamos conectar a la BD cada segundo hasta que responda o pasen 60s.
# Usamos un script php ligero inline para no depender de netcat o herramientas externas.
log "⏳ Esperando a la base de datos..."

TIMEOUT=60
COUNTER=0

while ! php -r "try { new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); } catch (PDOException \$e) { exit(1); }" > /dev/null 2>&1; do
    if [ $COUNTER -ge $TIMEOUT ]; then
        log "❌ Error: La base de datos no respondió después de $TIMEOUT segundos."
        exit 1
    fi
    log "💤 Esperando a la BD ($COUNTER/$TIMEOUT)..."
    sleep 2
    COUNTER=$((COUNTER+1))
done

log "✅ Conexión a Base de Datos exitosa."

# ---------------------------------------------------------
# 2. Lógica para PRODUCCIÓN
# ---------------------------------------------------------
if [ "$APP_ENV" = "production" ] || [ "$APP_ENV" = "prod" ]; then
    log "🏭 Configurando para PRODUCCIÓN..."

    # a. Cacheamos configuración, eventos, rutas y vistas
    # Esto acelera Laravel drásticamente al evitar leer el disco en cada request
    php artisan config:cache
    php artisan event:cache
    php artisan route:cache
    php artisan view:cache

    # b. Ejecutamos migraciones
    # --force: Necesario en producción para saltar la confirmación "Are you sure?"
    # --isolated: Evita que dos contenedores migren al mismo tiempo si escalas horizontalmente
    log "Deseas ejecutar migraciones..."
    php artisan migrate --force --isolated

    log "✅ Optimización y Migración completada."

# ---------------------------------------------------------
# 3. Lógica para DESARROLLO / LOCAL
# ---------------------------------------------------------
else
    log "🛠️  Configurando para DESARROLLO..."

    # En desarrollo NO cacheamos nada. Queremos ver los cambios al instante.
    # Si existía caché previa (de una imagen vieja), la borramos.
    php artisan optimize:clear

    # php artisan migrate --force
    # php artisan db:seed

    log "✅ Entorno de desarrollo listo."
fi

# ---------------------------------------------------------
# 4. Ejecutar el comando principal (PHP-FPM)
# ---------------------------------------------------------
log "🏁 Ejecutando comando principal..."
exec "$@"
