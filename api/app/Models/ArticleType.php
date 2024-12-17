<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleType extends Model
{
    use HasFactory;
    protected $fillable = [
        'designation',
        'description',
        'status',
        'company_id'
    ];

    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function article(){
        return $this->hasMany(Article::class);
    }
}
