@extends('layouts.app')

@section('content')
<section class="container">
    <div class="row justify-content-center text-center">
        <h1>Dashboard</h1>
        <div class="col-md-12">
            <div class="card-deck col-md-6">
                <div class="card">
                    <img class="card-img-top img-fluid" src="img/member.png" alt="Gestion des membres">
                    <div class="card-body">
                        <h5 class="card-title">Gestion des membres</h5>
                        <p class="card-text">Administrer vos membres (inscription, modification et suppression)</p>
                        <a href="{{ route('members.home') }}" class="btn btn-primary">Gérer les membres</a>
                    </div>
                </div>
                @hasrole('admin')
                <div class="card">
                    <img class="card-img-top img-fluid" src="img/calendar.png" alt="Gestion des sorties">
                    <div class="card-body">
                        <h5 class="card-title">Gestion des sorties</h5>
                        <p class="card-text">Administrer vos sorties (création, modification et suppression)</p>
                        <a href="{{ route('members.home') }}" class="btn btn-primary">Gérer les sorties</a>
                    </div>
                </div>
                @endhasrole
            </div>
        </div>
    </div>
</section>
@endsection
