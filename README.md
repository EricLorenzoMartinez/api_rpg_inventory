# README DE LARADEVS

[![Review Assignment Due Date](https://classroom.github.com/assets/deadline-readme-button-22041afd0340ce965d47ae6ef1cefeee28c7c493a6346c4f15d667ab976d596c.svg)](https://classroom.github.com/a/c8dIICDL)

## ⚠️ Uso de Issues (obligatorio)

Este repositorio incluye **issue templates** que representan los bloques evaluables del proyecto.
Debes crear los issues a partir de estos templates, **asignártelos a ti mismo**, completar la información solicitada y **cerrarlos únicamente cuando la parte esté correctamente implementada**.

La evaluación se realizará en base a los **issues cerrados**.
Un issue no creado o no cerrado se considerará **no entregado**.

## Descripción del Proyecto

Esta API ha sido desarrollada como solución a la Práctica de Laravel, simulando el backend de un videojuego de rol (RPG). El objetivo principal ha sido construir un sistema robusto y seguro que gestione usuarios, personajes, un catálogo de ítems y las mecánicas de inventario y equipamiento.

Nos hemos enfocado en mantener una arquitectura limpia, delegando responsabilidades correctamente y siguiendo las buenas prácticas del framework (separando lógica en Controladores, Modelos, Policies y FormRequests).

### Características Principales y Lógica de Negocio

* **Autenticación y Roles:** Implementado con **Laravel Sanctum**. La API distingue entre el rol `admin` (gestiona el catálogo global de ítems) y el rol `player` (gestiona sus propios personajes). Toda la seguridad está centralizada mediante **Policies** usando `Gate::authorize()`, manteniendo los controladores completamente limpios de condicionales.
* **Inventario Dinámico (Event Sourcing):** El mayor reto ha sido no depender de una tabla estática para el inventario. El estado actual de la mochila y el equipamiento (qué lleva puesto el personaje en la cabeza, cuerpo y arma) se calcula "al vuelo" proyectando el historial cronológico de la tabla `inventory_movements` (LOOT, EQUIP, UNEQUIP, DROP).
* **Logs en MongoDB:** Cada acción importante (como crear un personaje o mover un ítem) registra de forma paralela un log inmutable en una base de datos documental (MongoDB), manteniendo la trazabilidad del usuario.

### ✨ Extras y Mejoras Implementadas

Para asegurar la calidad y consistencia de los datos, hemos querido ir un paso más allá de los requisitos básicos implementando lo siguiente:

1. **Validaciones Complejas mediante Hooks (`withValidator`):** No nos hemos conformado con las reglas básicas de los Form Requests. Hemos utilizado el método `withValidator` y el hook `after()` para implementar reglas de negocio cruzadas. Por ejemplo, nuestra API bloquea activamente intentos de "equipar consumibles" (exigiendo que su slot sea nulo) o intentos de poner una armadura en el slot de armas, antes de que la petición siquiera llegue al controlador.

2. **Bonus Elegido: Query Scopes (Filtros Limpios):**
   Hemos implementado el **Bonus A** mediante *Local Scopes* en Eloquent. Hemos creado scopes como `mine()` (para obtener solo los personajes del usuario autenticado), `byType()` y `equippable()` (para filtrar el catálogo). Esto nos ha permitido mantener el código de lectura de la base de datos extremadamente semántico, corto (DRY) y fácil de leer.

[Ver vídeo demostración](https://drive.google.com/file/d/1SneWwD0-4op2q7yGVyroYraAAsNPDGLf/view?usp=sharing)
