<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Lecturize\Addresses\Traits\HasAddresses;

class Member extends Model
{

    use HasAddresses;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'members';

    /**
     * Get the phones associated to the member
     */
    public function phones() {
        return $this->hasMany('App\Phone');
    }

}
