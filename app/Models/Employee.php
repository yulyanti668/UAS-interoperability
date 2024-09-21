<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model{
    // Tirta Subagja - D112021114
    protected $table = "employees";

    protected $fillable = [
        'user_id', 'name', 'department_id', 'position_id', 'hire_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Departement::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}