```md
# WildPet - Tienda Online de Productos para Mascotas

## Descripción
WildPet es una tienda online funcional desarrollada como proyecto TFG, orientada a la venta de productos para mascotas.  
Incluye catálogo por categorías (Perros, Gatos, Pájaros y Peces), autenticación de usuarios, carrito de compra, favoritos, gestión de cuenta y registro de pedidos.

---

## Objetivo del proyecto
Diseñar e implementar una aplicación web ecommerce aplicando conocimientos de:
- Desarrollo backend con PHP
- Gestión de base de datos con MariaDB/MySQL
- Estructuración de interfaz con HTML/CSS
- Interacción en frontend con JavaScript
- Control de versiones con Git/GitHub

---

## Tecnologías utilizadas
- Backend: PHP (mysqli)
- Base de datos: MariaDB / MySQL
- Frontend: HTML, CSS, JavaScript
- Servidor local: XAMPP (Apache + MariaDB)
- Control de versiones: Git + GitHub

---

## Requisitos previos

### Hardware mínimo
- CPU de 2 núcleos
- 4 GB RAM
- 2 GB libres en disco

### Hardware recomendado
- CPU de 4 núcleos
- 8 GB RAM
- SSD

### Software
- XAMPP instalado (Apache + MariaDB + PHP)
- Navegador moderno (Chrome, Firefox, Edge)
- Git (opcional, recomendado)

---

## Instalación (XAMPP)

### 1) Colocar el proyecto en htdocs
Copia la carpeta del proyecto dentro de:

- macOS: `/Applications/XAMPP/xamppfiles/htdocs/wildpet`
- Windows: `C:\xampp\htdocs\wildpet`
- Linux: `/opt/lampp/htdocs/wildpet`

### 2) Iniciar servicios
Abre XAMPP y arranca:
- Apache
- MySQL (MariaDB)

### 3) Crear/importar base de datos
Entra en `http://localhost/phpmyadmin` y:

- Crea la base de datos `wildpet`
- Importa el archivo `init_database.sql`

### 4) Revisar conexión
Verifica en `conexion.php`:

```php
$host = "localhost";
$usuario = "root";
$contrasena = "";
$bd = "wildpet";
```

En algunos equipos Windows/Linux el usuario/contraseña puede variar según la configuración local de XAMPP.

### 5) Ejecutar proyecto
Abre en navegador:

- Inicio: `http://localhost/wildpet/Homepage.php`

---

## Acceso de prueba
Si en tu SQL hay usuario demo cargado:

- Email: `prueba@wildpet.com`
- Contraseña: `123456`

Si no funciona, revisa en la tabla `usuarios` de tu base de datos.

---

## URLs principales
- Inicio: `http://localhost/wildpet/Homepage.php`
- Perros: `http://localhost/wildpet/Perros.php`
- Gatos: `http://localhost/wildpet/Gatos.php`
- Pájaros: `http://localhost/wildpet/Pajaros.php`
- Peces: `http://localhost/wildpet/Peces.php`
- Detalle producto: `http://localhost/wildpet/DetailProduct.php?id=1`
- Buscar: `http://localhost/wildpet/Search.php`
- Carrito: `http://localhost/wildpet/ShoppingCart.php`
- Favoritos: `http://localhost/wildpet/MisFavoritos.php`
- Mi cuenta: `http://localhost/wildpet/MiCuenta.php`
- Pedidos: `http://localhost/wildpet/Pedidos.php`
- Login: `http://localhost/wildpet/Login.php`

---

## Estructura del proyecto

```text
wildpet/
├── Checkout.php
├── DetailProduct.css
├── DetailProduct.html
├── DetailProduct.php
├── DireccionEnvio.php
├── DogProducts.css
├── Gatos.php
├── Homepage.css
├── Homepage.html
├── Homepage.php
├── Login.css
├── Login.html
├── Login.php
├── Logout.php
├── MiCuenta.php
├── MisFavoritos.php
├── Pajaros.php
├── Peces.php
├── Pedidos.php
├── Perros.php
├── QUICK_START.md
├── README.md
├── Register.php
├── Search.php
├── ShoppingCart.css
├── ShoppingCart.html
├── ShoppingCart.php
├── account-dropdown-links.php
├── account-menu.css
├── account-menu.js
├── add_favorites_to_cart.php
├── add_to_cart.php
├── base de datos tabla categorías
├── conexion.php
├── csrf.php
├── favorites.js
├── footer.php
├── img/
├── img2/
└── init_database.sql
```

---

## Funcionalidades implementadas
- Catálogo por categorías
- Vista de detalle de producto
- Sistema de búsqueda y filtros
- Login, registro y cierre de sesión
- Carrito de compra (añadir, editar cantidad, eliminar)
- Favoritos y traspaso al carrito
- Mi Cuenta (datos personales, facturación y envío)
- Proceso de compra con dirección de envío
- Confirmación y guardado de pedidos en BD
- Historial de pedidos del usuario
- Diseño responsive básico

---

## Flujo de uso recomendado
1. Navegar por categorías.
2. Ver detalle y añadir productos.
3. Marcar favoritos (opcional).
4. Ir al carrito y tramitar pedido.
5. Introducir dirección de envío.
6. Confirmar compra.
7. Consultar pedidos en el perfil.

---

## Resolución de problemas

### Error de conexión a BD
- Verifica que MySQL esté iniciado en XAMPP.
- Revisa `conexion.php`.
- Comprueba que la base `wildpet` exista.

### Página en blanco
- Activa logs de PHP en XAMPP y revisa errores.
- Asegúrate de no tener código roto al final de archivos `.php`.

### 404 en rutas
- Comprueba que usas URLs con `.php`.
- Verifica nombre exacto de archivo (ej: `Perros.php`, no `DogProducts.php`).

### Sesión/login no mantiene estado
- Comprueba `session_start();` al inicio de cada página protegida.
- Borra caché/cookies y prueba de nuevo.

---

## Nota de seguridad
Este proyecto es académico. Para entorno real se recomienda:
- Validación y saneado más estricto de inputs
- Protección CSRF
- Gestión robusta de errores
- Control de permisos por rol
- Configuración segura de servidor y sesiones

---

## Licencia
Proyecto Intermodular - TFG DAW 2º Curso.

---

## Autor
Hadrián Romero  Abelleira
Repositorio: `https://github.com/romerohadri/WildPet`
```