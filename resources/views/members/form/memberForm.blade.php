<div class="col-md-8">
    <div class="text-center">
        <h2>Informations sur le membre</h2>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{ Form::model($member, ['route' => [$route, $member->id]]) }}
        <div class="form-group row">
            {{ Form::label('lastname', 'Nom', ['class' => 'col-sm-3 col-form-label']) }}
            <div class="col-sm-8">
                {{ Form::text('lastname', $member->lastname, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="form-group row">
            {{ Form::label('firstname', 'Prénom', ['class' => 'col-sm-3 col-form-label']) }}
            <div class="col-sm-8">
                {{ Form::text('firstname', $member->firstname, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="form-group row">
            {{ Form::label('gender', 'Genre', ['class' => 'col-sm-3 col-form-label']) }}
            <div class="col-sm-8">
                {{ Form::select('gender', ['male' => 'Homme', 'female' => 'Femme'], $member->gender, ['class' => 'custom-select mr-sm-2']) }}
            </div>
        </div>
        <div class='form-group row'>
            {{ Form::label('birthdate', 'Date de naissance', ['class' => 'col-sm-3 col-form-label']) }}
            <div class="col-sm-8">
                {{ Form::text('birthdate', null, ['class' => 'form-control']) }}
                <small id="birthdateHelp" class="form-text text-muted">Exemple : 25/05/1985</small>
            </div>
        </div>
        <div class="form-group row">
            {{ Form::label('email', 'Email', ['class' => 'col-sm-3 col-form-label']) }}
            <div class="col-sm-8">
                {{ Form::email('email', $member->email, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="form-group row">
            {{ Form::label('uscaNumber', 'Numéro USCA', ['class' => 'col-sm-3 col-form-label']) }}
            <div class="col-sm-8">
                {{ Form::text('uscaNumber', $member->uscaNumber, ['class' => 'form-control']) }}
            </div>
        </div>
        <hr/>
        <h4>Téléphones</h4>
        @foreach($member->phones as $key=>$phone)
            <div class="phone-form form-group row" id="phone-{{ $phone->id }}">
                {{ Form::label('phones[][number]', 'Numéro de téléphone ', ['class' => 'col-sm-3 col-form-label']) }}
                <div class="col-sm-7">
                    {{ Form::text('phones[][number]', $phone->number, ['class' => 'form-control']) }}
                </div>
                <div class="col-sm-1">
                    <a href="{{ route('phones.delete', ['member' => $member, 'phone' => $phone]) }}" v-on:click.stop.prevent="deletePhone('{{ route('phones.delete', ['member' => $member, 'phone' => $phone]) }}', $event)"><i class="fas fa-times-circle"></i></a>
                </div>
            </div>
        @endforeach
        <button class='btn btn-success' id='add-phone-form' type='button' v-on:click="addPhoneForm"><i class="fas fa-plus-circle"></i> Ajouter un téléphone</button>
        <hr/>
        <h4>Adresses</h4>
        @foreach($member->addresses()->get() as $key=>$address)
            <div id='address-{{ $address->id }}' class='address-form'>
                <h5>Adresse {{ $key + 1 }} <a href="#" v-on:click.stop.prevent="deleteAddress('{{ route('addresses.delete', ['member' => $member, 'address' => $address]) }}', $event)"><i class="fas fa-times-circle"></i></a></h5>
                <div class="form-group row" id="address-street-{{ $address->id }}">
                    <label for="addresses[{{$key}}][street]" class="col-sm-3 col-form-label">Rue</label> 
                    <div class="col-sm-7">
                        <input name="addresses[{{$key}}][street]" type="text" value="{{ $address->street }}" id="addresses[{{$key}}][street]" class="form-control">
                    </div>
                </div>
                <div class="form-group row" id="address-city-{{ $address->id }}">
                    <label for="addresses[{{$key}}][city]" class="col-sm-3 col-form-label">Ville</label> 
                    <div class="col-sm-7">
                        <input name="addresses[{{$key}}][city]" type="text" value="{{ $address->city }}" id="addresses[{{$key}}][city]" class="form-control">
                    </div>
                </div>
                <div class="form-group row" id="address-postalCode-{{ $address->id }}">
                    <label for="addresses[{{$key}}][post_code]" class="col-sm-3 col-form-label">Code postal</label> 
                    <div class="col-sm-7">
                        <input name="addresses[{{$key}}][post_code]" type="text" value="{{ $address->post_code }}" id="addresses[{{$key}}][post_code]" class="form-control">
                    </div>
                </div>
                <hr/>
            </div>
        @endforeach
        <button class='btn btn-success' id='add-address-form' type='button' v-on:click="addAddressForm"><i class="fas fa-plus-circle"></i> Ajouter une adresse</button>

        <div class="form-group row">
            <div class="col-sm-10" style="margin-top: 10px;">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>  
        </div>
    {{ Form::close() }}
</div>