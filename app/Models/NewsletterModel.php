<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsletterModel extends Model
{
    protected $table = 'newsletter';
    protected $primaryKey = 'id';
    protected $allowedFields = ['email'];
}
