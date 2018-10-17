<?php

namespace CloudCMR;

use Illuminate\Database\Eloquent\Model;

class Transportation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transportations';

    /**
     * Get the event that owns the transportation.
     */
    public function event() {
        return $this->belongsTo('CloudCMR\Event');
    }

}
