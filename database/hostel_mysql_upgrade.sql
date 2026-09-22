-- ================================================================
-- SMS Hostel Management - MySQL Database Upgrade
-- Target: existing school_management_system database
-- Tested design target: MySQL 5.7.x / Laravel 13
--
-- IMPORTANT:
-- 1. BACK UP the database before running this file.
-- 2. This file is for the current SMS database where:
--      - hostels uses type/location/is_active
--      - hostel_allocations.room_id currently references legacy rooms.id
--      - hostel_rooms/hostel_beds/hostel_fees already exist
-- 3. Run this file once.
-- 4. The legacy `rooms` table is NOT deleted by this script.
-- ================================================================

USE school_management_system;

SET FOREIGN_KEY_CHECKS = 0;

-- ================================================================
-- 1. Upgrade HOSTELS
-- ================================================================

ALTER TABLE hostels
    ADD COLUMN code VARCHAR(100) NULL AFTER name,
    ADD COLUMN hostel_type ENUM('boys','girls','mixed','staff') NOT NULL DEFAULT 'mixed' AFTER code,
    ADD COLUMN address TEXT NULL AFTER hostel_type,
    ADD COLUMN warden_staff_id BIGINT UNSIGNED NULL AFTER address,
    ADD COLUMN monthly_fee DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER capacity,
    ADD COLUMN status ENUM('active','inactive') NOT NULL DEFAULT 'active' AFTER monthly_fee,
    ADD COLUMN created_by BIGINT UNSIGNED NULL AFTER status;

-- Preserve data from the old columns.
UPDATE hostels
SET
    hostel_type = CASE
        WHEN type = 'male' THEN 'boys'
        WHEN type = 'female' THEN 'girls'
        WHEN type = 'mixed' THEN 'mixed'
        ELSE 'mixed'
    END,
    address = location,
    status = CASE
        WHEN is_active = 1 THEN 'active'
        ELSE 'inactive'
    END;

-- Give existing hostels a code where one does not exist.
UPDATE hostels
SET code = CONCAT('HOSTEL-', id)
WHERE code IS NULL OR code = '';

ALTER TABLE hostels
    MODIFY COLUMN school_id BIGINT UNSIGNED NOT NULL,
    MODIFY COLUMN name VARCHAR(255) NOT NULL,
    MODIFY COLUMN code VARCHAR(100) NULL,
    MODIFY COLUMN address TEXT NULL,
    MODIFY COLUMN capacity INT UNSIGNED NOT NULL DEFAULT 0,
    MODIFY COLUMN monthly_fee DECIMAL(12,2) NOT NULL DEFAULT 0,
    MODIFY COLUMN status ENUM('active','inactive') NOT NULL DEFAULT 'active';

-- Remove obsolete columns only after their data has been copied.
ALTER TABLE hostels
    DROP COLUMN type,
    DROP COLUMN location,
    DROP COLUMN is_active;

ALTER TABLE hostels
    ADD UNIQUE KEY hostels_school_code_unique (school_id, code),
    ADD KEY hostels_school_idx (school_id),
    ADD KEY hostels_warden_idx (warden_staff_id),
    ADD KEY hostels_created_by_idx (created_by);

-- ================================================================
-- 2. Ensure HOSTEL ROOMS structure
-- ================================================================

-- The current database already contains the room unique/index keys.
-- No duplicate indexes are added here.

-- ================================================================
-- 3. Migrate legacy `rooms` records into `hostel_rooms`
-- ================================================================
--
-- The current database has a legacy `rooms` table.
-- Copy those records before changing hostel_allocations.room_id.
--
-- Existing hostel_rooms records are preserved.
-- Matching hostel + room_number records are not duplicated.
-- ================================================================

INSERT INTO hostel_rooms
(
    school_id,
    hostel_id,
    room_number,
    floor,
    room_type,
    capacity,
    monthly_fee,
    status,
    created_at,
    updated_at
)
SELECT
    h.school_id,
    r.hostel_id,
    r.room_number,
    r.floor,
    NULL,
    r.capacity,
    0,
    CASE
        WHEN r.status IN ('available','full','maintenance')
            THEN r.status
        ELSE 'available'
    END,
    r.created_at,
    r.updated_at
