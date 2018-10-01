<tr>
    <td>
        <a href="{{ route('messages.show', $thread->id) }}">
            {{ $thread->subject }} <span class="badge badge-primary badge-pill">({{ $thread->userUnreadMessagesCount(Auth::id()) }} unread)</span>
        </a>
    </td>
    <td>
        <a href="{{ route('messages.show', $thread->id) }}">
            <span>{{$thread->created_at->diffForHumans()}}</span>
        </a>
    </td>
</tr>
