<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Document extends Model
{
    protected $fillable = [
    'title',
    'file_path',
    'uploaded_by'
];


public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}


