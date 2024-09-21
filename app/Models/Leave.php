<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model{
    // Tirta Subagja - D112021114
    protected $table = "leaves";

    protected $fillable = [
        'employee_id', 'start_date', 'end_date', 'reason', 'status'
    ];

    // Status cuti
    const STATUS_PENDING = 'Menunggu';
    const STATUS_APPROVED = 'Disetujui';
    const STATUS_REJECTED = 'Ditolak';

    // Relasi dengan Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}