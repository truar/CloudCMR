@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            @forelse ($members as $m)
                <li><a href="{{ route('members.edit', ['member' => $m]) }}">{{ $m->lastname }} {{ $m->firstname }}</a></li>
            @empty
                <p>No members</p>
            @endforelse
        </div>
        <div class="col-md-9">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{ Form::model($member, ['route' => ['members.create']]) }}
                <div class="form-group row">
                    {{ Form::label('lastname', 'Nom', ['class' => 'col-sm-1 col-form-label']) }}
                    <div class="col-sm-5">
                        {{ Form::text('lastname', null, ['class' => 'form-control']) }}
                    </div>
                    {{ Form::label('firstname', 'Prénom', ['class' => 'col-sm-1 col-form-label']) }}
                    <div class="col-sm-5">
                        {{ Form::text('firstname', null, ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="form-group row">
                    {{ Form::label('gender', 'Genre', ['class' => 'col-sm-1 col-form-label']) }}
                    <div class="col-sm-5">
                        {{ Form::select('gender', ['male' => 'Homme', 'female' => 'Femme'], null, ['class' => 'custom-select mr-sm-2']) }}
                    </div>
                    {{ Form::label('birthdate', 'Date Naiss', ['class' => 'col-sm-1 col-form-label']) }}
                    <div class="col-sm-5">
                        {{ Form::date('birthdate', null, ['class' => 'form-control']) }}
                        <small id="birthdateHelp" class="form-text text-muted">Exemple : 25/05/1985</small>
                    </div>
                </div>
                <div class="form-group row">
                    {{ Form::label('email', 'Email', ['class' => 'col-sm-1 col-form-label']) }}
                    <div class="col-sm-11">
                        {{ Form::email('email', null, ['class' => 'form-control']) }}
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
