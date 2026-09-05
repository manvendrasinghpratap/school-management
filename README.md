# SMS — Complete User Management Module

This module is the next layer after RBAC. It provides administrative user CRUD,
search/filtering, role assignment, password administration, account activation,
account deactivation, and protection against destructive Super Admin mistakes.

## Important existing-schema compatibility

The original SMS users migration may not contain `is_active`. This package adds
migration `000071_add_is_active_to_users_table.php`.

If your current users table already has `is_active`, the migration safely skips
adding it.

## User model requirements

Your `App\Models\User` must use:

    use Spatie\Permission\Traits\HasRoles;

Inside the class:

    use HasRoles;

If your User model has `$fillable`, include:

    'name',
    'email',
    'password',
    'is_active',

If your application uses `$casts`, include:

    'is_active' => 'boolean',
    'email_verified_at' => 'datetime',

Do not duplicate the `roles()` / `permissions()` relations supplied by HasRoles.

## Routes

Register `routes/user-management.php` from your application's route bootstrap
or merge its routes into `routes/web.php`.

The module protects every route with:
- authentication
- granular RBAC permissions

## Security rules

1. A user cannot delete their own account.
2. A user cannot deactivate their own account.
3. The last Super Admin cannot be deleted or deactivated.
4. A Super Admin cannot accidentally remove their own Super Admin access through
   the supplied update guard.
5. Passwords are always hashed.
6. Role assignment is validated against existing roles.
7. Search/filtering occurs server-side.
8. UI `@can()` checks are only convenience; route and request authorization
   remain mandatory.

## User lifecycle

Create → Assign Role(s) → Activate → Update → Password Change → Deactivate or Delete

For the SMS project, user accounts should normally be linked to student/staff
records through their respective user_id relationship rather than putting
student-specific or staff-specific fields into users.

## Student rule

Students should not receive `users.create`, `users.update`, or `users.delete`.
The student role is intended for portal access and read-only academic/personal
information, plus password change through the student-facing account workflow.

## Integration with Audit Logs

For production, connect the following actions to the SMS audit log service:
- user.created
- user.updated
- user.deleted
- user.activated
- user.deactivated
- user.password_changed
- user.roles_changed
- user.permissions_changed

Do not log raw passwords.

## Views

The included Blade views assume `layouts.app` exists. Adapt Bootstrap/Tailwind
classes to the project's final UI framework without changing authorization
logic.

## Testing

Run:

    php artisan test --filter=UserManagementTest

Then run the full suite:

    php artisan test
