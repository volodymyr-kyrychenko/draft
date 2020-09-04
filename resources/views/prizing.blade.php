@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($type > 0)
                        {{ __('Congratulations, you have won a prize!') }} {{ $name }}
                        @if ($amount > 0)
                            {{$amount}}
                        @endif

                        <a href="{{ route('receive', ['type' => $type, 'id' => $id])}}" class="btn btn-success pull-right" role="button"> Receive </a>

                        @if ($type == 1)
                            <a href="{{ route('convert', ['type' => $type, 'id' => $id])}}" class="btn btn-primary pull-right" role="button"> Convert </a>
                        @endif

                        <a href="{{ route('refuse', ['type' => $type, 'id' => $id])}}" class="btn btn-warning pull-right" role="button"> Refuse </a>
                    @else
                        {{ __('Something went wrong ... Please try again later') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
