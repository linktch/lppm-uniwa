<div>
    <div class="content-wrapper">
        <!-- HEADER -->
        <section class="content-header">
            <div class="container-fluid">
                @if ($jenis == 'PAM')
                    <h1 class="academic-title">Detail Mitra</h1>
                @else
                    <h1 class="academic-title">Detail Kelompok</h1>
                @endif
            </div>
        </section>

        <!-- CONTENT -->
        <section class="content">
            <div class="container-fluid">
                <div class="academic-card">

                    <!-- HEADER CARD - lebih akademik -->
                    <div class="academic-card-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h3 class="card-title-academic">
                                <i class="fas fa-users-class me-2"></i>
                                {{ $kelompok->nama_kelompok }}
                            </h3>
                            <span class="badge-academic">
                                <i class="fas fa-calendar-alt me-1"></i> Aktif
                            </span>
                        </div>
                        <p class="text-muted-small mt-2 mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            Kelompok {{ $jenis == 'PAM' ? 'Mitra' : 'KKN/Program' }}
                        </p>
                    </div>

                    <!-- BODY -->
                    <div class="academic-card-body">

                        <!-- INFO UTAMA - lebih rapi -->
                        <div class="info-panel">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="info-item-academic">
                                        <div class="info-label">
                                            <i class="fas fa-calendar-week"></i> Periode
                                        </div>
                                        <div class="info-value">
                                            {{ $kelompok->periode->nama_periode ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-item-academic">
                                        <div class="info-label">
                                            <i class="fas fa-chalkboard-user"></i> Kegiatan
                                        </div>
                                        <div class="info-value">
                                            {{ $kelompok->kegiatan->nama_kegiatan ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-item-academic">
                                        <div class="info-label">
                                            <i class="fas fa-graduation-cap"></i> Program Studi
                                        </div>
                                        <div class="info-value">
                                            {{ $kelompok->prodi->first()->name ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ================= MAHASISWA ================= -->
                        <div class="member-section">
                            <div class="section-header-academic">
                                <div class="section-title-wrapper">
                                    <i class="fas fa-user-graduate section-icon text-success"></i>
                                    <h5 class="section-title">Mahasiswa</h5>
                                    <span class="count-badge">{{ $mahasiswa->total() }}</span>
                                </div>
                                <div class="button-group">
                                    <a href="" class="btn-add-academic mr-1">
                                        <i class="fas fa-chart-line me-1"></i> Indikator Capaian Pembelajaran
                                    </a>
                                    <button wire:click="openModal('mahasiswa')" class="btn-add-academic">
                                        <i class="fas fa-plus-circle me-1"></i> Tambah Mahasiswa
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive-modern">
                                <table class="academic-table">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="70%">Nama / NIM / Prodi</th>
                                            <th width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($mahasiswa as $u)
                                            @php $data = json_decode($u->data_mahasiswa, true); @endphp
                                            <tr>
                                                <td class="text-center">
                                                    {{ $loop->iteration + ($mahasiswa->currentPage() - 1) * $mahasiswa->perPage() }}
                                                </td>
                                                <td>
                                                    <div class="member-info">
                                                        <strong
                                                            class="member-name">{{ $data['nama_mahasiswa'] ?? $u->name }}</strong>
                                                        <div class="member-detail">
                                                            <span class="nim">{{ $data['nim'] ?? '-' }}</span>
                                                            <span class="separator">•</span>
                                                            <span
                                                                class="prodi">{{ $data['nama_program_studi'] ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn-delete-sm"
                                                        wire:click="$dispatch('confirmDelete', { type: 'mahasiswa', id: '{{ $u->id }}' })">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="empty-row">
                                                <td colspan="3">
                                                    <div class="empty-state">
                                                        <i class="fas fa-users-slash"></i>
                                                        <p>Belum ada mahasiswa</p>
                                                        <small>Klik tombol Tambah untuk menambahkan</small>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if ($mahasiswa->hasPages())
                                <div class="pagination-modern mt-3">
                                    {{ $mahasiswa->links() }}
                                </div>
                            @endif
                        </div>

                        <!-- ================= TIM ================= -->
                        <div class="member-section">
                            <div class="section-header-academic">
                                <div class="section-title-wrapper">
                                    <i class="fas fa-users section-icon text-primary"></i>
                                    <h5 class="section-title">Tim DPL / Dosen</h5>
                                    <span class="count-badge">{{ $teams->total() }}</span>
                                </div>
                                <button wire:click="openModal('tim')" class="btn-add-academic">
                                    <i class="fas fa-plus-circle me-1"></i> Tambah
                                </button>
                            </div>

                            <div class="table-responsive-modern">
                                <table class="academic-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama / Username / Role</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($teams as $team)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $loop->iteration + ($teams->currentPage() - 1) * $teams->perPage() }}
                                                </td>
                                                <td>
                                                    <div class="member-info">
                                                        <strong class="member-name">{{ $team->first_name }}
                                                            {{ $team->last_name ?? '' }}</strong>
                                                        <div class="member-detail">
                                                            <span class="username">{{ $team->username ?? '-' }}</span>
                                                            <span class="separator">•</span>
                                                            <span
                                                                class="role-badge">{{ $team->pivot->role ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn-delete-sm"
                                                        wire:click="$dispatch('confirmDelete', { type: 'tim', id: '{{ $team->id }}' })">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="empty-row">
                                                <td colspan="3">
                                                    <div class="empty-state"><i class="fas fa-user-friends"></i>
                                                        <p>Tidak ada data tim</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if ($teams->hasPages())
                                <div class="pagination-modern mt-3">{{ $teams->links() }}</div>
                            @endif
                        </div>

                        <!-- ================= KEMAHASISWAAN ================= -->
                        <div class="member-section">
                            <div class="section-header-academic">
                                <div class="section-title-wrapper">
                                    <i class="fas fa-university section-icon text-warning"></i>
                                    <h5 class="section-title">Kemahasiswaan</h5>
                                    <span class="count-badge">{{ $kemahasiswaan->total() }}</span>
                                </div>
                                <button wire:click="openModal('kemahasiswaan')" class="btn-add-academic">
                                    <i class="fas fa-plus-circle me-1"></i> Tambah
                                </button>
                            </div>

                            <div class="table-responsive-modern">
                                <table class="academic-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama / Username / Role</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($kemahasiswaan as $warek)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $loop->iteration + ($kemahasiswaan->currentPage() - 1) * $kemahasiswaan->perPage() }}
                                                </td>
                                                <td>
                                                    <div class="member-info">
                                                        <strong>{{ $warek->first_name }}
                                                            {{ $warek->last_name ?? '' }}</strong>
                                                        <div class="member-detail">{{ $warek->username ?? '-' }} •
                                                            {{ $warek->pivot->role ?? '-' }}</div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn-delete-sm"
                                                        wire:click="$dispatch('confirmDelete', { type: 'kemahasiswaan', id: '{{ $warek->id }}' })">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="empty-row">
                                                <td colspan="3">
                                                    <div class="empty-state"><i class="fas fa-building"></i>
                                                        <p>Tidak ada data kemahasiswaan</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if ($kemahasiswaan->hasPages())
                                <div class="pagination-modern mt-3">{{ $kemahasiswaan->links() }}</div>
                            @endif
                        </div>

                        <!-- ================= PRODI ================= -->
                        <div class="member-section">
                            <div class="section-header-academic">
                                <div class="section-title-wrapper">
                                    <i class="fas fa-building section-icon text-info"></i>
                                    <h5 class="section-title">Koordinator Prodi</h5>
                                    <span class="count-badge">{{ $prodi->total() }}</span>
                                </div>
                                <button wire:click="openModal('prodi')" class="btn-add-academic">
                                    <i class="fas fa-plus-circle me-1"></i> Tambah
                                </button>
                            </div>

                            <div class="table-responsive-modern">
                                <table class="academic-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama / Username / Prodi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($prodi as $prd)
                                            @php
                                                $prodiData = \App\Models\ProdiFakultas::where(
                                                    'id_prodi',
                                                    $prd->id_prodi,
                                                )->first();
                                                $prodiName = $prodiData->nama_program_studi ?? '-';
                                            @endphp
                                            <tr>
                                                <td class="text-center">
                                                    {{ $loop->iteration + ($prodi->currentPage() - 1) * $prodi->perPage() }}
                                                </td>
                                                <td>
                                                    <div class="member-info">
                                                        <strong>{{ $prd->first_name }}
                                                            {{ $prd->last_name ?? '' }}</strong>
                                                        <div class="member-detail">{{ $prd->username ?? '-' }} •
                                                            {{ $prodiName }}</div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn-delete-sm"
                                                        wire:click="$dispatch('confirmDelete', { type: 'prodi', id: '{{ $prd->id }}' })">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="empty-row">
                                                <td colspan="3">
                                                    <div class="empty-state"><i class="fas fa-chalkboard"></i>
                                                        <p>Tidak ada data prodi</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if ($prodi->hasPages())
                                <div class="pagination-modern mt-3">{{ $prodi->links() }}</div>
                            @endif
                        </div>

                        <!-- ================= MITRA ================= -->
                        <div class="member-section">
                            <div class="section-header-academic">
                                <div class="section-title-wrapper">
                                    <i class="fas fa-handshake section-icon text-secondary"></i>
                                    <h5 class="section-title">Mitra / Akun Mitra</h5>
                                    <span class="count-badge">{{ $mitra->total() }}</span>
                                </div>
                                <button wire:click="openModal('mitra')" class="btn-add-academic">
                                    <i class="fas fa-plus-circle me-1"></i> Tambah
                                </button>
                            </div>

                            <div class="table-responsive-modern">
                                <table class="academic-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama / Username / Role</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($mitra as $mitr)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $loop->iteration + ($mitra->currentPage() - 1) * $mitra->perPage() }}
                                                </td>
                                                <td>
                                                    <div class="member-info">
                                                        <strong>{{ $mitr->first_name }}
                                                            {{ $mitr->last_name ?? '' }}</strong>
                                                        <div class="member-detail">{{ $mitr->username ?? '-' }} •
                                                            {{ $mitr->pivot->role ?? '-' }}</div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn-delete-sm"
                                                        wire:click="$dispatch('confirmDelete', { type: 'mitra', id: '{{ $mitr->id }}' })">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="empty-row">
                                                <td colspan="3">
                                                    <div class="empty-state"><i class="fas fa-building"></i>
                                                        <p>Tidak ada data mitra</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if ($mitra->hasPages())
                                <div class="pagination-modern mt-3">{{ $mitra->links() }}</div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
    @include('livewire.kegiatan.pam.kelompok.modal.globalModal')
    @include('livewire.kegiatan.pam.kelompok.modal.indikatorPencapaian')
</div>
