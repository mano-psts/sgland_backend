<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaultReport extends Model
{
    use HasFactory;
    public function fault_category(){
        return $this->hasOne(FaultCategory::class, 'id', 'fault_category_id');
    }
}
