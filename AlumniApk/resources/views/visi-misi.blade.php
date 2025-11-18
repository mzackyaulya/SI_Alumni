@extends('layout.main')

@section('title', 'Visi & Misi')

@section('content')
<div class="py-5" style="background-color: #f5f6fa;">
    <div class="container">

        <!-- Judul Utama -->
        <h1 class="text-center fw-bold mb-5" style="font-size: 62px;">
            Visi dan Misi
        </h1>

        <!-- Bagian Visi -->
        <div class="mb-5">
            <h3 class="text-center fw-semibold text-primary mb-3">Visi</h3>
            <p class="text-center fst-italic fs-5 text-secondary">
                “Terwujudnya SMK Negeri 1 Belimbing sebagai sekolah kejuruan yang unggul,
                berkarakter, berwawasan lingkungan, dan siap bersaing di dunia kerja serta dunia usaha.”
            </p>
        </div>

        <hr class="my-4">

        <!-- Bagian Misi -->
        <div class="mb-5">
            <h3 class="text-center fw-semibold text-primary mb-4">Misi</h3>

            <ol class="fs-5 text-secondary lh-lg" style="max-width: 900px; margin: auto;">
                <li>
                    Menyelenggarakan pendidikan kejuruan yang berorientasi pada kebutuhan dunia industri,
                    dunia usaha, dan dunia kerja.
                </li>
                <li>
                    Meningkatkan kualitas sumber daya manusia yang beriman, berakhlak mulia,
                    dan berkompeten di bidangnya.
                </li>
                <li>
                    Mengembangkan inovasi dan kreativitas peserta didik melalui kegiatan akademik maupun nonakademik.
                </li>
                <li>
                    Memperkuat kerja sama dengan dunia industri dan lembaga terkait untuk meningkatkan relevansi pendidikan.
                </li>
                <li>
                    Mewujudkan lingkungan sekolah yang bersih, sehat, dan ramah lingkungan.
                </li>
            </ol>
        </div>

    </div>
</div>
@endsection
