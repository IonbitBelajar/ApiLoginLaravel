<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Passport\PersonalAccessToken;
// use Laravel\Sanctum\Sanctum;
use Laravel\Sanctum\HasApiTokens;
use app\Models\User;
use Illuminate\Support\Facades\Auth;
// use App\Models\Sanctum\PersonalAccessToken;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {

        //   Sanctum::usePersonalAccessTokenModel
        //   $password = Crypt::decryptString(User::where('id',$id_user)->first()->password);
        // return $request->user();
        // $token = PersonalAccessToken::where('token', $token)->first();
        // $user = $token->tokenable_id;
        // $password = User::where('id',$user)->first()->name;

                $url = 'https://spa.test/reset-password?token='.$token;

        $this->notify(new ResetPasswordNotification($url));
    }
}
