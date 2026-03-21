# ⚔️ RPG Game Backend API

A robust RESTful API built with **Laravel** that simulates the backend of a Role-Playing Game (RPG). This project handles user authentication, character management, an item catalog, and complex inventory/equipment mechanics.

The core focus of this project is clean architecture, strict responsibility delegation, and adherence to framework best practices (Controllers, Models, Policies, and FormRequests).

## ✨ Key Features & Business Logic

* **Authentication & Role Management:** Implemented via **Laravel Sanctum**. The API distinguishes between `admin` (global catalog management) and `player` (character management). Security is centralized using **Policies** (`Gate::authorize()`), keeping controllers logic-free.
* **Dynamic Event-Sourced Inventory:** Instead of relying on a static inventory table, the current state of a character's backpack and equipment (head, body, weapon slots) is calculated on-the-fly. This is achieved by projecting the chronological history of `inventory_movements` (LOOT, EQUIP, UNEQUIP, DROP).
* **MongoDB Audit Logs:** Every critical action (e.g., character creation, item movement) triggers a parallel, immutable log in a NoSQL database (MongoDB) to ensure complete user traceability.

## 🚀 Advanced Implementations

* **Complex Validations via Hooks:** Beyond basic Form Requests, we utilized `withValidator` and the `after()` hook for cross-business rules. For example, the API actively blocks equipping consumables or placing armor in weapon slots before the request even reaches the controller.
* **Eloquent Query Scopes:** Implemented Local Scopes (`mine()`, `byType()`, `equippable()`) to filter the catalog dynamically. This keeps database read operations semantic, DRY, and highly readable.
* **AI-Assisted Optimization:** Leveraged AI coding assistants to refactor and optimize code quality, ensuring robust architecture.

## 👥 Team & Responsibilities

* **Eric Lorenzo:** Authentication, Item CRUD, Equipment Slot Logic, Query Scopes, Documentation.
* **Diego García:** MongoDB logging, Inventory Endpoints & Logic, Character CRUD, Seeders/Factories.
* **Shared:** Global Architecture, Roles, and Policies design.

🎥 **[Watch the Video Demonstration](https://drive.google.com/file/d/1SneWwD0-4op2q7yGVyroYraAAsNPDGLf/view?usp=sharing)**
