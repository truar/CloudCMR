<template>
    <div :id='addressId'>
        <h5>Adresse {{ addressKey }} <a href="#" v-on:click.stop.prevent="$emit('delete-address-form', addressId)"><i class="fas fa-times-circle"></i></a></h5>
        <div class="form-group row">
            <label :for="addressStreet" class="col-sm-3 col-form-label">Rue</label> 
            <div class="col-sm-7">
                <input :name="addressStreet" type="text" value="" :id="addressStreet" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label :for="addressCity" class="col-sm-3 col-form-label">Ville</label> 
            <div class="col-sm-7">
                <input :name="addressCity" type="text" value="" :id="addressCity" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label :for="addressPostCode" class="col-sm-3 col-form-label">Code postal</label> 
            <div class="col-sm-7">
                <input :name="addressPostCode" type="text" value="" :id="addressPostCode" class="form-control">
            </div>
        </div>
        <hr/>
    </div>
</template>

<script>
    export default {
        props: ['addressKey'],
        computed: {
            addressStreet: function() {
                return `addresses[${this.addressKey}][street]`;
            },
            addressCity: function() {
                return `addresses[${this.addressKey}][city]`;
            },
            addressPostCode: function() {
                return `addresses[${this.addressKey}][post_code]`;
            },
            addressId: function() {
                return `address-form-${this.addressKey}`;
            }
        },
    }
</script>