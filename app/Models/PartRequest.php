<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartRequest extends Model
{
    protected $fillable = [
        'user_id',
        'requested_part_name',
        'reference',
        'vehicle_make',
        'vehicle_model',
        'vehicle_year',
        'description',
        'contact_name',
        'contact_email',
        'contact_phone',
        'status',
        'admin_notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'new'         => 'Nouvelle',
            'in_progress' => 'En cours',
            'found'       => 'Trouvée',
            'closed'      => 'Fermée',
            default       => $this->status,
        };
    }
}
