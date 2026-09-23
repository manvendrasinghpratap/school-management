# Wave 5 Implementation Notes

## Review findings

The supplied route/codebase already had:
- Laravel 13 application structure
- Spatie RBAC
- Student, Parent and Teacher roles
- Student `user_id`
- Staff `user_id`
- Student/Guardian relationship
- Academic hierarchy and enrollment data
- Attendance, timetable, course, examination, marks/result, report-card/transcript and finance tables
- A notifications table

There was no API route tree in the supplied routes package and no API controller directory. `config/auth.php` only had the `web` guard. Wave 5 therefore adds a lightweight hashed bearer-token guard without introducing another dependency.

## School isolation

All portal queries derive the school from the authenticated user and then constrain child/student/staff records to that school. Parent child access is additionally constrained through the authenticated guardian's `student_guardians` relationship.

## Notification schema compatibility

The supplied SQL notification table uses `body`, not `message`. Wave 5 uses `body` consistently.

## Existing model naming compatibility

The existing codebase uses `Courses`, `Classes`, `AcademicYears`, etc. Wave 5 retains those names instead of inventing singular aliases.

## Scope

Wave 5 provides:
- Student portal
- Parent portal
- Teacher portal
- Shared profile/password pages
- Notification center
- API authentication
- API portal endpoints
- API profile endpoints
- API notification endpoints
- Portal RBAC integration
- School isolation
- Optional demo portal account seeding

Online payment gateway integration is deliberately not coupled to the portal release. Existing invoice/payment records remain the source of truth; a gateway can be added as a separate adapter in the next payment stage.
