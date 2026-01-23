<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

📋 Descripción del Proyecto
HouseCare es un sistema integral de gestión para centros ecuestres que combina la administración de inventario de productos/servicios con el control y seguimiento completo de caballos. Desarrollado con Laravel, proporciona una solución robusta para la gestión diaria de establos, inventarios y cuidados equinos.

 # 🏗️ Arquitectura del Sistema
## Módulos Principales
### 1. 🏷️ Gestión de Categorías y Productos
- Categorías: Clasificación de productos (supply, shopping, service)

- Unidades: Sistema de conversión entre unidades de medida

- Productos/Servicios: Gestión completa con conversiones automáticas

- Inventario: Control de existencias por almacén

## 2. 🐴 Gestión Equina
- Caballos: Registro completo con historial médico

- Establos y Sectores: Sectorización inteligente con gestión de espacios

- Movimientos: Tracking de ubicaciones (rotación, entrenamiento, competencias)

- Asignaciones Especiales: Control de estados (entrenamiento, veterinaria, herraje)

## 3. 📦 Sistema de Inventario
### Operaciones: Entradas, salidas, pérdidas, daños, transferencias

- Stock: Control por almacén con conversiones automáticas

- Suministros: Asignación de productos a caballos por horarios (AM/PM)

## 4. ⚙️ Servicios y Asignaciones
- Servicios: Catálogo de servicios ecuestres

- Asignaciones Especiales: Programación de actividades

- Tarjetas de Caballo: Vista consolidada por sector


## 🗄️ ESTRUTURA DE BASE DE DATOS
- Tablas Principales
sql
### ESTRUCTURA BASE:
categories           Categorías de productos
units                Unidades de medida
unit_conversions     Conversiones entre unidades
products             Productos y servicios
stores               Almacenes/depósitos
stocks               Existencias por almacén

### MÓDULO EQUINO
horses               Registro de caballos
sectors              Sectorización de instalaciones
stables              Establos y paddocks
horse_movements      Historial de movimientos
horse_special_conditions  Condiciones especiales

### INVENTARIO
inventory_operations     Operaciones de inventario
inventory_controls       Control detallado

### SUMINISTROS
supplies                 Asignaciones de suministro
supplie_details          Detalles por horario

### SERVICIOS
services                 Catálogo de servicios
assignments              Asignaciones de servicios
assignment_details       Detalles de asignaciones

### MOVIMIENTOS
movements                Asignaciones de movimiento
movement_details         Ejecución de movimientos


## 🚀 Características Técnicas
### 🔧 Tecnologías Implementadas
--------------------------------------
- Backend: Laravel 10+ con PHP 8.1+

- Frontend: Blade, Bootstrap 5, JavaScript

- Base de Datos: MySQL


# Servicios: Arquitectura por capas con Services y Observers

## 🔄 Flujos Automatizados

### 1. Sistema de Inventario Inteligente

- Observers: 
Actualización automática de stock

- Services: 
Conversión de unidades en tiempo real

- Validaciones: 
Prevención de movimientos inválidos

### 2. Gestión de Suministros Automática
- Reseteo Diario: 
Comandos programados para resetear estados

- Control por Horario: 
AM/PM con límites de fecha

- Restas Automáticas: 
Actualización de existencias al ejecutar

### 3. Movimientos de Caballos
- Asignación-Ejecución: 
Flujo DIRECTOR → MANAGER

- Tracking en Tiempo Real: 
Ubicación actual automática

- Validación de Capacidad: 
Prevención de sobrepoblación

## 📊 Funcionalidades Clave
### ✅ Módulo de Productos
- Gestión completa de productos y servicios

- Sistema dual de unidades (compra/consumo)

- Conversiones automáticas

- Control de mínimos y máximos

- Códigos de barras y fotografías

### ✅ Módulo Equino

- Ficha técnica completa por caballo

- Historial médico y vacunación

- Control de condiciones especiales

- Sectorización visual con colores

- Tarjetas por sector

### ✅ Sistema de Movimientos

- Asignación por roles (DIRECTOR/MANAGER)

- Tipos:
  SALIDA, RETORNO, MOVIMIENTO

- Estados: 
  ASIGNADO, EJECUTADO

- Ejecución con actualización automática de ubicación

### ✅ Dashboard y Reportes
- Vista consolidada por sectores

- Alertas de inventario bajo

- Control de suministros pendientes

- Historial de movimientos

- Estadísticas de uso

## 🛠️ Configuración e Instalación
### Requisitos Previos
bash
PHP >= 8.1
Composer 2.5+
MySQL 5.7+
Node.js 16+
Instalación
bash

## 1. Clonar repositorio
git clone https://github.com/maytech76/housercare.git

## 3. Configurar entorno
cp .env.example .env
php artisan key:generate

## 4. Configurar base de datos en .env
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

## 5. Migrar y sembrar datos
php artisan migrate --seed

## 6. Compilar assets
npm run build

## 7. Configurar tareas programadas (crontab)
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1

## Comandos Personalizados
bash

## Resetear suministros diariamente
php artisan supplies:reset-status

## Generar números correlativos
# Automático en modelos

## Ejecutar pruebas
php artisan test


## 🔐 Roles y Permisos
### 👑 Director
- Crear productos/servicios

- Asignar suministros

- Programar movimientos

- Asignar condiciones especiales

- Ver todos los reportes

### 👨‍💼 Manager
- Ejecutar suministros asignados (AM/PM)

- Ejecutar movimientos asignados (AM/PM)

- Ejecutar servicios asignados (AM/PM)

- Actualizar estados

Control diario

### 👨‍🔧 Staff
- Ver asignaciones

- Reportar novedades

- Consultar inventario

- Ver ubicaciones

## ⚡ Optimizaciones Implementadas
### 🔄 Patrones de Diseño
- Service Layer: Separación de lógica de negocio

- Observer Pattern: Automatización de procesos

- Repository Pattern (parcial): Organización de consultas

- Dependency Injection: Mejor testabilidad

## 🚀 Performance
- Eager Loading: Relaciones optimizadas

- Caching: Datos frecuentes en caché

- Queue Ready: Tareas en segundo plano

- Pagination: Listados optimizados

## 🛡️ Seguridad
- CSRF Protection: Protección integrada

- XSS Prevention: Escapado automático

- SQL Injection: Eloquent ORM

- Validación: Request classes

## 📈 Próximas Mejoras
### 🔄 En Desarrollo

- Dashboard interactivo con gráficos

- Notificaciones en tiempo real

- App móvil para gestión en campo


## 🚀 Planeadas
### Sistema de facturación

### Reservas en línea

### Geolocalización de movimientos

### Reportes analíticos avanzados

## 🤝 Contribución
Fork el proyecto

Crear rama de características (git checkout -b feature/AmazingFeature)

Commit cambios (git commit -m 'Add AmazingFeature')

Push a la rama (git push origin feature/AmazingFeature)

Abrir Pull Request

📄 Licencia
Este proyecto está bajo licencia MIT. Ver archivo LICENSE para más detalles.

## 👥 Contacto
### Desarrollador Principal - @maytech76 (Marco Antonio Yanez )

Proyecto: https://github.com/maytech76/housercare


Versión: 1.1.0
Última Actualización: Enero 2026
Estado: En Desarrollo Activo
Compatibilidad: Laravel 10.x, PHP 8.1+ Mysql 8.0, Bootstrap 5