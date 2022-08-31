<?php

namespace Modules\Tenat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EquipmentFaults extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Tenat\Database\factories\EquipmentFaultsFactory::new();
    }
}
