<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model{
    // Yulyanti - D112331025
    protected $table = "admins";

    protected $fillable = ['user_id', 'name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}