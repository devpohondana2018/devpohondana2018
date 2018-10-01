<div class="card card-default">
    <div class="card-body">
        <h5 class="card-title">{{ $message->user->name }}</h5>
        <p class="card-text">{{ $message->body }}</p>
        <div class="card-footer text-muted">
            <small>Posted {{ $message->created_at->diffForHumans() }}</small>
        </div>
    </div>
</div>