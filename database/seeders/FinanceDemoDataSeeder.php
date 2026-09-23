<?php

namespace Database\Seeders;

use App\Models\AcademicYears;
use App\Models\Classes;
use App\Models\FeeCategory;
use App\Models\FeeInstallment;
use App\Models\FeeStructure;
use App\Models\FinanceMovement;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\PaymentAllocation;
use App\Models\PaymentRefund;
use App\Models\Scholarships;
use App\Models\Student;
use App\Models\StudentFee;
use App\Models\Terms;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinanceDemoDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $schoolId = 1;
            $createdBy = 2;

            $this->command?->info('Creating finance fee categories...');

            /*
             * ---------------------------------------------------------
             * 1. Fee Categories
             * ---------------------------------------------------------
             */
            $categories = [];

            foreach ([
                [
                    'name' => 'Tuition Fee',
                    'code' => 'TUITION',
                    'description' => 'Core tuition fee for the academic term.',
                ],
                [
                    'name' => 'Registration Fee',
                    'code' => 'REGISTRATION',
                    'description' => 'Annual student registration fee.',
                ],
                [
                    'name' => 'Examination Fee',
                    'code' => 'EXAM',
                    'description' => 'Term examination and assessment fee.',
                ],
                [
                    'name' => 'ICT Fee',
                    'code' => 'ICT',
                    'description' => 'ICT and computer laboratory support fee.',
                ],
                [
                    'name' => 'Library Fee',
                    'code' => 'LIBRARY',
                    'description' => 'Library services and resources fee.',
                ],
            ] as $data) {
                $categories[$data['code']] = FeeCategory::create([
                    'school_id' => $schoolId,
                    'name' => $data['name'],
                    'code' => $data['code'],
                    'description' => $data['description'],
                    'is_active' => true,
                ]);
            }

            /*
             * ---------------------------------------------------------
             * 2. Current academic year / terms
             * ---------------------------------------------------------
             */
            $academicYear = AcademicYears::where('school_id', $schoolId)
                ->where('is_current', true)
                ->firstOrFail();

            $firstTerm = Terms::where('school_id', $schoolId)
                ->where('academic_year_id', $academicYear->id)
                ->where('term_number', 1)
                ->firstOrFail();

            $secondTerm = Terms::where('school_id', $schoolId)
                ->where('academic_year_id', $academicYear->id)
                ->where('term_number', 2)
                ->firstOrFail();

            /*
             * ---------------------------------------------------------
             * 3. Fee Structures
             * ---------------------------------------------------------
             */
            $primary1 = Classes::where('school_id', $schoolId)
                ->where('name', 'Primary 1')
                ->firstOrFail();
            $primary2 = Classes::where('school_id', $schoolId)
                ->where('name', 'Primary 2')
                ->firstOrFail();
            $primary3 = Classes::where('school_id', $schoolId)
                ->where('name', 'Primary 3')
                ->firstOrFail();

            $jss1 = Classes::where('school_id', $schoolId)
                ->where('name', 'JSS 1')
                ->firstOrFail();

            $ss1 = Classes::where('school_id', $schoolId)
                ->where('name', 'SS 1')
                ->firstOrFail();

            $structureDefinitions = [
                [
                    'category' => 'TUITION',
                    'class' => $primary1,
                    'term' => $firstTerm,
                    'amount' => 45000,
                    'due_date' => '2026-10-15',
                ],
                [
                    'category' => 'TUITION',
                    'class' => $primary3,
                    'term' => $firstTerm,
                    'amount' => 50000,
                    'due_date' => '2026-10-15',
                ],
                [
                    'category' => 'TUITION',
                    'class' => $jss1,
                    'term' => $firstTerm,
                    'amount' => 60000,
                    'due_date' => '2026-10-15',
                ],
                [
                    'category' => 'TUITION',
                    'class' => $ss1,
                    'term' => $firstTerm,
                    'amount' => 70000,
                    'due_date' => '2026-10-15',
                ],
                [
                    'category' => 'REGISTRATION',
                    'class' => $primary1,
                    'term' => $firstTerm,
                    'amount' => 10000,
                    'due_date' => '2026-09-30',
                ],
                [
                    'category' => 'EXAM',
                    'class' => $jss1,
                    'term' => $firstTerm,
                    'amount' => 5000,
                    'due_date' => '2026-11-30',
                ],
                [
                    'category' => 'ICT',
                    'class' => $ss1,
                    'term' => $firstTerm,
                    'amount' => 7500,
                    'due_date' => '2026-11-15',
                ],
                [
                    'category' => 'LIBRARY',
                    'class' => $primary3,
                    'term' => $secondTerm,
                    'amount' => 3000,
                    'due_date' => '2027-01-31',
                ],
                [
                    'category' => 'TUITION',
                    'class' => $primary2,
                    'term' => $firstTerm,
                    'amount' => 48000,
                    'due_date' => '2026-10-15',
                ],
            ];

            $structures = [];

            foreach ($structureDefinitions as $definition) {
                $structures[] = FeeStructure::create([
                    'school_id' => $schoolId,
                    'fee_category_id' => $categories[$definition['category']]->id,
                    'academic_year_id' => $academicYear->id,
                    'class_id' => $definition['class']->id,
                    'term_id' => $definition['term']->id,
                    'amount' => $definition['amount'],
                    'due_date' => $definition['due_date'],
                    'is_active' => true,
                ]);
            }

            /*
             * ---------------------------------------------------------
             * 4. Installments
             * ---------------------------------------------------------
             */
            foreach ($structures as $structure) {
                if ($structure->fee_category_id === $categories['TUITION']->id) {
                    $half = round((float) $structure->amount / 2, 2);

                    FeeInstallment::create([
                        'school_id' => $schoolId,
                        'fee_structure_id' => $structure->id,
                        'installment_number' => 1,
                        'name' => 'First Installment',
                        'amount' => $half,
                        'due_date' => '2026-10-15',
                        'status' => 'pending',
                    ]);

                    FeeInstallment::create([
                        'school_id' => $schoolId,
                        'fee_structure_id' => $structure->id,
                        'installment_number' => 2,
                        'name' => 'Second Installment',
                        'amount' => $half,
                        'due_date' => '2026-12-01',
                        'status' => 'pending',
                    ]);
                } else {
                    FeeInstallment::create([
                        'school_id' => $schoolId,
                        'fee_structure_id' => $structure->id,
                        'installment_number' => 1,
                        'name' => 'Full Payment',
                        'amount' => $structure->amount,
                        'due_date' => $structure->due_date,
                        'status' => 'pending',
                    ]);
                }
            }

            /*
             * ---------------------------------------------------------
             * 5. Student Fee Assignments
             * ---------------------------------------------------------
             */
            $students = Student::where('school_id', $schoolId)
                ->orderBy('id')
                ->get();

            $tuitionStructures = collect($structures)
                ->filter(fn ($structure) => $structure->fee_category_id === $categories['TUITION']->id)
                ->keyBy('class_id');

            $studentFees = [];

            foreach ($students as $student) {
                $enrollment = $student->enrollments()
                    ->where('school_id', $schoolId)
                    ->where('academic_year_id', $academicYear->id)
                    ->where('status', 'active')
                    ->first();

                if (!$enrollment) {
                    continue;
                }

                $structure = $tuitionStructures->get($enrollment->class_id);

                if (!$structure) {
                    continue;
                }

                $scholarship = null;
                $discount = 0;

                /*
                 * Apply the first available percentage scholarship
                 * to one student so the finance reports demonstrate
                 * scholarship/discount handling.
                 */
                if ($student->student_number === 'STU-2026-003') {
                    $scholarship = Scholarships::where('school_id', $schoolId)
                        ->where('is_active', true)
                        ->where('type', 'percentage')
                        ->first();

                    if ($scholarship) {
                        $discount = round(
                            ((float) $structure->amount * (float) $scholarship->value) / 100,
                            2
                        );
                    }
                }

                $netAmount = max(
                    round((float) $structure->amount - $discount, 2),
                    0
                );

                $studentFees[$student->id] = StudentFee::create([
                    'school_id' => $schoolId,
                    'student_id' => $student->id,
                    'fee_structure_id' => $structure->id,
                    'scholarship_id' => $scholarship?->id,
                    'amount' => $netAmount,
                    'discount' => $discount,
                    'status' => 'pending',
                ]);
            }

            /*
             * ---------------------------------------------------------
             * 6. Invoices
             *
             * We intentionally create three invoice scenarios:
             *   - fully paid
             *   - partially paid
             *   - outstanding
             * ---------------------------------------------------------
             */
            $invoiceScenarios = [
                [
                    'student_number' => 'STU-2026-002',
                    'payment' => 55000,
                    'payment_method' => 'bank_transfer',
                    'status' => 'paid',
                ],
                [
                    'student_number' => 'STU-2026-004',
                    'payment' => 25000,
                    'payment_method' => 'cash',
                    'status' => 'partial',
                ],
                [
                    'student_number' => 'STU-2026-006',
                    'payment' => 0,
                    'payment_method' => null,
                    'status' => 'unpaid',
                ],
                [
                    'student_number' => 'STU-2026-003',
                    'payment' => 42000,
                    'payment_method' => 'card',
                    'status' => 'partial',
                ],
            ];

            foreach ($invoiceScenarios as $index => $scenario) {
                $student = $students->firstWhere(
                    'student_number',
                    $scenario['student_number']
                );

                if (!$student) {
                    continue;
                }

                $studentFee = $studentFees[$student->id] ?? null;

                if (!$studentFee) {
                    continue;
                }

                $subtotal = (float) $studentFee->amount;
                $discount = (float) $studentFee->discount;
                $total = round($subtotal, 2);

                $paid = min(
                    (float) $scenario['payment'],
                    $total
                );

                $balance = round($total - $paid, 2);

                $invoice = Invoice::create([
                    'school_id' => $schoolId,
                    'student_id' => $student->id,
                    'invoice_number' => 'INV-2026-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                    'invoice_date' => '2026-09-22',
                    'due_date' => '2026-10-15',
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'total' => $total,
                    'paid' => $paid,
                    'balance' => $balance,
                    'status' => $scenario['status'],
                    'created_by' => $createdBy,
                ]);

                $invoiceItem = InvoiceItem::create([
                    'school_id' => $schoolId,
                    'invoice_id' => $invoice->id,
                    'fee_category_id' => $studentFee->feeStructure->fee_category_id,
                    'description' => $studentFee->feeStructure->feeCategory->name,
                    'quantity' => 1,
                    'amount' => $total,
                ]);

                /*
                 * -----------------------------------------------------
                 * Payment + allocation + movement
                 * -----------------------------------------------------
                 */
                if ($paid > 0) {
                    $payment = Payment::create([
                        'school_id' => $schoolId,
                        'invoice_id' => $invoice->id,
                        'amount' => $paid,
                        'payment_method' => $scenario['payment_method'],
                        'transaction_reference' => 'DEMO-' . $invoice->invoice_number,
                        'paid_at' => '2026-09-22 10:00:00',
                        'received_by' => $createdBy,
                        'notes' => 'Clean SMS demo payment.',
                    ]);

                    PaymentAllocation::create([
                        'school_id' => $schoolId,
                        'payment_id' => $payment->id,
                        'invoice_id' => $invoice->id,
                        'invoice_item_id' => $invoiceItem->id,
                        'amount' => $paid,
                        'allocated_at' => '2026-09-22 10:00:00',
                        'allocated_by' => $createdBy,
                    ]);

                    FinanceMovement::create([
                        'school_id' => $schoolId,
                        'student_id' => $student->id,
                        'invoice_id' => $invoice->id,
                        'invoice_item_id' => $invoiceItem->id,
                        'payment_id' => $payment->id,
                        'movement_type' => 'payment',
                        'reference_type' => 'payment',
                        'reference_id' => $payment->id,
                        'amount' => $paid,
                        'direction' => 'credit',
                        'previous_balance' => $total,
                        'new_balance' => $balance,
                        'performed_by' => $createdBy,
                        'reason' => 'Demo payment',
                        'notes' => 'Clean SMS demo finance movement.',
                    ]);

                    /*
                     * One processed refund gives the Refund Report
                     * meaningful demo data.
                     */
                    if ($student->student_number === 'STU-2026-002') {
                        $refundAmount = 5000;

                        $refund = PaymentRefund::create([
                            'school_id' => $schoolId,
                            'payment_id' => $payment->id,
                            'student_id' => $student->id,
                            'amount' => $refundAmount,
                            'reason' => 'Demo refund for payment adjustment.',
                            'status' => 'processed',
                            'requested_by' => $createdBy,
                            'approved_by' => $createdBy,
                            'processed_by' => $createdBy,
                            'requested_at' => '2026-09-22 11:00:00',
                            'approved_at' => '2026-09-22 11:15:00',
                            'processed_at' => '2026-09-22 11:30:00',
                            'reference' => 'REF-DEMO-0001',
                            'notes' => 'Clean SMS demo refund.',
                        ]);

                        FinanceMovement::create([
                            'school_id' => $schoolId,
                            'student_id' => $student->id,
                            'invoice_id' => $invoice->id,
                            'invoice_item_id' => $invoiceItem->id,
                            'payment_id' => $payment->id,
                            'movement_type' => 'refund',
                            'reference_type' => 'payment_refund',
                            'reference_id' => $refund->id,
                            'amount' => $refundAmount,
                            'direction' => 'debit',
                            'previous_balance' => $balance,
                            'new_balance' => round($balance + $refundAmount, 2),
                            'performed_by' => $createdBy,
                            'reason' => 'Processed demo refund',
                            'notes' => 'Clean SMS demo refund movement.',
                        ]);
                    }
                }
            }

            $this->command?->info('Finance demo data created.');
        });
    }
}