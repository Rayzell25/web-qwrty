@extends('layouts.app')

@section('title', 'Leaderboard — ' . setting('site_name', config('app.name', 'RPD')))

@section('content')
<div class="container">
    <h1 class="h3 mb-4">Leaderboard</h1>

    @if ($entries->isEmpty())
        <div class="alert alert-info">Belum ada data leaderboard.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" style="width: 80px;">Rank</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Kota</th>
                        <th scope="col" class="text-end">Skor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entries as $index => $entry)
                        @php
                            $displayRank = $entry->rank ?? ($entries->firstItem() + $index);
                            $avatar = $entry->image
                                ? \Illuminate\Support\Facades\Storage::disk('public')->url($entry->image)
                                : 'https://placehold.co/48x48?text=' . urlencode(mb_substr($entry->name, 0, 1));
                        @endphp
                        <tr>
                            <td>
                                @if ($displayRank == 1)
                                    <span class="badge bg-warning text-dark">🥇 1</span>
                                @elseif ($displayRank == 2)
                                    <span class="badge bg-secondary">🥈 2</span>
                                @elseif ($displayRank == 3)
                                    <span class="badge" style="background:#cd7f32;color:#fff;">🥉 3</span>
                                @else
                                    <span class="fw-bold">{{ $displayRank }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $avatar }}" alt="{{ $entry->name }}" width="40" height="40"
                                         class="rounded-circle object-cover">
                                    <span>{{ $entry->name }}</span>
                                </div>
                            </td>
                            <td>{{ $entry->city ?? '-' }}</td>
                            <td class="text-end fw-bold">{{ number_format($entry->score) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $entries->links() }}
        </div>
    @endif
</div>
@endsection
