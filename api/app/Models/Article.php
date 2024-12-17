<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ArticleCategory;


class Article extends Model
{
    use HasFactory;
    protected $fillable = [
        "designation",
        "description",
        "price",
        "article_type_id",
        "article_category_id",
        "retention_id",
        "tax_id",
        "company_id",
    ];

    
    protected $hidden = [
        "created_at",
        "updated_at",
        "company_id"
    ];

    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function article_type(){
        return $this->belongsTo(ArticleType::class);
    }
    public function article_category(){
        return $this->belongsTo(ArticleCategory::class);
    }
    public function retention(){
        return $this->belongsTo(Retention::class);
    }
    public function tax(){
        return $this->belongsTo(Tax::class);
    } 
}
