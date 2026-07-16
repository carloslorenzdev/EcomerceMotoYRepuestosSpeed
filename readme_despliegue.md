# Bitácora de Despliegue en Servidor Staging (motosspeed.com)

Esta bitácora documenta de manera detallada todos los comandos ejecutados en el VPS `2.24.109.195` para montar el e-commerce de pruebas de forma aislada y sin afectar la producción (`motosspeed.cl`).

---

## Estructura del Servidor y Decisiones Tomadas

1. **Aislamiento absoluto:**
   - La aplicación de producción en uso corre sobre un servidor **React Router (NodeJS)** derivado al puerto `3000` (`/var/www/MotoSpeed`).
   - El e-commerce nuevo corre en **Laravel (PHP 8.4)**. Se desplegó en una carpeta independiente: `/var/www/motosspeed-staging`.
2. **Base de Datos SQLite para Pruebas:**
   - De acuerdo a tu solicitud, se configuró una base de datos local en SQLite (`database.sqlite`) conteniendo todas las tablas y datos de prueba locales migrados para no tocar PostgreSQL de producción en esta fase.

---

## Historial de Comandos Ejecutados en el VPS

### 1. Actualización de Repositorios del Sistema
* **Comando:** `apt-get update`
* **Para qué sirve:** Descarga la lista actualizada de los paquetes de software disponibles desde los servidores oficiales de Ubuntu.
* **Qué hizo:** Indexó las últimas versiones de software para el sistema operativo Ubuntu 24.04 (Noble).

---

### 2. Adición del Repositorio de PHP (PPA Ondrej)
* **Comando:** `add-apt-repository -y ppa:ondrej/php`
* **Para qué sirve:** Añade el repositorio PPA oficial y más estable mantenido por Ondřej Surý, permitiendo instalar cualquier versión de PHP (incluida la versión 8.4) en Ubuntu.
* **Qué hizo:** Habilitó el acceso para instalar PHP 8.4 y sus extensiones específicas en el servidor, las cuales son requeridas por las dependencias de Laravel 11/12.

---

### 3. Instalación de PHP 8.4 y Extensiones Requeridas
* **Comando:** `apt-get install -y php8.4-cli php8.4-fpm php8.4-mbstring php8.4-xml php8.4-bcmath php8.4-curl php8.4-zip php8.4-sqlite3 php8.4-pgsql unzip`
* **Para qué sirve:** Instala el intérprete de comandos de PHP 8.4, el procesador FastCGI para servidores Nginx (FPM), y módulos necesarios para compresión, conexión a bases de datos SQLite y PostgreSQL, manejo de URLs, y cadenas de caracteres multibyte.
* **Qué hizo:** Levantó el servicio `php8.4-fpm` en el socket `/var/run/php/php8.4-fpm.sock` e instaló los binarios necesarios.

---

### 4. Instalación Global de Composer (Gestor de Dependencias PHP)
* **Comando:** `curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer`
* **Para qué sirve:** Descarga e instala el binario ejecutable de Composer de forma global en la ruta de binarios del sistema `/usr/local/bin`.
* **Qué hizo:** Permitió el uso de la herramienta `composer` desde cualquier directorio de la terminal para gestionar dependencias PHP.

---

### 5. Transferencia y Carga de Archivos de la Aplicación
* **Acción:** Transferencia recursiva mediante protocolo SFTP excluyendo carpetas innecesarias para producción (`.git`, `node_modules`, `vendor`, `.gemini`).
* **Para qué sirve:** Permite transferir el código fuente modificado, optimizado y precompilado directamente al VPS sin requerir la configuración de credenciales privadas de GitHub en el servidor remoto.
* **Qué hizo:** Copió la versión más reciente de la aplicación con la integración de Google Login y Mercado Pago a `/var/www/motosspeed-staging`.

---

