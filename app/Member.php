<?php

namespace CloudCMR;

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
        return $this->hasMany('CloudCMR\Phone');
    }

    /**
     * Save the phones into the DB.
     */
    public function savePhones() {
        if(isset($this->phones[0])) {
            foreach($this->phones as $phone) {
                $this->phones()->save($phone);
            }
        }
    }

    /**
     * Save the addresses into the DB
     */
    public function saveAddresses($addresses) {
        if(isset($addresses)) {
            // Loop to save the addresses
            foreach($addresses as $address) {
                $this->addAddress($address);
            }
        }
    }

}
