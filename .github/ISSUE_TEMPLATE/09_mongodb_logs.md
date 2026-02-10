---
name: 🧾 MongoDB Logs
about: Registro de logs en MongoDB desde la lógica de aplicación
---

## 🧾 MongoDB Logs

- [ ] Conexión a MongoDB configurada
- [ ] Colección `logs` (solo escritura)
- [ ] Se registran logs al menos en:
  - [ ] character_created
  - [ ] inventory_movement_created
  - [ ] item_created (si aplica)
- [ ] Cada log incluye:
  - [ ] action
  - [ ] user_id
  - [ ] metadata (object)
  - [ ] created_at

## 🧪 Cómo probar
Indica qué acción ejecutar y qué documento debe aparecer en Mongo.

## 📝 Comentario
Explica dónde llamas al log y por qué.
