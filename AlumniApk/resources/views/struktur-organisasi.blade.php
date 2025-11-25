@extends('layout.main')

@section('title', 'Struktur Organisasi')

@section('content')
<div class="py-5" style="background:#f5f6fa;">
    <div class="container">

        <h2 class="text-center fw-bold mb-5" style="font-size:32px;">Struktur Organisasi</h2>

        <style>
            .org-wrapper {
                max-width: 1100px;
                margin: 0 auto;
            }

            /* KOTAK UMUM */
            .org-head,
            .org-card {
                width: 240px;
                border-radius: 12px;
                padding: 15px;
                text-align: center;
                box-shadow: 0 4px 10px rgba(0,0,0,0.08);
                border: 1px solid #e2e2e3;
                background: #fff;
            }

            .org-head {
                background: #0d6efd;
                color: #fff;
                border: none;
            }

            /* LEVEL 1 */
            .org-head-wrap {
                display: flex;
                justify-content: center;
                margin-bottom: 0;
            }

            /* garis dari Kepala → garis horizontal (dibuat terpisah) */
            .v-line-top {
                width: 0;
                height: 40px;
                border-left: 2px solid #999;
                margin: 0 auto;
            }

            /* LEVEL 2: 4 card */
            .org-row {
                position: relative;
                width: 1080px;               /* 4 card * 240 + 3 gap * 40 */
                margin: 0 auto 40px auto;
                display: flex;
                gap: 40px;
                justify-content: center;
            }

            /* GARIS HORIZONTAL UTAMA (dari tengah card1 ke tengah card4) */
            .org-line {
                position: absolute;
                top: 0;
                left: 120px;                  /* center card1 */
                right: 120px;                 /* center card4 */
                border-top: 2px solid #999;
            }

            /* CARD LEVEL 2 */
            .org-row .org-card {
                position: relative;
                margin-top: 40px;             /* jarak dari garis ke card */
            }

            /* GARIS VERTIKAL DARI GARIS HORIZONTAL KE CARD (nempel pas di tengah) */
            .org-row .org-card::before {
                content: "";
                position: absolute;
                top: -40px;                   /* sama dengan margin-top card */
                left: 50%;
                transform: translateX(-50%);
                border-left: 2px solid #999;
                height: 40px;
            }

            /* ===== KONEKTOR TAHAP 3 (Unit) DARI GARIS PANJANG ===== */
            .center-connector {
                width: 1080px;
                margin: 0 auto;
                position: relative;
                height: 40px;
            }

            /* garis vertikal tepat di tengah, melanjutkan dari garis horizontal */
            .center-connector::before {
                content: "";
                position: absolute;
                top: 0;
                left: 50%;
                transform: translateX(-50%);
                border-left: 2px solid #999;
                height: 40px;
            }

            /* LEVEL 3 & 4 (Unit & Komite) */
            .connector {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .connector .v-line {
                width: 0;
                height: 40px;
                border-left: 2px solid #999;
                margin-bottom: 10px;
            }
        </style>

        <div class="org-wrapper">

            {{-- LEVEL 1: Kepala Sekolah --}}
            <div class="org-head-wrap">
                <div class="org-head">
                    <strong>Kepala Sekolah</strong><br>
                    <span class="small">SMK Negeri 1 Belimbing</span>
                </div>
            </div>

            {{-- Garis vertikal dari Kepala ke garis horizontal (sekarang tidak nembus card) --}}
            <div class="v-line-top"></div>

            {{-- LEVEL 2: Garis horizontal & 4 card --}}
            <div class="org-row">
                <div class="org-line"></div>

                <div class="org-card">
                    <strong>Wakil Kepala Sekolah</strong>
                    <div class="small text-muted">Bidang Kurikulum</div>
                    <div class="small text-muted">Bidang Kesiswaan</div>
                </div>

                <div class="org-card">
                    <strong>Kepala Tata Usaha</strong>
                    <div class="small text-muted">Staf TU dan Bendahara</div>
                </div>

                <div class="org-card">
                    <strong>Ketua Program Keahlian</strong>
                    <div class="small text-muted">Guru Produktif</div>
                    <div class="small text-muted">Guru Normatif</div>
                </div>

                <div class="org-card">
                    <strong>Bimbingan Konseling (BK)</strong>
                    <div class="small text-muted">Pembina Kesiswaan</div>
                </div>
            </div>

            {{-- GARIS DARI GARIS PANJANG → UNIT (TAHAP 3) --}}
            <div class="center-connector"></div>

            {{-- LEVEL 3 – Unit Pendukung Sekolah --}}
            <div class="connector mb-5">
                {{-- di sini tidak pakai v-line lagi, karena sudah ada center-connector --}}
                <div class="org-card">
                    <strong>Unit Pendukung Sekolah</strong>
                    <div class="small text-muted">Perpustakaan</div>
                    <div class="small text-muted">Laboratorium</div>
                    <div class="small text-muted">OSIS / Ekstrakurikuler</div>
                </div>
            </div>

            {{-- LEVEL 4 – Komite Sekolah (garis hanya dari Unit) --}}
            <div class="connector">
                <div class="v-line"></div>
                <div class="org-card">
                    <strong>Komite Sekolah</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
