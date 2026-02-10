---
name: 🌱 Seeders & Factories
about: Datos suficientes para testear toda la API
---

## 🌱 Seeders & Factories

### ✅ MySQL mínimos
- [ ] Users: 1 admin + 3 players (`role` incluido)
- [ ] Characters: mínimo 2 por player (mín. 6 total)
- [ ] Items: mínimo 18 (6 weapons, 6 armor, 6 consumables)
- [ ] InventoryMovements: mínimo 60 (30 LOOT, 10 EQUIP, 10 UNEQUIP, 10 DROP)
- [ ] Casos obligatorios creados por seed:
  - [ ] 1 character con equipamiento completo
  - [ ] 1 character con inventario no vacío pero sin equipar
  - [ ] 1 character con inventario vacío

### ✅ MongoDB (logs) mínimos
- [ ] Mínimo 40 logs generados
- [ ] Incluye acciones: `character_created`, `item_created` (si aplica), `inventory_movement_created`
- [ ] `metadata` incluye info útil (movementType, characterId, itemId...)

## 🧪 Cómo probar
Indica los comandos y qué se debe ver tras ejecutarlos:
- `php artisan migrate:fresh --seed`

## 📝 Comentario
Explica cómo has generado datos y cómo garantizas los casos obligatorios.
