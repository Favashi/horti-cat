# Horti-CAT

![Horti-CAT Logo](https://via.placeholder.com/150)

> **Nota:** Este es un proyecto de API para la gestión y automatización de huertos urbanos. La imagen del logo es DUMMY y se actualizará en el futuro.

[![Build Status](https://img.shields.io/github/actions/workflow/status/tu-usuario/mi-proyecto-api/ci.yml?branch=main)](https://github.com/tu-usuario/mi-proyecto-api/actions)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![Docker](https://img.shields.io/badge/Docker-ready-blue)](https://www.docker.com/)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%20%2B-blue)](https://www.php.net/)

---

## Descripción

**Horti-CAT** es la solución integral para la gestión y automatización de huertos urbanos. Construido sobre el [Slim Framework](https://www.slimframework.com/) y siguiendo los estándares PSR-7, Horti-CAT integra herramientas modernas para ofrecer una API robusta y escalable, ideal para integraciones IoT y aplicaciones comerciales.

**Características Clave:**
- **Guzzle:** Cliente HTTP para consumir APIs externas.
- **Monolog:** Registro estructurado de logs.
- **PHP Dotenv:** Gestión de variables de entorno.
- **Eloquent ORM (Illuminate/Database):** Interacción con la base de datos.
- **Swagger-PHP:** Documentación de la API según OpenAPI.
- **PHPUnit:** Ejecución de pruebas unitarias e integradas.
- **Firebase/php-jwt:** Creación y validación de tokens JWT para autenticación.
- **PHP-DI:** Inyección de dependencias.

> [!TIP]  
> **Horti-CAT** sigue una arquitectura hexagonal/DDD que separa el dominio, los casos de uso, la infraestructura y las interfaces. Esto facilita el mantenimiento, testeo y escalabilidad del sistema.

---

## Tabla de Contenidos

- [Requisitos](#requisitos)
- [Instalación y Setup](#instalaci%C3%B3n-y-setup)
- [Ejecución del Entorno de Desarrollo](#ejecuci%C3%B3n-del-entorno-de-desarrollo)
- [Makefile](#makefile)
- [Ejecución de Tests](#ejecuci%C3%B3n-de-tests)
- [Dependencias del Proyecto](#dependencias-del-proyecto)
- [Checklist Funcionalidades](#checklist-funcionalidades)
- [Contribuciones](#contribuciones)
- [Despliegue en Producción](#despliegue-en-producci%C3%B3n)
- [Licencia](#licencia)

---

## Requisitos

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Git](https://git-scm.com/)
- (Opcional) [Make](https://www.gnu.org/software/make/)
- (Opcional) [PHPStorm](https://www.jetbrains.com/phpstorm/) u otro IDE

---

## Instalación y Setup

1. **Clonar el Repositorio:**

   ```bash
   git clone https://github.com/tu-usuario/mi-proyecto-api.git
   cd mi-proyecto-api
   ```

2. **Configurar Variables de Entorno:**

   Renombra `.env.example` a **.env** y ajusta los valores (por ejemplo, `JWT_SECRET`, configuración de base de datos, etc.).

3. **Construir las Imágenes Docker:**

   ```bash
   make build
   ```

4. **Levantar el Entorno de Desarrollo:**

   ```bash
   make up
   ```

5. **Instalar Dependencias de Composer:**

   ```bash
   make composer-install
   ```

---

## Ejecución del Entorno de Desarrollo

- La API se ejecuta mediante un contenedor Nginx que expone el puerto **8080**.  
  Accede a la aplicación en: [http://localhost:8080](http://localhost:8080)

- **Estructura del Proyecto:**
    - **public/**: Front controller (index.php) y archivos estáticos.
    - **src/**: Código fuente (Dominio, Aplicación, Infraestructura, Interfaces).
    - **config/**: Archivos de configuración (dependencies, middleware, routes).

- Para abrir una shell en el contenedor PHP:

  ```bash
  make exec-php
  ```

---

## Makefile

El proyecto incluye un Makefile para simplificar las tareas comunes:

- **build**:  
  Construye las imágenes Docker.
  ```bash
  make build
  ```

- **up**:  
  Levanta los contenedores en segundo plano.
  ```bash
  make up
  ```

- **down**:  
  Detiene y elimina los contenedores.
  ```bash
  make down
  ```

- **restart**:  
  Reinicia los contenedores.
  ```bash
  make restart
  ```

- **composer-install**:  
  Instala las dependencias de Composer dentro del contenedor PHP.
  ```bash
  make composer-install
  ```

- **composer-dump-autoload**:  
  Regenera el autoload de Composer.
  ```bash
  make composer-dump-autoload
  ```

- **exec-php**:  
  Abre una shell interactiva en el contenedor PHP.
  ```bash
  make exec-php
  ```

- **test**:  
  Ejecuta todos los tests con PHPUnit.
  ```bash
  make test
  ```

- **test-unit**:  
  Ejecuta la suite de tests unitarios.
  ```bash
  make test-unit
  ```

- **test-integration**:  
  Ejecuta la suite de tests de integración.
  ```bash
  make test-integration
  ```

- **openapi**:  
  Genera la documentación OpenAPI (Swagger) para la API.
  ```bash
  make openapi
  ```

- **help**:  
  Muestra la lista de comandos y su descripción.
  ```bash
  make help
  ```

---

## Ejecución de Tests

El proyecto utiliza **PHPUnit** para asegurar la calidad del código. Los tests se dividen en:

- **Tests Unitarios**: (en `tests/Unit/`)
- **Tests de Integración**: (en `tests/Integration/`)

Para ejecutar todos los tests:

```bash
make test
```

Para ejecutar solo los tests unitarios:

```bash
make test-unit
```

Para ejecutar solo los tests de integración:

```bash
make test-integration
```

---

## Dependencias del Proyecto

- **Slim Framework 4.x**
- **Guzzle**
- **Monolog**
- **PHP Dotenv**
- **Eloquent ORM (Illuminate/Database)**
- **Swagger-PHP**
- **PHPUnit**
- **Firebase/php-jwt**
- **PHP-DI**
- *(Opcional)* **Mockery** (para mocks, si se prefiere sobre PHPUnit nativo)

---

## Checklist Funcionalidades

- [ ] **Autenticación y Seguridad:**
    - Endpoint `/auth/login` para obtener un token JWT.
    - Middleware JWT que protege endpoints bajo `/api/*`.
- [ ] **Documentación y Soporte:**
    - Documentación generada con Swagger-PHP, visible en `/docs`.
- [ ] **Monitorización:**
    - Health endpoint (`/health`) para comprobar el estado de la API.
    - Metrics endpoint (`/metrics`) para exponer métricas (por ejemplo, Prometheus).
- [ ] **Gestión de Errores:**
    - Manejador de errores centralizado que sigue el estándar RFC 7807.
- [ ] **Versionado de la API**
- [ ] **Registro de Logs y Auditoría**
- [ ] **Rate Limiting y Optimización**

---

## Contribuciones

¡Las contribuciones son bienvenidas!

- **Fork** el repositorio.
- Crea una nueva rama:
  ```bash
  git checkout -b feature/nueva-funcionalidad
  ```
- Realiza tus cambios y haz commits descriptivos.
- Envía un **Pull Request** para revisión.

---

## Despliegue en Producción

Pasos para desplegar:

1. **Configurar el entorno:**  
   Actualiza el archivo **.env** para producción.
2. **Construir imágenes optimizadas:**  
   Utiliza `docker-compose.prod.yml`:
   ```bash
   docker-compose -f docker-compose.prod.yml build
   ```
3. **Desplegar en un servidor:**  
   Considera servicios compatibles con Docker (AWS, DigitalOcean, Heroku).
4. **Integrar CI/CD:**  
   Configura pipelines (p.ej., GitHub Actions) para automatizar pruebas y despliegues.

---

## Notas Adicionales

- **Arquitectura:**  
  Horti-CAT sigue un patrón modular basado en **Slim-Skeleton** y una arquitectura hexagonal/DDD:
    - **Dominio:** Entidades y reglas de negocio.
    - **Aplicación:** Casos de uso.
    - **Infraestructura:** Implementaciones concretas.
    - **Interfaces:** Adaptadores de entrada (controladores HTTP).
- **Documentación:**  
  La API se documenta con **Swagger-PHP** y se puede visualizar con **Swagger UI** (en `/docs`).
- **Manejo de Errores:**  
  Se implementa un error handler centralizado que devuelve respuestas siguiendo el estándar RFC 7807.
- **Seguridad:**  
  Endpoints públicos (p.ej., `/auth/login`, `/health`, `/metrics`) y endpoints protegidos (bajo `/api`).

---

## Licencia

Este proyecto se distribuye bajo la licencia [MIT](LICENSE).

---

> **Recuerda:**
> - Mantén actualizadas las dependencias y la documentación del proyecto.
> - Revisa las pruebas regularmente para asegurarte de que los cambios en la lógica de negocio no rompan el contrato de la API.
> - ¡Buena suerte y feliz codificación!