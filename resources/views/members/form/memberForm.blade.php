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
        @foreach($member->phones as $phone)
            <div class="form-group row" id="phone-{{ $phone->id }}">
                {{ Form::label('phones[][number]', 'Numéro de téléphone', ['class' => 'col-sm-3 col-form-label']) }}
                <div class="col-sm-7">
                    {{ Form::text('phones[][number]', $phone->number, ['class' => 'form-control']) }}
                </div>
                <div class="col-sm-1">
                    <a href="{{ route('phones.delete', [$phone]) }}" v-on:click.stop.prevent="deletePhone('{{ route('phones.delete', [$phone]) }}', $event)"><i class="fas fa-times-circle"></i></a>
                </div>
            </div>
        @endforeach
        <button class='btn btn-success' id='add-phone-form' type='button' v-on:click="addPhoneForm"><i class="fas fa-plus-circle"></i> Ajouter un téléphone</button>
        <hr/>
        <h4>Adresses</h4>
        <div class="form-group row">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </div>
    {{ Form::close() }}
</div>