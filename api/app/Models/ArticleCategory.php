<?php

namespace App\Models;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;




class ArticleCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'designation',
        'description',
        'status',
        'unique',
        'company_id'
    ];

    protected $hidden = [
        "created_at",
        "updated_at",
        "company_id",
        "unique"
    ];

    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function article(){
        return $this->hasMany(Article::class);
    }
}
