@extends('pages.layout')

@section('pages-title')
    {{ $page->title }} - Gallery
@endsection

@section('meta-img')
    {{ $page->image ? $page->image->thumbnailUrl : asset('images/logo.png') }}
@endsection

@section('meta-desc')
    {{ $page->summary ? $page->summary : Config::get('mundialis.settings.site_desc') }}
@endsection

@section('pages-content')
    {!! breadcrumbs([
        $page->category->subject['name'] => $page->category->subject['key'],
        $page->category->name => $page->category->subject['key'] . '/categories/' . $page->category->id,
        $page->title => $page->url,
        'Gallery' => $page->url . '/gallery',
    ]) !!}

    @include('pages._page_header', ['section' => 'Gallery'])

    <p>This page currently has {{ $page->images()->visible(Auth::check() ? Auth::user() : null)->count() }} gallery pieces, totalling a value of ${{ $page->images()->visible(Auth::check() ? Auth::user() : null)->sum('sale_value') }}.</p>
    <p>
        {{ $page->images()->where('transfer_type', '=', 'commission')->count() }} of these pieces were commisioned, for a total value of ${{ $page->images()->visible(Auth::check() ? Auth::user() : null)->where('transfer_type', '=', 'commission')->sum('sale_value') }}.<br>
        {{ $page->images()->where('transfer_type', '=', 'art Trade')->count() }} of these pieces were traded for, for a total value of ${{ $page->images()->visible(Auth::check() ? Auth::user() : null)->where('transfer_type', '=', 'art Trade')->sum('sale_value') }}.<br>
        {{ $page->images()->where('transfer_type', '=', 'owner')->count() }} of these pieces were drawn by their current owner, for a total estimated value of ${{ $page->images()->visible(Auth::check() ? Auth::user() : null)->where('transfer_type', '=', 'owner')->sum('sale_value') }}.<br>
        {{ $page->images()->where('transfer_type', '=', 'art game')->count() }} of these pieces were obtained during art games.<br>
        {{ $page->images()->where('transfer_type', '=', 'freebie')->count() }} of these pieces were gifted for free.<br>
    </p>

    <p> 
        This character originally had {{ $page->images()->where('transfer_type', '=', 'original')->count() }} images upon purchase.
    </p>

    <p>
        The following are all the images associated with this page. Click an image's thumbnail for more information about it.
    </p>
    <div>
        {!! Form::open(['method' => 'GET', 'class' => '']) !!}
        <div class="form-inline justify-content-end">
            <div class="form-group mb-3">
                {!! Form::text('creator_url', Request::get('creator_url'), [
                    'class' => 'form-control',
                    'placeholder' => 'Creator URL',
                ]) !!}
            </div>
            <div class="form-group ml-3 mb-3">
                {!! Form::select('creator_id', $users, Request::get('creator_id'), [
                    'class' => 'form-control selectize',
                    'placeholder' => 'Creator',
                ]) !!}
            </div>
        </div>
        <div class="form-inline justify-content-end">
            <div class="form-group mr-3 mb-3">
                {!! Form::select(
                    'sort',
                    [
                        'newest' => 'Newest First',
                        'oldest' => 'Oldest First',
                    ],
                    Request::get('sort') ?: 'newest',
                    ['class' => 'form-control'],
                ) !!}
            </div>
            <div class="form-group mb-3">
                {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    {!! $images->render() !!}

    <div class="row ">
        @foreach ($images as $image)
            {!! $loop->remaining + 1 == $loop->count % 4 ? '<div class="my-auto col mobile-hide"></div>' : '' !!}
            <div class="col-md-3 mb-2 text-center">
                <a href="{{ url('pages/get-image/' . $page->id . '/' . $image->id) }}" class="image-link"><img
                        src="{{ $image->thumbnailUrl }}" class="img-thumbnail mw-100 mb-2"
                        style="{{ !$image->pivot->is_valid ? 'filter: grayscale(60%) opacity(50%);' : '' }}" />
                </a>
                @if ($image->pivot->is_ref || $image->pivot->is_featured || $image->pivot->is_surpressed)
                <div type="button" class="btn btn-primary">
                    @if ($image->pivot->is_ref)
                        <i class="fas fa-image" data-toggle="tooltip" title="This image is a reference" style="position-relative"></i>
                    @endif
                    @if ($image->pivot->is_featured)
                        <i class="fas fa-star" data-toggle="tooltip" title="This image is featured" style="position-relative"></i>
                    @endif
                    @if ($image->pivot->is_surpressed)
                        <i class="fas fa-ban" data-toggle="tooltip" title="This image is surpressed" style="position-relative"></i>
                    @endif
                </div>
                @endif
            </div>
            {!! $loop->count % 4 != 0 && $loop->last ? '<div class="my-auto col mobile-hide"></div>' : '' !!}
            {!! $loop->iteration % 4 == 0 ? '<div class="w-100"></div>' : '' !!}
        @endforeach
    </div>

    {!! $images->render() !!}

    <div class="text-center mt-4 small text-muted">{{ $images->total() }} result{{ $images->total() == 1 ? '' : 's' }}
        found.</div>
@endsection

@section('scripts')
    @parent
    @include('pages.images._info_popup_js')
@endsection
