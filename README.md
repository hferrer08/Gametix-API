# 🎮 Gametix-API

API REST desarrollada con **Laravel 12 + Sanctum + MySQL (Docker/Sail)**
para un sistema tipo e-commerce enfocado en gestión de productos,
proveedores, stock, pedidos, carrito y lista de deseos.

Este proyecto forma parte del proceso formativo del Máster en Desarrollo
Web (VIU) y está diseñado bajo buenas prácticas REST, autenticación por
token y arquitectura modular.

------------------------------------------------------------------------

# 🚀 Tecnologías Utilizadas

-   PHP \^8.2
-   Laravel 12
-   Laravel Sanctum (Autenticación por token)
-   MySQL 8 (Docker)
-   Laravel Sail
-   Redis (incluido en Sail)
-   Docker Compose

------------------------------------------------------------------------

# 🔗 Integración con Frontend

Esta API se conecta con el frontend desarrollado en Angular:

👉 https://github.com/jotaefepece/VIU-Gametix-Frontend

El frontend consume los endpoints REST protegidos por Sanctum y gestiona
autenticación, carrito, pedidos y catálogo de productos.

------------------------------------------------------------------------

# 📦 Instalación (Laravel Sail)

## 1️⃣ Clonar repositorio

git clone https://github.com/hferrer08/Gametix-API.git cd Gametix-API

## 2️⃣ Copiar archivo .env

cp .env.example .env

Configurar base de datos para Sail:

DB_CONNECTION=mysql\
DB_HOST=mysql\
DB_PORT=3306\
DB_DATABASE=gametix\
DB_USERNAME=sail\
DB_PASSWORD=password

## 3️⃣ Instalar dependencias

composer install

## 4️⃣ Levantar entorno Docker

./vendor/bin/sail up -d

## 5️⃣ Generar key y migrar

./vendor/bin/sail artisan key:generate\
./vendor/bin/sail artisan migrate

## 6️⃣ (Opcional) Seeders

./vendor/bin/sail artisan db:seed

Base URL API: http://localhost/api

------------------------------------------------------------------------

# 🔐 Autenticación (Sanctum)

## Registro

POST /api/auth/register

## Login

POST /api/auth/login

## Usuario autenticado

GET /api/me

## Logout

POST /api/auth/logout

Headers requeridos:

Authorization: Bearer `<TOKEN>`{=html}\
Accept: application/json\
Content-Type: application/json

------------------------------------------------------------------------

# 🧩 Módulos Principales

## Categorías

/api/categories

## Compañías

/api/companias

## Productos

/api/products

## Proveedores

/api/proveedores

## Movimiento de Stock

/api/movimiento-stock

## Pedidos + Detalles

/api/pedidos\
/api/pedidos/{id}/detalles

## Pagos

/api/pagos

## Reseñas

/api/resenas

## Carrito + Items

/api/carritos\
/api/carritos/{id}/items

## Lista de Deseos

/api/lista-deseos\
/api/lista-deseos/{id}/productos

## Estados

/api/estados

------------------------------------------------------------------------

# 🧠 Características Implementadas

✔ CRUD completos\
✔ Relaciones muchos a muchos (Proveedor ↔ Producto)\
✔ Manejo de stock mediante movimientos\
✔ Carrito persistente por usuario\
✔ Pedidos con detalle\
✔ Soft delete y reactivación en múltiples módulos\
✔ Autenticación basada en token\
✔ Endpoints protegidos con auth:sanctum

------------------------------------------------------------------------

# 🧪 Comandos Útiles

./vendor/bin/sail artisan route:list\
./vendor/bin/sail artisan migrate\
./vendor/bin/sail artisan optimize:clear\
./vendor/bin/sail artisan test

------------------------------------------------------------------------

# 📈 Próximas Mejoras

-   Documentación Swagger / OpenAPI\
-   Versionado de API (/api/v1)\
-   Tests de integración\
-   Roles y permisos\
-   CI/CD

------------------------------------------------------------------------

# 👨‍💻 Autores

-   Hubert Ferrer 
-   José Poblete
-   Eduardo Criollo

------------------------------------------------------------------------

# 📄 Licencia

Proyecto académico con fines educativos.
