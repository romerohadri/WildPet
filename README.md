# 🐾 WildPet - Tienda Online de Productos para Mascotas

## Descripción
WildPet es una tienda online completamente funcional para comprar productos para mascotas. Dispone de categorías para Perros, Gatos, Pájaros y Peces, con un sistema de carrito de compras y autenticación de usuarios.

---

## 📋 Requisitos Previos

- **PHP 7.0+** (con soporte para mysqli)
- **MySQL 5.7+**
- **Servidor web** (Apache, Nginx, etc.)
- **Control de versiones** (recomendado: Git)

---

## 🚀 Instalación

### 1. Preparar el entorno
Si estás en **macOS**, necesitas un servidor web local. Las opciones más comunes son:

- **Homebrew + PHP + MySQL**
- **Docker**
- **MAMP/XAMPP**

Para Mac recomendamos **Homebrew**:
```bash
brew install php mysql
mysql.server start
```

### 2. Crear la carpeta del proyecto
```bash
mkdir -p ~/Sites/wildpet
cd ~/Sites/wildpet
```

### 3. Copiar los archivos de WildPet
Coloca todos los archivos del proyecto en la carpeta anterior.

### 4. Crear la base de datos

#### Opción A: Usando phpMyAdmin
1. Abre phpMyAdmin en tu navegador
2. Ve a la pestaña "SQL"
3. Abre el archivo `init_database.sql` y copia su contenido
4. Pégalo en phpMyAdmin y ejecuta (botón "Ejecutar")

#### Opción B: Usando terminal MySQL
```bash
mysql -u root -p < init_database.sql
```

#### Opción C: Manualmente en MySQL
```bash
mysql -u root -p
```

Luego pega el contenido del archivo `init_database.sql` en la terminal.

### 5. Configurar la conexión
Verifica que `conexion.php` tenga los datos correctos:

```php
$host = "localhost";
$usuario = "root";
$contrasena = "";  // Modifica si tienes contraseña
$bd = "wildpet";
```

### 6. Configurar el servidor web

#### Si usas Apache:
Edita el archivo de configuración de Apache para que apunte a la carpeta de WildPet.

#### Si usas PHP incorporado:
```bash
cd ~/Sites/wildpet
php -S localhost:8000
```

---

## 🔐 Acceso a la Tienda

### Usuario de Prueba Incluido
- **Email:** `prueba@wildpet.com`
- **Contraseña:** `123456`

### URLs Principales
- **Inicio:** `http://localhost:8000/Homepage.php`
- **Productos Perros:** `http://localhost:8000/DogProducts.php`
- **Productos Gatos:** `http://localhost:8000/Gatos.php`
- **Productos Pájaros:** `http://localhost:8000/Pajaros.php`
- **Productos Peces:** `http://localhost:8000/Peces.php`
- **Carrito:** `http://localhost:8000/ShoppingCart.php`
- **Login:** `http://localhost:8000/Login.php`

---

## 📁 Estructura del Proyecto

```
WildPet_HadriánRomero/
├── Homepage.php           # Página principal
├── DogProducts.php        # Productos perros
├── Gatos.php              # Productos gatos
├── Pajaros.php            # Productos pájaros
├── Peces.php              # Productos peces
├── DetailProduct.php      # Detalle de un producto
├── ShoppingCart.php       # Carrito de compras
├── Login.php              # Autenticación
├── conexion.php           # Conexión a base de datos
├── init_database.sql      # Script SQL inicial
├── README.md              # Este archivo
├── *.css                  # Estilos CSS
├── img/                   # Imágenes generales
└── img2/                  # Imágenes de productos
```

---

## 🔧 Funcionalidades Principales

✅ **Catálogo de Productos** - Organizado por categorías (Perros, Gatos, Pájaros, Peces)  
✅ **Detalle de Producto** - Información completa con precio, descripción y stock  
✅ **Autenticación** - Sistema de login seguro con sesiones  
✅ **Carrito de Compras** - Gestión dinámica del carrito desde la base de datos  
✅ **Sistema de Base de Datos** - MySQL con relaciones bien definidas  
✅ **Responsivo** - Diseño adaptable a dispositivos móviles  
✅ **Interfaz Amigable** - Navegación intuitiva con iconos Font Awesome  

---

## 🛠️ Próximas Mejoras (Sugerencias)

- [ ] Sistema de registro de nuevos usuarios
- [ ] Procesamiento real de pagos (Stripe, PayPal)
- [ ] Gestión de ordenes y historial de compras
- [ ] Panel de administración para gestionar productos
- [ ] Sistema de búsqueda y filtros avanzados
- [ ] Carrito persistente (localStorage)
- [ ] Sistema de comentarios y reseñas
- [ ] Envíos y tracking de pedidos
- [ ] Notificaciones por email
- [ ] Sistema de cupones y descuentos

---

## 🐛 Resolución de Problemas

### Error: "Error de conexión: Connection refused"
**Solución:** Asegúrate de que MySQL está corriendo:
```bash
mysql.server start
```

### Error: "Base de datos no encontrada"
**Solución:** Ejecuta nuevamente el script `init_database.sql`

### Archivo no se carga (404)
**Solución:** Verifica que el archivo existe y que la URL es correcta. Recuerda cambiar `.html` por `.php`

### Login no funciona
**Solución:** 
1. Verifica que la base de datos esté creada y tenga usuarios
2. Comprueba que `conexion.php` está bien configurado
3. Asegúrate de que las sesiones están habilitadas en PHP

---

## 📝 Notas Importantes

- **Seguridad:** Este proyecto es educativo. Para producción, implementa medidas de seguridad adicionales (validación input, SQL injection prevention, CSRF tokens, etc.)
- **Contraseñas:** Las contraseñas están hasheadas con bcrypt
- **Base de datos:** Los datos de ejemplo se incluyen en `init_database.sql`
- **Imágenes:** Las rutas de imágenes apuntan a `img/` e `img2/`. Asegúrate de que existan

---

## 📞 Soporte

Si tienes problemas:
1. Revisa que todos los archivos `.php` estén en la misma carpeta
2. Verifica que `conexion.php` está configurado correctamente
3. Comprueba los permisos de carpetas
4. Revisa los logs de error del servidor

---

## 📄 Licencia

Proyecto educativo - Trabajo de Fin de Grado  
Autor: Hadrian Romero

---

**¡Disfruta de WildPet! 🐾**
