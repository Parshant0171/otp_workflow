<?php

namespace Jgu\Wfotp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Yajra\Auditable\AuditableWithDeletesTrait;

class WfoBaseModel extends Model
{
    use HasFactory;
    use AuditableWithDeletesTrait, SoftDeletes;

}