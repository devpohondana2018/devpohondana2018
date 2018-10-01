@if (\Session::has('success'))
<div class="alert alert-success">
    <strong>{{ \Session::get('success') }}</strong>
</div>
@endif
@if (\Session::has('message'))
<div class="alert alert-info">
    <strong>{{ \Session::get('message') }}</strong>
</div>
@endif
@if (session('status'))
<div class="alert alert-success">
    <strong>{{ session('status') }}</strong>
</div>
@endif