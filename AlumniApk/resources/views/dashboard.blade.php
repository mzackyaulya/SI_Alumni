@extends('layout.main')

@section('title','Dashboard')

@section('content')
<div class="container py-4">
  <h3 class="fw-bold mb-3">🗺️ Peta Lowongan Kerja per Provinsi</h3>
  <p class="text-muted">
    Semakin gelap warnanya, semakin banyak lowongan di provinsi tersebut.
    Arahkan ke titik kota untuk melihat jumlah lowongan; klik titik untuk membuka katalog terfilter kota.
  </p>

  {{-- PETA --}}
  <div id="map" style="height:520px;border-radius:12px;overflow:hidden" class="mb-4"></div>

  {{-- GRAFIK ALUMNI --}}
  <div class="row mb-4">
      <div class="col-lg-7 mb-3">
          <div class="card shadow-sm h-100">
              <div class="card-header bg-light">
                  <strong>Grafik Lulusan per Angkatan</strong>
              </div>
              <div class="card-body" style="height:260px;">
                  <canvas id="chartAngkatan"></canvas>
              </div>
          </div>
      </div>

      <div class="col-lg-5 mb-3">
          <div class="card shadow-sm h-100">
              <div class="card-header bg-light">
                  <strong>Status Alumni (Kerja / Kuliah / Lainnya)</strong>
              </div>
              <div class="card-body" style="height:260px;">
                  <canvas id="chartStatus"></canvas>
              </div>
          </div>
      </div>
  </div>

  {{-- Ringkasan Top Provinsi --}}
  @if(!empty($topProv))
  <div class="card shadow-sm">
    <div class="card-header bg-light"><strong>Top Provinsi</strong></div>
    <div class="card-body p-0">
      <table class="table table-striped mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:60px">No</th>
            <th>Provinsi</th>
            <th style="width:180px" class="text-end">Jumlah Lowongan</th>
          </tr>
        </thead>
        <tbody>
          @foreach($topProv as $p => $v)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ ucwords(strtolower($p)) }}</td>
              <td class="text-end">{{ $v }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @endif
</div>

{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
  .legend {
      background:#fff;
      padding:.5rem .75rem;
      border-radius:.5rem;
      box-shadow:0 2px 10px rgba(0,0,0,.07);
      line-height:1.2;
  }
  .legend i {
      width: 14px;
      height: 14px;
      display:inline-block;
      margin-right:6px;
      border-radius:3px;
      border:1px solid rgba(0,0,0,.08);
  }
  .btn-icon i { margin-right:.4rem; }
</style>

{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  // ===== Data dari Controller =====
  const provCounts = @json($provCounts ?? []);
  const maxVal     = Number(@json($maxVal ?? 0));
  const cityPoints = @json($cityPoints ?? []);

  // ===== Util =====
  function normProv(s){ return String(s||'').toUpperCase().replace(/\s+/g,' ').trim(); }

  // ambil nama provinsi dari properti yang umum dipakai di berbagai geojson Indonesia
  function getProvName(props) {
    const keys = ['NAME_1','provinsi','Propinsi','PROPINSI','shapeName','state','name','NAME','WADMPR','WADMKK'];
    for (const k of keys) if (props[k]) return String(props[k]).replace(/^\s*(Provinsi|Province)\s+/i,'');
    for (const [k,v] of Object.entries(props)) {
      if (typeof v === 'string' && v.length >= 3 && v.length <= 40) return v.replace(/^\s*(Provinsi|Province)\s+/i,'');
    }
    return '';
  }

  function colorFor(v){
    if (!maxVal || v<=0) return '#eef2ff';
    const t = Math.min(1, v/maxVal);
    const r = Math.round(99  + (139-99)*t);
    const g = Math.round(102 + (92 -102)*t);
    const b = Math.round(241 + (246-241)*t);
    return `rgb(${r},${g},${b})`;
  }

  // ===== BATASI PETA HANYA INDONESIA =====
  const indoBounds = L.latLngBounds(
    L.latLng(-11.5, 94.5),   // barat-daya (lat, lng)
    L.latLng(  6.5, 141.5)   // timur-laut
  );

  const map = L.map('map', {
    maxBounds: indoBounds,        // cegah panning keluar
    maxBoundsViscosity: 1.0,      // "memantul" ketika ditarik keluar
    minZoom: 4.2,                 // cegah terlalu jauh keluar
    zoomSnap: 0.25
  }).fitBounds(indoBounds);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap',
    maxZoom: 12,
    minZoom: 4,
    noWrap: true,                  // cegah wrap ke sisi lain dunia
    bounds: indoBounds
  }).addTo(map);

  // MASK: gelapkan area di luar Indonesia biar fokus
  const worldRing = [[-90,-180],[90,-180],[90,180],[-90,180]];
  const indoRing  = [[-11.5,94.5],[-11.5,141.5],[6.5,141.5],[6.5,94.5]];
  L.polygon([worldRing, indoRing], {
    stroke: false,
    fillColor: '#000',
    fillOpacity: 0.25,
    interactive: false
  }).addTo(map);

  // ===== Muat choropleth provinsi =====
  fetch('{{ asset('geo/indonesia-provinces.json') }}')
    .then(r => r.json())
    .then(geo => {
      const provLayer = L.geoJSON(geo, {
        style: f => {
          const raw = getProvName(f.properties);
          const key = normProv(raw);
          const val = provCounts[key] || 0;
          return { color:'#fff', weight:1, fillColor: colorFor(val), fillOpacity:.9 };
        },
        onEachFeature: (f, lyr) => {
          const raw = getProvName(f.properties);
          const key = normProv(raw);
          const val = provCounts[key] || 0;
          lyr.bindTooltip(`<strong>${raw || '(Tanpa nama)'}</strong><br>Lowongan: ${val}`, {sticky:true});
        }
      }).addTo(map);

      map.fitBounds(indoBounds, {padding:[10,10]});
    });

  // ===== Titik kota (bubble) =====
  function cityRadius(count){ return count<=0 ? 4 : Math.max(6, Math.min(18, 6 + Math.log(count+1)*4)); }

  const cityLayer = L.layerGroup().addTo(map);
  cityPoints.forEach(p => {
    L.circleMarker([p.lat, p.lng], {
      radius: cityRadius(p.count),
      weight: 1,
      color: '#1F2937',
      fillColor: '#F59E0B',
      fillOpacity: .85
    })
    .bindTooltip(`<strong>${p.name}</strong><br>Lowongan: ${p.count}`, {sticky:true})
    .on('click', () => {
      window.location.href = `{{ route('lowongan.index') }}?q=${encodeURIComponent(p.name)}`;
    })
    .addTo(cityLayer);
  });

  // ===== Legenda =====
  const legend = L.control({position:'bottomright'});
  legend.onAdd = function(){
    const div = L.DomUtil.create('div','legend');
    const steps = 5;
    let html = '<div><strong>Jumlah (relatif)</strong></div>';
    for (let i=0;i<=steps;i++){
      const v = Math.round((i/steps)*maxVal);
      html += `<div><i style="background:${colorFor(v)}"></i>${v}</div>`;
    }
    div.innerHTML = html;
    return div;
  };
  legend.addTo(map);

  L.control.layers(null, {'Titik Kota (Lowongan)': cityLayer}, {collapsed:true}).addTo(map);
</script>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ===== DATA UNTUK GRAFIK DARI CONTROLLER =====
    const angkatanLabels = @json($angkatanLabels ?? []);
    const angkatanData   = @json($angkatanData ?? []);

    const statusObj    = @json($statusCounts ?? []);
    const statusLabels = Object.keys(statusObj);
    const statusData   = Object.values(statusObj);

    // ===== GRAFIK BATANG LULUSAN PER ANGKATAN =====
    const ctxAngkatan = document.getElementById('chartAngkatan');
    if (ctxAngkatan && angkatanLabels.length) {
        new Chart(ctxAngkatan, {
            type: 'bar',
            data: {
                labels: angkatanLabels,
                datasets: [{
                    label: 'Jumlah Alumni',
                    data: angkatanData,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
    }

    // ===== GRAFIK DONUT STATUS ALUMNI =====
    const ctxStatus = document.getElementById('chartStatus');
    if (ctxStatus && statusLabels.length) {
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: statusLabels.map(s => s.toUpperCase()),
                datasets: [{
                    data: statusData,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
</script>
@endsection
