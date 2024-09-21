<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model{
    // Yulyanti - D112331025
    protected $table = "positions";

    protected $fillable = ['title'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}