<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberModel extends Model
{
    protected $table            = 'members';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'member_code',
        'name',
        'class_name',
        'phone',
        'status',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
}
