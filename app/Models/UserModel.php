<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'fullname',
        'email',
        'school',
        'role',
        'phone',
        'password',
        'is_active'
    ];

    protected $useTimestamps = true;
}
