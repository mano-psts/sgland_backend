<?php

namespace Modules\Tenat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FaultReport extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Tenat\Database\factories\FaultReportFactory::new();
    }


    public function fault_category(){
        return $this->hasOne(FaultCategory::class, 'id', 'fault_category_id');
    }
}
