@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <?php foreach(Cart::content() as $row) :?>

                    <tr>
                        <td>
                            <p><strong><?php echo $row->name; ?></strong></p>
                            <p><?php echo ($row->options->has('size') ? $row->options->size : ''); ?></p>
                        </td>
                        <td><input type="text" value="<?php echo $row->qty; ?>"></td>
                        <td>$<?php echo $row->price; ?></td>
                        <td>$<?php echo $row->total; ?></td>
                    </tr>

                <?php endforeach;?>
                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
