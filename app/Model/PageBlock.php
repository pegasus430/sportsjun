<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PageBlock extends Model
{
    protected $table = 'page_blocks';

    static $BLOCK_TYPES = [
        'html'=>'html',
        'infolist'=>'infolist'
    ];

    protected $fillable =[
        'user_id',
        'container_id',
        'page_id',
        'title',
        'data',
        'type'
    ];

    protected $cats =[
        'data'=>'array'
    ];

    public function pageBlocks()
    {
        return $this->belongsTo(Page::class, 'page_id', 'id');
    }

}
