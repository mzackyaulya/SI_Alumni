<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ambil jumlah lowongan per nilai 'lokasi'
        $rows = Lowongan::select('lokasi', \DB::raw('COUNT(*) as total'))
            ->whereNotNull('lokasi')
            ->groupBy('lokasi')
            ->get();

        $mapKotaKeProv = $this->cityToProvinceMap();

        $provCounts = [];   // agregat provinsi
        $cityCounts = [];   // agregat kota

        foreach ($rows as $r) {
            $lokasi = trim((string) $r->lokasi);

            // --- hitung provinsi ---
            $prov = $this->resolveProvince($lokasi, $mapKotaKeProv)
                  ?? $this->normalizeProvinceName($lokasi);
            if ($prov) {
                $provCounts[$prov] = ($provCounts[$prov] ?? 0) + (int) $r->total;
            }

            // --- hitung kota ---
            $city = $this->normalizeCityName($lokasi);
            if ($city) {
                $cityCounts[$city] = ($cityCounts[$city] ?? 0) + (int) $r->total;
            }
        }

        $maxVal = empty($provCounts) ? 0 : max($provCounts);

        // Siapkan titik kota (count + koordinat)
        $coords = $this->cityCoords(); // 'PALEMBANG' => [-2.97, 104.77], ...
        $cityPoints = [];
        foreach ($cityCounts as $city => $count) {
            if (isset($coords[$city])) {
                [$lat, $lng] = $coords[$city];
                $cityPoints[] = [
                    'name'  => $city,
                    'count' => $count,
                    'lat'   => $lat,
                    'lng'   => $lng,
                ];
            }
        }

        // Top provinsi (opsional buat tabel ringkas)
        arsort($provCounts);
        $topProv = array_slice($provCounts, 0, 10, true);

        return view('dashboard', [
            'provCounts' => $provCounts,
            'maxVal'     => $maxVal,
            'cityPoints' => $cityPoints,
            'topProv'    => $topProv,
        ]);
    }

    /* ======================
     *  Normalisasi Nama Kota
     * ====================== */
    private function normalizeCityName(string $lokasi): ?string
    {
        $u = mb_strtoupper($lokasi);

        // Buang awalan "KOTA", "KAB", "KABUPATEN"
        $u = preg_replace('~\b(KAB\.?|KABUPATEN|KOTA)\b~u', '', $u);
        $u = trim(preg_replace('~\s+~', ' ', $u));

        // Hilangkan tail seperti ", INDONESIA"
        $u = preg_replace('~,(.*)$~', '', $u);

        // Alias kota umum
        $aliases = [
            'JAKARTA SELATAN' => 'JAKARTA',
            'JAKARTA TIMUR'   => 'JAKARTA',
            'JAKARTA BARAT'   => 'JAKARTA',
            'JAKARTA UTARA'   => 'JAKARTA',
            'JAKARTA PUSAT'   => 'JAKARTA',
            'SURAKARTA'       => 'SOLO',
        ];
        if (isset($aliases[$u])) {
            $u = $aliases[$u];
        }

        return $u !== '' ? $u : null;
    }

    /* ==========================
     *  Normalisasi Nama Provinsi
     * ========================== */
    private function normalizeProvinceName(string $lokasi): ?string
    {
        $u = mb_strtoupper($lokasi);
        $u = preg_replace('~\s+~', ' ', $u);

        // Map variasi -> nama baku (upper)
        $pmap = [
            'DKI JAKARTA'                     => 'DKI JAKARTA',
            'JAKARTA'                         => 'DKI JAKARTA',
            'DAERAH KHUSUS IBUKOTA JAKARTA'   => 'DKI JAKARTA',

            'JAWA BARAT'  => 'JAWA BARAT',
            'JAWA TENGAH' => 'JAWA TENGAH',
            'JAWA TIMUR'  => 'JAWA TIMUR',

            'DI YOGYAKARTA'                  => 'DI YOGYAKARTA',
            'DAERAH ISTIMEWA YOGYAKARTA'     => 'DI YOGYAKARTA',
            'YOGYAKARTA'                     => 'DI YOGYAKARTA',

            'BANTEN' => 'BANTEN',

            'SUMATERA UTARA'    => 'SUMATERA UTARA',
            'SUMATERA BARAT'    => 'SUMATERA BARAT',
            'SUMATERA SELATAN'  => 'SUMATERA SELATAN',
            'RIAU'              => 'RIAU',
            'KEPULAUAN RIAU'    => 'KEPULAUAN RIAU',
            'JAMBI'             => 'JAMBI',
            'BENGKULU'          => 'BENGKULU',
            'LAMPUNG'           => 'LAMPUNG',
            'BANGKA BELITUNG'   => 'KEPULAUAN BANGKA BELITUNG',
            'KEPULAUAN BANGKA BELITUNG' => 'KEPULAUAN BANGKA BELITUNG',

            'BALI' => 'BALI',
            'NUSA TENGGARA BARAT' => 'NUSA TENGGARA BARAT',
            'NTB' => 'NUSA TENGGARA BARAT',
            'NUSA TENGGARA TIMUR' => 'NUSA TENGGARA TIMUR',
            'NTT' => 'NUSA TENGGARA TIMUR',

            'KALIMANTAN BARAT'  => 'KALIMANTAN BARAT',
            'KALIMANTAN TENGAH' => 'KALIMANTAN TENGAH',
            'KALIMANTAN SELATAN'=> 'KALIMANTAN SELATAN',
            'KALIMANTAN TIMUR'  => 'KALIMANTAN TIMUR',
            'KALIMANTAN UTARA'  => 'KALIMANTAN UTARA',

            'SULAWESI UTARA'    => 'SULAWESI UTARA',
            'SULAWESI TENGAH'   => 'SULAWESI TENGAH',
            'SULAWESI SELATAN'  => 'SULAWESI SELATAN',
            'SULAWESI TENGGARA' => 'SULAWESI TENGGARA',
            'GORONTALO'         => 'GORONTALO',
            'SULAWESI BARAT'    => 'SULAWESI BARAT',

            'MALUKU'            => 'MALUKU',
            'MALUKU UTARA'      => 'MALUKU UTARA',

            'PAPUA'                   => 'PAPUA',
            'PAPUA BARAT'             => 'PAPUA BARAT',
            'PAPUA BARAT DAYA'        => 'PAPUA BARAT DAYA',
            'PAPUA TENGAH'            => 'PAPUA TENGAH',
            'PAPUA PEGUNUNGAN'        => 'PAPUA PEGUNUNGAN',
            'PAPUA SELATAN'           => 'PAPUA SELATAN',
        ];

        // cocokan persis
        if (isset($pmap[$u])) return $pmap[$u];

        // fallback: cari frasa provinsi dalam lokasi (kasus "Palembang, Sumatera Selatan")
        foreach ($pmap as $key => $name) {
            if (str_contains($u, $key)) return $name;
        }

        return null;
    }

    /* =====================
     *  Peta Kota -> Provinsi
     * ===================== */
    private function cityToProvinceMap(): array
    {
        return [
            // SUMATERA SELATAN
            'PALEMBANG' => 'SUMATERA SELATAN',
            'LUBUKLINGGAU' => 'SUMATERA SELATAN',
            'PRABUMULIH'   => 'SUMATERA SELATAN',
            'PAGAR ALAM'   => 'SUMATERA SELATAN',
            'BANYUASIN'    => 'SUMATERA SELATAN',

            // DKI JAKARTA
            'JAKARTA' => 'DKI JAKARTA',

            // JAWA BARAT
            'BANDUNG' => 'JAWA BARAT',
            'BOGOR'   => 'JAWA BARAT',
            'DEPOK'   => 'JAWA BARAT',
            'BEKASI'  => 'JAWA BARAT',

            // BANTEN
            'TANGERANG' => 'BANTEN',

            // JAWA TENGAH
            'SEMARANG' => 'JAWA TENGAH',
            'SOLO'     => 'JAWA TENGAH',

            // DI YOGYAKARTA
            'YOGYAKARTA' => 'DI YOGYAKARTA',

            // JAWA TIMUR
            'SURABAYA' => 'JAWA TIMUR',
            'SIDOARJO' => 'JAWA TIMUR',
            'MALANG'   => 'JAWA TIMUR',

            // BALI
            'DENPASAR' => 'BALI',

            // KEP. RIAU
            'BATAM' => 'KEPULAUAN RIAU',

            // SUMATERA UTARA
            'MEDAN' => 'SUMATERA UTARA',

            // SULSEL
            'MAKASSAR' => 'SULAWESI SELATAN',

            // RIAU
            'PEKANBARU' => 'RIAU',

            // SUMBAR
            'PADANG' => 'SUMATERA BARAT',

            // BENGKULU
            'BENGKULU' => 'BENGKULU',
        ];
    }

    /* =============================
     *  Resolve provinsi dari lokasi
     * ============================= */
    private function resolveProvince(string $lokasi, array $cityProv): ?string
    {
        $city = $this->normalizeCityName($lokasi);
        if ($city && isset($cityProv[$city])) {
            return $cityProv[$city];
        }
        return null;
    }

    /* ===================
     *  Koordinat kota-kota
     * =================== */
    private function cityCoords(): array
    {
        return [
            'PALEMBANG' => [-2.9761, 104.7754],
            'JAKARTA'   => [-6.2000, 106.8167],
            'BANDUNG'   => [-6.9175, 107.6191],
            'BOGOR'     => [-6.5950, 106.8167],
            'DEPOK'     => [-6.4025, 106.7942],
            'BEKASI'    => [-6.2383, 106.9756],
            'TANGERANG' => [-6.1783, 106.6319],
            'SEMARANG'  => [-6.9667, 110.4167],
            'SOLO'      => [-7.5666, 110.8166],
            'YOGYAKARTA'=> [-7.7956, 110.3695],
            'SURABAYA'  => [-7.2575, 112.7521],
            'SIDOARJO'  => [-7.4469, 112.7174],
            'MALANG'    => [-7.9839, 112.6214],
            'DENPASAR'  => [-8.6500, 115.2167],
            'BATAM'     => [1.0456, 104.0305],
            'MEDAN'     => [3.5952, 98.6722],
            'MAKASSAR'  => [-5.1477, 119.4327],
            'PEKANBARU' => [0.5333, 101.4500],
            'PADANG'    => [-0.9471, 100.4172],
            'BENGKULU'  => [-3.8006, 102.2655],
        ];
    }
}
