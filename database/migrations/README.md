# School Management System — Laravel Migration Set

This directory contains the ordered Laravel migrations for the SMS database baseline.

Database design source:
- school_management_system
- MySQL / InnoDB
- utf8mb4
- 69 migration files, dependency ordered

The migration order follows the ERD dependency chain:
schools → users/RBAC/settings → academic structure → students/staff → academic operations
→ examinations/results → finance → communication/identity → services → audit/Laravel support.

Important:
- This set follows the agreed SMS database/ERD baseline and the student migration previously supplied.
- The ERD reference document in the project describes the table inventory and relationships; the prior conversation SQL was the implementation baseline.
- Run with `php artisan migrate`.
