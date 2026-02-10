---
name: 🎒 Inventory Movements
about: Registrar movimientos LOOT/EQUIP/UNEQUIP/DROP
---

## 🎒 Inventory Movements

- [ ] POST `/inventory-movements`
- [ ] Usa auth (user logeado)
- [ ] Guarda `character_id`, `item_id`, `type`, `executed_at`
- [ ] Validación con Form Request:
  - [ ] `character_id` exists
  - [ ] `item_id` exists
  - [ ] `type` enum (LOOT/EQUIP/UNEQUIP/DROP)

## 🧠 Lógica de negocio (fuera del Form Request)
- [ ] Ownership del character validado con Policy/authorize
- [ ] Reglas RPG (si las hay) implementadas en lógica de aplicación

## 📝 Comentario
Explica si has implementado reglas extra (slot ocupado, etc.) y dónde.
