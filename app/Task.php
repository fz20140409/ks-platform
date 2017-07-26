<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Task extends Model
{
    //
    protected $guarded=[];
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table =  env('TABLE_PREFIX','admin_').'tasks';
    }
}
