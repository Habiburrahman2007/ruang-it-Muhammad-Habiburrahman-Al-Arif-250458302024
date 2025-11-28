<div>
    <div class="page-heading d-flex gap-3">
        <i class="fa-solid fa-chart-pie fa-2x"></i>
        <h3>Statistik</h3>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="row justify-between">
                    <div class="col-12 col-lg-2">
                        <a href="{{ route('user-control') }}">
                            <div class="card h-75">
                                <div
                                    class="card-body d-flex flex-column justify-content-center align-items-center py-3 px-2">
                                    <span class="badge bg-primary rounded-circle p-3 mb-2">
                                        <i class="fa-solid fa-user fa-lg text-white"></i>
                                    </span>
                                    <h5 class="text-muted font-semibold">Total Pengguna</h5>
                                    <h5 class="font-extrabold">{{ $totalUsers }}</h5>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-lg-2">
                        <a href="{{ route('blog-control') }}">
                            <div class="card h-75">
                                <div
                                    class="card-body d-flex flex-column justify-content-center align-items-center text-center py-3 px-2">
                                    <span class="badge bg-success rounded-circle p-3 mb-2">
                                        <i class="fa-solid fa-tags fa-lg text-white"></i>
                                    </span>
                                    <h5 class="text-muted font-semibold">Total Kategori</h5>
                                    <h5 class="font-extrabold">{{ $totalCategories }}</h5>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-lg-2">
                        <a href="{{ route('blog-control') }}">
                            <div class="card h-75">
                                <div
                                    class="card-body d-flex flex-column justify-content-center align-items-center text-center py-3 px-2">
                                    <span class="badge bg-warning rounded-circle p-3 mb-2">
                                        <i class="fa-solid fa-file-alt fa-lg text-white"></i>
                                    </span>
                                    <h5 class="text-muted font-semibold">Total Artikel</h5>
                                    <h5 class="font-extrabold">{{ $totalArticles }}</h5>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-lg-2">
                        <div class="card h-75">
                            <div
                                class="card-body d-flex flex-column justify-content-center align-items-center text-center py-3 px-2">
                                <span class="badge bg-danger rounded-circle p-3 mb-2">
                                    <i class="fa-solid fa-heart fa-lg text-white"></i>
                                </span>
                                <h5 class="text-muted font-semibold">Total Suka</h5>
                                <h5 class="font-extrabold">{{ $totalLikes }}</h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-2">
                        <div class="card h-75">
                            <div
                                class="card-body d-flex flex-column justify-content-center align-items-center text-center py-3 px-2">
                                <span class="badge bg-info rounded-circle p-3 mb-2">
                                    <i class="fa-solid fa-comment fa-lg text-white"></i>
                                </span>
                                <h5 class="text-muted font-semibold">Total Komentar</h5>
                                <h5 class="font-extrabold">{{ $totalComments }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>Artikel Terpopuler</h4>
                        <p class="text-muted mb-0">5 artikel dengan jumlah like terbanyak</p>
                    </div>

                    <div class="card-body">
                        <div class="overflow-x-auto">
                            <table class="table-responsive">
                                <table class="table table-striped" id="table1">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Judul</th>
                                            <th>Penulis</th>
                                            <th>Kategori</th>
                                            <th>Jumlah Like</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($popularArticles as $index => $article)
                                            <tr onclick="window.location='{{ route('detail-article', ['slug' => $article->slug, 'from' => 'statistic']) }}'"
                                                style="cursor: pointer;">
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ Str::limit($article->title, 20, '...') }}</td>
                                                <td>{{ Str::limit($article->user->name, 10, '...') }}</td>
                                                <td>
                                                    @if ($article->category)
                                                        <span
                                                            class="badge {{ $article->category->color ?? 'bg-secondary' }}">
                                                            {{ $article->category->name }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">-</span>
                                                    @endif
                                                </td>
                                                <td>{{ $article->likes_count }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-3">
                                                    Tidak ada artikel aktif.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header d-col justify-content-between align-items-center">
                        <h4>Kategori Terpopuler</h4>
                        <small>Distribusi artikel per kategori dan presentasenya</small>
                    </div>
                    <div class="card-body">
                        <div id="categoryPieChart" style="height: 400px;" wire:ignore></div>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <!-- ApexCharts Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.45.1/apexcharts.min.js"
        integrity="sha512-vD54YAf6El2OBej64SujWFLztu5selS4iZt4c2OJfCDYLD4km52r4dPSzhcdJ8XaOk3l4eauvRzRCVjJSndY8A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        // 1. Variabel Global untuk menyimpan instance chart
        // Disimpan di luar fungsi agar bisa di-destroy saat navigasi

        let pieChart = null;



        function createPieChart() {
            // A. Ambil Elemen Pie Chart
            const chartElement = document.querySelector("#categoryPieChart");

            // B. SAFETY CHECK: Jika elemen tidak ditemukan, BERHENTI.
            if (!chartElement) {
                return;
            }

            // C. Bersihkan chart lama
            if (pieChart) {
                pieChart.destroy();
            }

            const pieOptions = {
                chart: {
                    type: 'pie',
                    height: 400
                },
                series: @json($categoryArticleCounts ?? []),
                labels: @json($categoryNames ?? []),
                legend: {
                    position: 'bottom',
                    formatter: function(seriesName, opts) {
                        return seriesName + ": " + opts.w.globals.series[opts.seriesIndex]
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: val => val.toFixed(1) + "%"
                },
                tooltip: {
                    y: {
                        formatter: val => val + ' artikel'
                    }
                }
            };

            // D. Render Chart Baru
            pieChart = new ApexCharts(chartElement, pieOptions);
            pieChart.render();
        }

        // Fungsi Wrapper untuk inisialisasi semua chart
        function initCharts() {

            createPieChart();
        }

        // --- EVENT LISTENERS ---

        // 1. Jalankan saat Navigasi Livewire selesai (Pindah Halaman)
        document.addEventListener('livewire:navigated', () => {
            initCharts();
        });

        // 2. Jalankan saat Load Pertama kali (Hard Refresh)
        // Menggunakan DOMContentLoaded lebih aman & cepat daripada 'load'
        document.addEventListener('DOMContentLoaded', () => {
            initCharts();
        });
    </script>

</div>
