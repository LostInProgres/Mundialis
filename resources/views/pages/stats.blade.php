@extends('pages.layout')

@section('pages-title')
    {{ $page->title }}
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
    ]) !!}

    @include('pages._page_header', ['section' => 'Stats'])


    <table class="table table-sm category-table">
        <tbody>
            <thead>
                <tr><th>Page</th><th></th></tr>
            </thead>
            <tr>
                <td>Length</td>
                <td>{{ $page->version->length }}</td>
            </tr>
            <tr>
                <td>Length</td>
                <td>{{ $page->version->length }}</td>
            </tr>
            <tr>
                <td>Edits</td>
                <td>{{ $page->version->where('page_id', $page->id)->count() }}</td>
            </tr>
            <tr>
                <td>Incoming Links</td>
                <td>{{ $page->linked->count() }}</td>
            </tr>
            <tr>
                <td>Outgoing Links</td>
                <td>{{ $page->links->count() }}</td>
            </tr>
            <tr>
                <td>Relationships</td>
                <td>{{ $page->related->count() + $page->relationships->count() }}</td>
            </tr>
            <thead>
                <tr><th>Amount of Images</th><th></th></tr>
            </thead>
            <tr>
                <td>Total</td><td>{{ $page->images()->visible(Auth::check() ? Auth::user() : null)->count() }}</td>
            </tr>
            <tr>
                <td>Commissioned</td>
                <td>{{ $page->images()->where('transfer_type', '=', 'commission')->count() }}</td>
            </tr>
            <tr>
                <td>Art Trades</td>
                <td>{{ $page->images()->where('transfer_type', '=', 'art trade')->count() }}</td>
            </tr>
            <tr>
                <td>Owner-Made</td>
                <td>{{ $page->images()->where('transfer_type', '=', 'owner')->count() }}</td>
            </tr>
            <tr>
                <td>Art games</td>
                <td>{{ $page->images()->where('transfer_type', '=', 'art game')->count() }}</td>
            </tr>
            <tr>
                <td>Freebies</td>
                <td>{{ $page->images()->where('transfer_type', '=', 'freebie')->count() }}</td>
            </tr>
            <tr>
                <td>Upon Purchase</td>
                <td>{{ $page->images()->where('transfer_type', '=', 'original')->count() }}</td>
            </tr>
            <!-- value -->
            <thead>
                <tr><th>Value</th><th></th></tr>
            </thead>
            <tr>
                <td>Total</td>
                <td>${{ $page->images()->visible(Auth::check() ? Auth::user() : null)->sum('sale_value') }}</td>
            </tr>
            <tr>
                <td>Commissions</td>
                <td>${{ $page->images()->visible(Auth::check() ? Auth::user() : null)->where('transfer_type', '=', 'commission')->sum('sale_value') }}</td>
            </tr>
            <tr>
                <td>Art Trades</td>
                <td>${{ $page->images()->visible(Auth::check() ? Auth::user() : null)->where('transfer_type', '=', 'art trade')->sum('sale_value') }}</td>
            </tr>
            <tr>
                <td>Owner-Made</td>
                <td>${{ $page->images()->visible(Auth::check() ? Auth::user() : null)->where('transfer_type', '=', 'owner')->sum('sale_value') }}</td>
            </tr>
        </tbody>
    </table>

@endsection
