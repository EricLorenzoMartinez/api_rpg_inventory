---
name: 📦 Endpoint Inventory (calculado)
about: Inventario calculado a partir de movimientos (sin tabla inventory)
---

## 📦 Inventory (calculado)

- [ ] GET `/characters/{id}/inventory`
- [ ] Devuelve inventario calculado por movimientos
- [ ] Incluye info útil del item (id, name, type, slot, power)
- [ ] Respeta policies (player solo sus characters)
- [ ] Funciona para:
  - [ ] inventario vacío
  - [ ] inventario con items no equipados
  - [ ] inventario con items equipados (si aplica)

## 🧪 Cómo probar
Indica un characterId con inventario vacío y otro con inventario lleno (seed).

## 📝 Comentario
Explica brevemente tu estrategia de cálculo.
