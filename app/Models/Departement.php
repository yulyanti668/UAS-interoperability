<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departement extends Model{
    // Yulyanti - D112331025
    protected $table = "departments";

    protected $fillable = ['name'];
 
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}