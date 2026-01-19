<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

<p align="center">
    <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status">
    <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads">
    <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
    <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
</p>

# Sistema de Inventario en Laravel

## Descripción del Proyecto

Este proyecto es un sistema de gestión de inventarios desarrollado con el framework Laravel, cuyo objetivo principal es administrar, controlar y dar seguimiento a los productos almacenados dentro de una organización o negocio.

La aplicación permite llevar un control ordenado de los artículos disponibles, registrar entradas y salidas, así como mantener actualizada la información relacionada con existencias, categorías y estados de los productos. De esta manera, el sistema ayuda a optimizar la administración del inventario y a reducir errores en el manejo manual de la información.

## Funcionalidades Principales

- Registro, edición y eliminación de productos.
- Control de existencias y stock disponible.
- Clasificación de productos por categorías.
- Registro de movimientos de inventario (entradas y salidas).
- Visualización de listados y consultas de productos.
- Gestión de usuarios y permisos (según configuración).
- Interfaz web amigable y fácil de usar.

## Tecnologías Utilizadas

El proyecto fue desarrollado utilizando las siguientes tecnologías:

- Laravel como framework backend.
- PHP para la lógica del servidor.
- MySQL u otro gestor de base de datos compatible.
- Blade como motor de plantillas.
- HTML, CSS y JavaScript para la interfaz de usuario.

Laravel facilita el desarrollo gracias a su arquitectura MVC, su ORM Eloquent y sus herramientas integradas para el manejo de rutas, controladores y migraciones.

## Objetivo del Sistema

El objetivo principal del sistema es proporcionar una herramienta eficiente para la administración de inventarios, permitiendo al usuario:

- Contar con información confiable y actualizada.
- Mejorar el control y organización de los productos.
- Facilitar la toma de decisiones.
- Reducir pérdidas y errores administrativos.

## Instalación y Ejecución

Sigue los pasos a continuación para ejecutar el proyecto de manera local:

```bash
git clone https://github.com/tu-usuario/tu-repositorio.git
cd tu-repositorio
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve

## Licencia

Este proyecto ha sido desarrollado utilizando el framework Laravel, el cual es software de código abierto y se distribuye bajo la licencia MIT.
