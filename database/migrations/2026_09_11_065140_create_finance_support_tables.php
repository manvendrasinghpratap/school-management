<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Fee Installments
        |--------------------------------------------------------------------------
        |
        | Breaks a fee structure into monthly, quarterly, half-yearly,
        | annual, or custom installments.
        |
        */

        Schema::create('fee_installments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->foreignId('fee_structure_id')
                ->constrained('fee_structures')
                ->cascadeOnDelete();

            $table->unsignedInteger('installment_number');

            $table->string('name', 255);

            $table->decimal('amount', 12, 2);

            $table->date('due_date');

            $table->enum('status', [
                'pending',
                'paid',
                'partial',
                'overdue',
                'cancelled',
            ])->default('pending');

            $table->timestamps();

            $table->index(
                ['school_id', 'fee_structure_id'],
                'fee_installments_school_structure_index'
            );

            $table->unique(
                ['fee_structure_id', 'installment_number'],
                'fee_installments_structure_number_unique'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Payment Allocations
        |--------------------------------------------------------------------------
        |
        | Allows one payment to be allocated:
        | - to one invoice
        | - to a specific invoice item
        | - across multiple invoices/items
        |
        */

        Schema::create('payment_allocations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->foreignId('payment_id')
                ->constrained('payments')
                ->cascadeOnDelete();

            $table->foreignId('invoice_id')
                ->constrained('invoices')
                ->cascadeOnDelete();

            $table->foreignId('invoice_item_id')
                ->nullable()
                ->constrained('invoice_items')
                ->nullOnDelete();

            $table->decimal('amount', 12, 2);

            $table->dateTime('allocated_at');

            $table->foreignId('allocated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(
                ['school_id', 'payment_id'],
                'payment_allocations_school_payment_index'
            );

            $table->index(
                ['school_id', 'invoice_id'],
                'payment_allocations_school_invoice_index'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Payment Refunds
        |--------------------------------------------------------------------------
        |
        | Financial records must not be deleted to correct mistakes.
        | Refunds are recorded separately and preserve the original payment.
        |
        */

        Schema::create('payment_refunds', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->foreignId('payment_id')
                ->constrained('payments')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->decimal('amount', 12, 2);

            $table->text('reason');

            $table->enum('status', [
                'requested',
                'approved',
                'processed',
                'rejected',
                'cancelled',
            ])->default('requested');

            $table->foreignId('requested_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('processed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->dateTime('requested_at')
                ->nullable();

            $table->dateTime('approved_at')
                ->nullable();

            $table->dateTime('processed_at')
                ->nullable();

            $table->string('reference', 255)
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->timestamps();

            $table->index(
                ['school_id', 'payment_id'],
                'payment_refunds_school_payment_index'
            );

            $table->index(
                ['school_id', 'student_id'],
                'payment_refunds_school_student_index'
            );

            $table->index(
                ['school_id', 'status'],
                'payment_refunds_school_status_index'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Finance Movements
        |--------------------------------------------------------------------------
        |
        | Central financial audit/ledger trail.
        |
        */

        Schema::create('finance_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->nullable()
                ->constrained('students')
                ->nullOnDelete();

            $table->foreignId('guardian_id')
                ->nullable()
                ->constrained('guardians')
                ->nullOnDelete();

            $table->foreignId('invoice_id')
                ->nullable()
                ->constrained('invoices')
                ->nullOnDelete();

            $table->foreignId('invoice_item_id')
                ->nullable()
                ->constrained('invoice_items')
                ->nullOnDelete();

            $table->foreignId('payment_id')
                ->nullable()
                ->constrained('payments')
                ->nullOnDelete();

            $table->foreignId('receipt_id')
                ->nullable()
                ->constrained('receipts')
                ->nullOnDelete();

            $table->string('movement_type', 100);

            $table->string('reference_type', 100)
                ->nullable();

            $table->unsignedBigInteger('reference_id')
                ->nullable();

            $table->decimal('amount', 12, 2);

            $table->enum('direction', [
                'debit',
                'credit',
            ]);

            $table->decimal('previous_balance', 12, 2)
                ->nullable();

            $table->decimal('new_balance', 12, 2)
                ->nullable();

            $table->foreignId('performed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('reason', 500)
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->timestamps();

            $table->index(
                ['school_id', 'student_id'],
                'finance_movements_school_student_index'
            );

            $table->index(
                ['school_id', 'movement_type'],
                'finance_movements_school_type_index'
            );

            $table->index(
                ['school_id', 'created_at'],
                'finance_movements_school_created_index'
            );

            $table->index(
                ['reference_type', 'reference_id'],
                'finance_movements_reference_index'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Cashier Sessions
        |--------------------------------------------------------------------------
        |
        | Tracks daily cashier opening and closing.
        |
        */

        Schema::create('cashier_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->dateTime('opened_at');

            $table->decimal('opening_cash', 12, 2)
                ->default(0);

            $table->dateTime('closed_at')
                ->nullable();

            $table->decimal('expected_cash', 12, 2)
                ->nullable();

            $table->decimal('actual_cash', 12, 2)
                ->nullable();

            $table->decimal('difference', 12, 2)
                ->nullable();

            $table->enum('status', [
                'open',
                'closed',
            ])->default('open');

            $table->text('closing_notes')
                ->nullable();

            $table->timestamps();

            $table->index(
                ['school_id', 'user_id'],
                'cashier_sessions_school_user_index'
            );

            $table->index(
                ['school_id', 'status'],
                'cashier_sessions_school_status_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Drop in reverse dependency order
        |--------------------------------------------------------------------------
        */

        Schema::dropIfExists('cashier_sessions');
        Schema::dropIfExists('finance_movements');
        Schema::dropIfExists('payment_refunds');
        Schema::dropIfExists('payment_allocations');
        Schema::dropIfExists('fee_installments');
    }
};