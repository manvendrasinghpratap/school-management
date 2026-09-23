<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffDocuments extends Model
{
    use HasFactory;
    protected $table = 'staff_documents';
    protected $fillable = ['school_id','staff_id','document_type','document_name','file_path','uploaded_by'];
    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function staff(): BelongsTo { return $this->belongsTo(Staff::class); }
    public function uploadedBy(): BelongsTo { return $this->belongsTo(User::class, 'uploaded_by'); }
}
