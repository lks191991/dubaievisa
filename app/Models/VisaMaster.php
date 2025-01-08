<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'visa_type',
        'insurance_mandate',
        'insurance_information',
        'stay_validity',
        'visa_validity',
        'adult_fees',
        'child_fees',
        'express_charges',
        'super_express_charges',
        'normal_processing_timeline',
        'express_processing_timeline',
        'super_express_processing_timeline',
        'description',
        'documents_checklist',
    ];

    protected $casts = [
        'documents_checklist' => 'array',
    ];
}

	

