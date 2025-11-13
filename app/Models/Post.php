<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'excerpt',
        'content', 
        'author_name',
        'published_at',
        'category_id',
        'user_id',
        'image',
    ];

    protected $dates = [
        'published_at',
        'created_at', 
        'updated_at',
        'deleted_at'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    protected static function boot(){
        parent::boot();

        static::creating(function ($post) {
            if (auth()->check()) {
                $post->user_id = auth()->id();
                $post->author_name = auth()->user()->name;
            }
        });
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function getCategoryNameAttribute(){
        return $this->category ? $this->category->name : 'Без категории';
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }
}
