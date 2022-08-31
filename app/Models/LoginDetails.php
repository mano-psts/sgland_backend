<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginDetails extends Model
{
    use HasFactory;
    public function customer(){
        return $this->hasOne(Customers::class, 'id', 'customer_id');
    }
}
