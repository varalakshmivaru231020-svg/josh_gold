{{--
    Cover image of an update. A post without an image (or whose file is missing) shows
    a gold placeholder instead. Expects: $post (App\Models\Blog); optional $size ('sm' for thumbnails),
    $eager (true for the first image on the page).
--}}
@php $coverUrl = $post->image_url; @endphp
<div class="upd-cover{{ ! empty($size) ? ' upd-cover--' . $size : '' }}{{ $coverUrl ? '' : ' upd-cover--empty' }}">
    @if($coverUrl)
        <img src="{{ $coverUrl }}" alt="" @if(empty($eager)) loading="lazy" @endif decoding="async"
             onerror="this.parentNode.classList.add('upd-cover--empty'); this.remove();">
    @endif
</div>
