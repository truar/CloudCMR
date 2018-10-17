<div class="col-md-4">
    <div class="text-center">
        <a class="btn btn-outline-primary" href="{{ route('members.home') }}" role="button">Ajouter un nouveau membre</a>
    </div>
    <table class="table table-hover" id='members-table'>
        <thead>
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Prénom</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($members as $m)
                <tr>
                    <td>{{ $m->lastname }}</td>
                    <td>{{ $m->firstname }}</td>
                    <td><a href="{{ route('members.edit', ['member' => $m]) }}"><i class="fas fa-pencil-alt"></i></a></td>
                </tr>
            @empty
                <p>No members</p>
            @endforelse
            </tr>
        </tbody>
    </table>
</div>