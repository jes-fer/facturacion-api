# 📄 Facturación API

REST API para gestión de facturas desarrollada con Laravel y MySQL.  
Diseñada con lógica de negocio real para facturación compatible con SAT México.

---

## 🚀 Tecnologías

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

---

## ✨ Características

- CRUD completo de clientes, productos y facturas
- Cálculo correcto de IVA con `round()` para evitar errores de punto flotante
- Generación automática de folios (FAC-0001, FAC-0002...)
- Transacciones DB — si algo falla, revierte todo
- Validaciones en todos los endpoints
- Las facturas no se eliminan, solo se cancelan (regla SAT)

---

## 📦 Instalación

```bash
git clone https://github.com/jes-fer/facturacion-api.git
cd facturacion-api
composer install
cp .env.example .env
php artisan key:generate
```

Configura tu base de datos en `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=facturacion_db
DB_USERNAME=root
DB_PASSWORD=
```

```bash
php artisan migrate
php artisan serve
```

---

## 🛠️ Endpoints

### Clientes
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/clientes` | Listar todos |
| POST | `/api/clientes` | Crear cliente |
| GET | `/api/clientes/{id}` | Ver uno |
| PUT | `/api/clientes/{id}` | Actualizar |
| DELETE | `/api/clientes/{id}` | Eliminar |

### Productos
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/productos` | Listar activos |
| POST | `/api/productos` | Crear producto |
| GET | `/api/productos/{id}` | Ver uno |
| PUT | `/api/productos/{id}` | Actualizar |
| DELETE | `/api/productos/{id}` | Desactivar |

### Facturas
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/facturas` | Listar todas |
| POST | `/api/facturas` | Crear factura |
| GET | `/api/facturas/{id}` | Ver una |
| PUT | `/api/facturas/{id}` | Cambiar estatus |
| DELETE | `/api/facturas/{id}` | No permitido |

---

## 📋 Ejemplo de uso

### Crear una factura

```json
POST /api/facturas
{
    "cliente_id": 1,
    "fecha": "2026-06-17",
    "productos": [
        {
            "id": 1,
            "cantidad": 2
        }
    ]
}
```

### Respuesta

```json
{
    "folio": "FAC-0001",
    "subtotal": "30000.00",
    "iva": "4800.00",
    "total": "34800.00",
    "estatus": "pendiente"
}
```

---

## 👨‍💻 Autor

**Fernando García**  
[LinkedIn](https://www.linkedin.com/in/fernando-garcía-76492b251) · [GitHub](https://github.com/jes-fer)
