<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'issued_by',
        'staff_id',
        'student_id',
    ];

    public function book() { return $this->belongsTo(Book::class, 'book_id'); }
    public function student() { return $this->belongsTo(Student::class, 'student_id'); }
    public function staff() { return $this->belongsTo(Staff::class, 'staff_id'); }
    public function issuedBy() { return $this->belongsTo(User::class, 'issued_by'); }
}
