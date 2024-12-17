<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Role;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'departament_id',
        'company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function schedule(){
        return $this->hasMany(Schedule::class);
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function departament(){
        return $this->belongsTo(Departament::class);
    }
    public function mini_schedule(){
        return $this->hasOne(MiniSchedule::class);
    }

     public function professor()
    {
        return $this->belongsTo(Role::class);
    }


    public function invoice_receipt(){
        return $this->hasOne(InvoiceReceipt::class);
    }

}
