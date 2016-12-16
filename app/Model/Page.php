<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    static $STATE_PUBLISHED = 'published';

    static $TEMPLATES = [1 => 'home.layout'];

    protected $cats = [
        'data' => 'array'
    ];
    protected $dates = ['publish_date'];
    protected $fillable = ['title', 'template_id', 'linkname', 'publish_state'];


    public function pageBlocks()
    {
        return $this->hasMany(PageBlock::class, 'page_id', 'id');
    }

    public function scopePublished($query)
    {
        return $query->where('publish_state', '=', self::$STATE_PUBLISHED);
    }

}
