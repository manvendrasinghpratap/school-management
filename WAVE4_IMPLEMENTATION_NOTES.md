# SMS Wave 4 Implementation

## Included

This update adds the first complete Wave 4 administration/operations layer while preserving the existing completed modules.

### HR
- Designation model and CRUD
- User -> Designation relationship
- School-scoped designation validation in user create/update requests
- Staff document upload/download/delete
- Staff show page link to staff documents

### Administration / Operations
- Suppliers
- Inventory items
- Assets
- Visitors
- Complaints
- Disciplinary records
- Medical records

### Admissions
- Admission applications
- Academic placement validation
- Application status workflow
- Accept application
- Applicant -> Student conversion
- Guardian creation/linking during conversion
- Initial student enrollment during conversion

### Security / Data isolation
- Wave 4 business tables receive `school_id` where the existing schema did not have a direct school discriminator.
- All Wave 4 queries validate the authenticated user's school.
- Related school-owned users, students, staff, suppliers and academic records are validated before assignment.
- Designations use the existing `account_id` field as the school discriminator.

### Demo reset
`sms:reset-demo` now also clears `admission_applications`. Existing RBAC definitions and designations are intentionally preserved.

## Migration

Run after backing up the database:

```bash
php artisan migrate
```

Migration:

`database/migrations/2026_09_22_120000_prepare_wave4_modules.php`

The migration backfills `school_id` for existing Wave 4 records using the first configured school when the column is added. The current project/database baseline contains one school.

## Routes

Wave 4 routes are centralized in:

`routes/wave4.php`

and loaded from `routes/web.php`.

## Verification required on the real project

Because this uploaded source does not contain the installed `vendor/` directory, Laravel runtime tests cannot be executed against this copy here. After merging into the local project:

```bash
php artisan migrate
php artisan optimize:clear
php artisan route:list --path=admin
php artisan tinker
```

Then test:

1. Designation CRUD
2. Staff document upload/download/delete
3. Supplier CRUD
4. Inventory CRUD
5. Asset CRUD
6. Visitor CRUD
7. Complaint CRUD/status
8. Disciplinary CRUD
9. Medical CRUD with exactly one student/staff subject
10. Admission create/edit/accept/convert
11. Cross-school access rejection
12. Permission restrictions

## Important

Do not run `migrate:fresh` on the existing development database. Take a backup before running the Wave 4 migration.
