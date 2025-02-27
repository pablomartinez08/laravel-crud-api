# 🏀 Laravel CRUD API para Gestión de Club Deportivo

Este proyecto es una API REST desarrollada en Laravel 11 para la gestión de socios, reservas y pistas deportivas.

## 🚀 Características
- 📌 CRUD para Usuarios, Deportes, Socios, Reservas y Pistas
- 🔐 Autenticación con Laravel Sanctum
- 📖 Documentación con Swagger (`l5-swagger`)
- 📊 Base de datos con migraciones
- 📡 API RESTful con validaciones y controladores versionados

---

## 📂 Instalación

### **1️⃣ Clonar el repositorio**
```bash
git clone https://github.com/pablomartinez08/laravel-crud-api.git
cd laravel-crud-api

## 🛠 Configuración del entorno

Configurar la base de datos en .env:

Este proyecto usa PostgreSQL como base de datos.
La base de datos debe llamarse laravel.
Ajustar las credenciales en .env según tu configuración.

🛢️ Base de datos
Estructura y Datos Iniciales
📜 Los scripts de la base de datos (estructura.sql y datos_inicial.sql) se encuentran en la carpeta 'database' junto con el esquema entidad relación de la base de datos.

🔥 Pruebas con Postman
📌 Se incluye un archivo de exportación de una colección en Postman para probar todos los endpoints.
📌 Importante: La mayoría de las rutas están protegidas y requieren autenticación mediante un token.

Usar el token en la cabecera Authorization:
Authorization: Bearer <TOKEN>
Para obtener un token sin necesidad de crear un nuevo usuario podrías iniciar sesión con cualquier usuario introduciendo email y password, asi la respuesta te entregará un token. La password es 'clave123' para todos los usuarios creados.

📜 Documentación de la API
📌 El documento JSON con la especificación completa de la API Swagger se encuentra en:
laravel-crud-api\storage\api-docs\api-docs.json

📌 Con el proyecto en ejecución (php artisan serve), la documentación Swagger se puede consultar en:
🔗 http://localhost:8000/api/documentation
