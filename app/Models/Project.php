<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'html_code',
        'css_code',
        'js_code',
        'technology_stack',
        'status',
        'is_temporary',
        'expires_at',
    ];

    protected $casts = [
        'technology_stack' => 'array',
        'is_temporary' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeTemporary($query)
    {
        return $query->where('is_temporary', true);
    }

    public function scopePermanent($query)
    {
        return $query->where('is_temporary', false);
    }

    public function isExpired()
    {
        return $this->is_temporary && $this->expires_at && now()->isAfter($this->expires_at);
    }
}