FROM rooms r
INNER JOIN hostels h
    ON h.id = r.hostel_id
LEFT JOIN hostel_rooms hr
    ON hr.hostel_id = r.hostel_id
   AND hr.room_number = r.room_number
WHERE hr.id IS NULL;

-- ================================================================
-- 4. Upgrade HOSTEL ALLOCATIONS
-- ================================================================

-- Remove the incorrect legacy FK first.
ALTER TABLE hostel_allocations
    DROP FOREIGN KEY hostel_allocations_room_foreign;

ALTER TABLE hostel_allocations
    ADD COLUMN school_id BIGINT UNSIGNED NULL AFTER id,
    ADD COLUMN hostel_id BIGINT UNSIGNED NULL AFTER school_id,
    ADD COLUMN bed_id BIGINT UNSIGNED NULL AFTER room_id,
    ADD COLUMN academic_year_id BIGINT UNSIGNED NULL AFTER student_id,
    ADD COLUMN monthly_fee DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER end_date,
    ADD COLUMN notes TEXT NULL AFTER status,
    ADD COLUMN created_by BIGINT UNSIGNED NULL AFTER notes;

-- Convert old allocation status values.
UPDATE hostel_allocations
SET status = CASE
    WHEN status = 'active' THEN 'allocated'
    WHEN status = 'completed' THEN 'checked_out'
    WHEN status = 'cancelled' THEN 'cancelled'
    ELSE 'allocated'
END;

ALTER TABLE hostel_allocations
    MODIFY COLUMN status ENUM(
        'allocated',
        'checked_in',
        'checked_out',
        'cancelled'
    ) NOT NULL DEFAULT 'allocated';

-- Map legacy room IDs to the new hostel_rooms IDs.
UPDATE hostel_allocations ha
INNER JOIN rooms legacy_room
    ON legacy_room.id = ha.room_id
INNER JOIN hostel_rooms hr
    ON hr.hostel_id = legacy_room.hostel_id
   AND hr.room_number = legacy_room.room_number
INNER JOIN hostels h
    ON h.id = hr.hostel_id
SET
    ha.room_id = hr.id,
    ha.hostel_id = hr.hostel_id,
    ha.school_id = h.school_id,
    ha.monthly_fee = COALESCE(hr.monthly_fee, 0);

-- Populate school_id for any allocation that did not require
-- legacy room migration but can be resolved through its hostel.
UPDATE hostel_allocations ha
INNER JOIN hostels h
    ON h.id = ha.hostel_id
SET ha.school_id = h.school_id
WHERE ha.school_id IS NULL;

-- Keep old allocation data usable while making new columns required.
ALTER TABLE hostel_allocations
    MODIFY COLUMN school_id BIGINT UNSIGNED NOT NULL,
    MODIFY COLUMN hostel_id BIGINT UNSIGNED NOT NULL,
    MODIFY COLUMN room_id BIGINT UNSIGNED NOT NULL,
    MODIFY COLUMN student_id BIGINT UNSIGNED NOT NULL,
    MODIFY COLUMN start_date DATE NOT NULL;

ALTER TABLE hostel_allocations
    ADD KEY hostel_allocations_school_idx (school_id),
    ADD KEY hostel_allocations_hostel_idx (hostel_id),
    ADD KEY hostel_allocations_bed_idx (bed_id),
    ADD KEY hostel_allocations_student_idx (student_id),
    ADD KEY hostel_allocations_year_idx (academic_year_id),
    ADD KEY hostel_allocations_created_by_idx (created_by);

-- ================================================================
-- 5. Ensure HOSTEL BEDS structure
-- ================================================================

-- The current database already contains the bed unique/index keys.
-- No duplicate indexes are added here.

-- ================================================================
-- 6. Ensure HOSTEL FEES structure
-- ================================================================

-- The current database already contains the hostel fee index keys.
-- No duplicate indexes are added here.

