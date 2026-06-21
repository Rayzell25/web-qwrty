@extends('layouts.app')

@section('title', 'FAQ — ' . setting('site_name', config('app.name', 'RPD')))

@section('content')
<div class="container py-5">
    <h1 class="h3 mb-4">Pertanyaan yang Sering Diajukan</h1>

    @if ($faqs->isEmpty())
        <div class="alert alert-info">Belum ada FAQ yang tersedia.</div>
    @else
        <div class="accordion" id="faqAccordion">
            @foreach ($faqs as $faq)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $faq->id }}">
                        <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $faq->id }}"
                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $faq->id }}">
                            {{ $faq->question }}
                        </button>
                    </h2>
                    <div id="collapse{{ $faq->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                         aria-labelledby="heading{{ $faq->id }}" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">{!! nl2br(e($faq->answer)) !!}</div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
