<div class="row g-3 mb-4">
    @foreach($cards as $card)
        <div class="col-md-3"><div class="card h-100 shadow-sm"><div class="card-body"><div class="text-muted small">{{ $card['label'] }}</div><div class="fs-3 fw-bold">{{ $card['value'] }}</div></div></div></div>
    @endforeach
</div>
