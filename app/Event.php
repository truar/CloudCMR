<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'events';

    /**
     * Get the transportations associated to the event
     */
    public function transportations() {
        return $this->hasMany('App\Transportation');
    }

    /**
     * Save the transportations into the DB.
     */
    public function saveTransportations() {
        if(isset($this->transportations[0])) {
            foreach($this->transportations as $transportation) {
                $this->transportations()->save($transportation);
            }
        }
    }
}
