<template>
    <div id="phone-form" class="form-group row">
        <label for="phones[][number]" class="col-sm-3 col-form-label">Numéro de téléphone</label> 
        <div class="col-sm-7">
            <input name="phones[][number]" type="text" value="" id="phones[][number]" class="form-control">
        </div>
        <div class="col-sm-1">
            <a href="#" v-on:click.stop.prevent="deletePhoneForm"><i class="fas fa-times-circle"></i></a>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            deletePhoneForm: function() {
                $('#phone-form').remove();
            }
        }
    }
</script>