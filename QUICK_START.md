# 🚀 GUÍA RÁPIDA - WildPet

## En 3 Pasos

### 1️⃣ Crear la Base de Datos
```bash
# En terminal o SSH:
mysql -u root -p < init_database.sql

# O en phpMyAdmin:
# - Ir a "SQL"
# - Copiar contenido de init_database.sql
# - Ejecutar
```

### 2️⃣ Iniciar el Servidor
```bash
cd ~/Desktop/TFG/WildPet_HadriánRomero\ 2
php -S localhost:8000
```

### 3️⃣ Acceder a la Tienda
```
http://localhost:8000/Homepage.php

Login: prueba@wildpet.com / 123456
```

---

## 📂 Archivos Principales

| Archivo | Descripción |
|---------|------------|
| Homepage.php | Página inicial |
| DogProducts.php | Productos para perros |
| Gatos.php | Productos para gatos |
| Pajaros.php | Productos para pájaros |
| Peces.php | Productos para peces |
| DetailProduct.php | Detalle de un producto |
| ShoppingCart.php | Carrito de compras |
| Login.php | Autenticación de usuario |
| conexion.php | Conexión a base de datos |
| init_database.sql | Script de base de datos |

---

## ✅ ¿Qué Está Funcionando?

- ✅ Navegación entre categorías (todos los links son .php)
- ✅ Visualización dinámica de productos desde BD
- ✅ Detalle de producto con información de BD
- ✅ Sistema de login con sesiones
- ✅ Carrito que consulta tabla `carrito` de BD
- ✅ Cálculo de totales y envío automático

---

## 🔧 Configuración

El archivo `conexion.php` ya está configurado:
```php
$host = "localhost";
$usuario = "root";
$contrasena = "";  // Si tienes contraseña, agrégala aquí
$bd = "wildpet";
```

---

## 🎯 Pruebas Recomendadas

1. Ir a Homepage.php
2. Hacer clic en "Perros"
3. Hacer clic en cualquier producto
4. Ir a Login para autenticarse
5. Probar carrito (vacío si no hay datos en tabla carrito)

---

## 💡 Datos de Prueba

| Campo | Valor |
|-------|-------|
| Email | prueba@wildpet.com |
| Contraseña | 123456 |

---

## ❌ Si Algo No Funciona

1. **"MySQL no encontrado"** → Reinicia MySQL: `mysql.server restart`
2. **"Database doesn't exist"** → Ejecuta init_database.sql
3. **Página en blanco** → Revisa logs: `php -S localhost:8000` (verás errores)
4. **Links rotos** → Asegúrate que todos sean .php (no .html)

---

## 📞 Contacto

Si tienes problemas, consulta el README.md completo en la carpeta del proyecto.

**¡Listo para usar! 🐾**
