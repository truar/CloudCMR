<?php

namespace CloudCMR;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'phones';

    /**
     * Get the member that owns the phone.
     */
    public function member() {
        return $this->belongsTo('CloudCMR\Member');
    }
}