### 6. Instalación de Dependencias de la Aplicación en Staging
* **Comando:** `cd /var/www/motosspeed-staging && composer install --no-dev --optimize-autoloader`
* **Para qué sirve:** Instala todos los paquetes de PHP definidos en `composer.lock` en su versión optimizada de producción (sin instalar herramientas de desarrollo).
* **Qué hizo:** Descargó y configuró internamente componentes clave como Laravel Framework, Laravel Socialite (Google Login), Livewire y Flux en el servidor.

---

### 7. Configuración de Entorno `.env` y Base de Datos SQLite
* **Acción:** Se subió el archivo de configuración `.env` a `/var/www/motosspeed-staging/.env` mapeando:
  - `DB_CONNECTION=sqlite`
  - `DB_DATABASE=/var/www/motosspeed-staging/database/database.sqlite`
  - `GOOGLE_REDIRECT_URI=https://motosspeed.com/auth/google/callback`
  - URLs de Mercado Pago apuntando dinámicamente a `https://motosspeed.com`.
* **Para qué sirve:** Define las variables de entorno exclusivas para el servidor de staging.
* **Qué hizo:** Conectó la app a la base de datos SQLite sincronizada.

---

### 8. Configuración de Permisos en el Servidor
* **Comandos:**
  - `touch /var/www/motosspeed-staging/database/database.sqlite`
  - `chmod -R 777 /var/www/motosspeed-staging/storage /var/www/motosspeed-staging/bootstrap/cache /var/www/motosspeed-staging/database`
  - `chown -R www-data:www-data /var/www/motosspeed-staging/storage /var/www/motosspeed-staging/bootstrap/cache /var/www/motosspeed-staging/database`
* **Para qué sirve:** Crea el archivo de base de datos física y otorga permisos de lectura/escritura al usuario de Nginx (`www-data`) para que pueda guardar sesiones, caché y escribir en la base de datos sqlite.
* **Qué hizo:** Previno errores de permisos (500) en el flujo de la aplicación.

---

### 9. Limpieza de Caché de Configuración
* **Comando:** `php artisan config:clear && php artisan cache:clear`
* **Para qué sirve:** Borra cualquier caché residual de configuraciones previas para obligar a Laravel a leer el archivo `.env` recién cargado.
* **Qué hizo:** Aplicó los cambios de rutas y base de datos de manera inmediata.

---

### 10. Configuración del Servidor Web Nginx
* **Acción:** Se creó el archivo de bloque de servidor `/etc/nginx/sites-available/motosspeed-staging` y se enlazó a `/etc/nginx/sites-enabled/`.
* **Comando:** `ln -sf /etc/nginx/sites-available/motosspeed-staging /etc/nginx/sites-enabled/motosspeed-staging && nginx -t && systemctl reload nginx`
* **Para qué sirve:** Habilita el hosting virtual en Nginx para que escuche peticiones hacia el dominio `motosspeed.com` en el puerto 80, valide la sintaxis y recargue el servidor web de forma segura sin interrumpir el sitio de producción.
* **Qué hizo:** Expuso la carpeta `/var/www/motosspeed-staging/public` bajo el dominio `motosspeed.com` para peticiones HTTP.

---

## Nota sobre el Certificado SSL (HTTPS) y DNS

Intentamos activar el certificado SSL seguro (HTTPS) ejecutando:
`certbot --nginx -d motosspeed.com -d www.motosspeed.com`

Sin embargo, el comando falló debido a que el dominio **`motosspeed.com`** actualmente apunta en sus registros DNS (A Record) a la dirección IP de Hostinger (`2.57.91.91`), no a la IP de tu VPS (`2.24.109.195`).

### Qué debes hacer para habilitar el dominio de pruebas:
1. Accede al panel de control de tu dominio `motosspeed.com` en Hostinger.
2. Modifica el **Registro A** (o crea uno nuevo si no existe) para que apunte a la IP de tu VPS: `2.24.109.195`.
3. Espera unos minutos a que se propague el cambio DNS.
4. Conéctate a tu VPS y ejecuta el comando de SSL:
   ```bash
   certbot --nginx -d motosspeed.com -d www.motosspeed.com
   ```
   *Esto habilitará el HTTPS de forma 100% gratuita y automática.*
