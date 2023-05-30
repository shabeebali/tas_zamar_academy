<x-layouts.frontend>
    {!! $content !!}
    @push('meta_tags')
        <meta name="title" content="{{$meta_title}}">
        <meta name="keywords" content="{{$meta_keywords}}">
        <meta name="description" content="{{$meta_description}}">
        <title>{{$meta_title}}</title>
    @endpush
</x-layouts.frontend>
