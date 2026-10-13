{{-- One update in a grid: cover image, date, title, excerpt. Expects: $post (App\Models\Blog) --}}
<a href="{{ route('updates.show', $post->slug) }}" class="upd-card">
    @include('blog._cover', ['post' => $post])
    <div class="upd-card__body">
        <time class="upd-date" datetime="{{ $post->created_at->toDateString() }}">{{ $post->created_at->format('d M Y') }}</time>
        <h3 class="upd-card__title">{{ $post->title }}</h3>
        @if(filled($post->excerpt))
            <p class="upd-card__excerpt">{{ $post->excerpt }}</p>
        @endif
        <span class="upd-more">Read update <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
    </div>
</a>
