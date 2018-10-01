@extends('layouts.header-dashboard')

@section('content')
<div class="container">
    <h1 class="mb-4">Kotak Masuk</h1>
    @include('messenger.partials.flash')

    <div class="row">
        <div class="col-md-12">
            <table class="datatable display dt-responsive no-wrap" width="100%">
        	<thead>
                <tr>
                    <th>Subject</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
    			@each('messenger.partials.thread', $threads, 'thread', 'messenger.partials.no-threads')
    		</tbody>
            </table>
        </div>
    </div>

</div>
@stop