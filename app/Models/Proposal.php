<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    protected $fillable = [
        'student_user_id',
        'student_name',
        'student_id',
        'whatsapp',
        'title',
        'abstract',
        'graduation_estimate',
        'topic_id',
        'recommended_topic',
        'selected_dosen_id',
        'kaprodi_recommended_dosen_id',
        'status',
        'krs_file',
        'rejection_reason',
        'kaprodi_recommendation_note',
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    public function selectedDosen()
    {
        return $this->belongsTo(Dosen::class, 'selected_dosen_id');
    }

    public function kaprodiRecommendedDosen()
    {
        return $this->belongsTo(Dosen::class, 'kaprodi_recommended_dosen_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_user_id');
    }
}