-- ================================================================
-- 7. Foreign keys
-- ================================================================

ALTER TABLE hostels
    ADD CONSTRAINT hostels_school_foreign
        FOREIGN KEY (school_id)
        REFERENCES schools(id)
        ON DELETE CASCADE,
    ADD CONSTRAINT hostels_warden_staff_foreign
        FOREIGN KEY (warden_staff_id)
        REFERENCES staff(id)
        ON DELETE SET NULL,
    ADD CONSTRAINT hostels_created_by_foreign
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL;

ALTER TABLE hostel_rooms
    ADD CONSTRAINT hostel_rooms_school_foreign
        FOREIGN KEY (school_id)
        REFERENCES schools(id)
        ON DELETE CASCADE,
    ADD CONSTRAINT hostel_rooms_hostel_foreign
        FOREIGN KEY (hostel_id)
        REFERENCES hostels(id)
        ON DELETE CASCADE;

ALTER TABLE hostel_beds
    ADD CONSTRAINT hostel_beds_school_foreign
        FOREIGN KEY (school_id)
        REFERENCES schools(id)
        ON DELETE CASCADE,
    ADD CONSTRAINT hostel_beds_room_foreign
        FOREIGN KEY (room_id)
        REFERENCES hostel_rooms(id)
        ON DELETE CASCADE;

ALTER TABLE hostel_allocations
    ADD CONSTRAINT hostel_allocations_school_foreign
        FOREIGN KEY (school_id)
        REFERENCES schools(id)
        ON DELETE CASCADE,
    ADD CONSTRAINT hostel_allocations_hostel_foreign
        FOREIGN KEY (hostel_id)
        REFERENCES hostels(id)
        ON DELETE CASCADE,
    ADD CONSTRAINT hostel_allocations_room_foreign
        FOREIGN KEY (room_id)
        REFERENCES hostel_rooms(id)
        ON DELETE CASCADE,
    ADD CONSTRAINT hostel_allocations_bed_foreign
        FOREIGN KEY (bed_id)
        REFERENCES hostel_beds(id)
        ON DELETE SET NULL,
    ADD CONSTRAINT hostel_allocations_student_foreign
        FOREIGN KEY (student_id)
        REFERENCES students(id)
        ON DELETE CASCADE,
    ADD CONSTRAINT hostel_allocations_year_foreign
        FOREIGN KEY (academic_year_id)
        REFERENCES academic_years(id)
        ON DELETE SET NULL,
    ADD CONSTRAINT hostel_allocations_created_by_foreign
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL;

ALTER TABLE hostel_fees
    ADD CONSTRAINT hostel_fees_school_foreign
        FOREIGN KEY (school_id)
        REFERENCES schools(id)
        ON DELETE CASCADE,
    ADD CONSTRAINT hostel_fees_allocation_foreign
        FOREIGN KEY (hostel_allocation_id)
        REFERENCES hostel_allocations(id)
        ON DELETE CASCADE,
    ADD CONSTRAINT hostel_fees_invoice_foreign
        FOREIGN KEY (invoice_id)
        REFERENCES invoices(id)
        ON DELETE SET NULL;

SET FOREIGN_KEY_CHECKS = 1;

-- ================================================================
-- 8. Verification
-- ================================================================

SELECT
    'hostels' AS table_name,
    COUNT(*) AS records
FROM hostels
UNION ALL
SELECT
    'hostel_rooms',
    COUNT(*)
FROM hostel_rooms
UNION ALL
SELECT
    'hostel_beds',
    COUNT(*)
FROM hostel_beds
UNION ALL
SELECT
    'hostel_allocations',
    COUNT(*)
FROM hostel_allocations
UNION ALL
SELECT
    'hostel_fees',
    COUNT(*)
FROM hostel_fees;

SHOW CREATE TABLE hostels;
SHOW CREATE TABLE hostel_rooms;
SHOW CREATE TABLE hostel_beds;
SHOW CREATE TABLE hostel_allocations;
SHOW CREATE TABLE hostel_fees;
