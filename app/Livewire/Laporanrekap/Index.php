<?php

namespace App\Livewire\Laporanrekap;

use App\Models\Kelompok;
use App\Models\KelompokUser;
use App\Models\LaporanHarian;
use App\Models\TimelineKegiatan;
use App\Models\User;
use App\Services\KKNService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $periode_id;
    public $kegiatan_id;
    public $jenisKegiatan;
    public $role;
    public $search = '';
    public $kelompok_id;
    public $tanggal_mulai;
    public $tanggal_selesai;
    public $timeline;
    public $mingguKe = 1;
    public $tahun;
    public $bulan;

    public function mount($role, $jenisKegiatan)
    {
        $this->role = $role;
        $this->jenisKegiatan = $jenisKegiatan;
        $this->periode_id = KKNService::periodeId();
        $this->kegiatan_id = KKNService::kegiatanId($jenisKegiatan);

        // Ambil timeline dengan jenis 'pelaksanaan'
        $this->timeline = TimelineKegiatan::where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->where('jenis', 'pelaksanaan')
            ->first();

        // Set tanggal mulai dan selesai dari timeline
        if ($this->timeline) {
            $startDate = Carbon::parse($this->timeline->tanggal_mulai);
            $this->tahun = $startDate->year;
            $this->bulan = $startDate->month;
            $this->setTanggalByMinggu(1);
        }

        // Ambil kelompok dosen
        $kelompokUser = KelompokUser::where('user_id', Auth::id())
            ->where('role', 'dospem')
            ->first();

        $this->kelompok_id = $kelompokUser->kelompok_id ?? null;
    }

    public function setTanggalByMinggu($minggu)
    {
        $this->mingguKe = $minggu;

        if (!$this->timeline) {
            return;
        }

        $startDate = Carbon::parse($this->timeline->tanggal_mulai);

        $startDay = (($minggu - 1) * 7) + 1;
        $endDay = $minggu * 7;

        $lastDayOfMonth = $startDate->copy()->endOfMonth()->day;
        $actualEndDay = min($endDay, $lastDayOfMonth);

        $this->tanggal_mulai = Carbon::create($startDate->year, $startDate->month, $startDay)->format('Y-m-d');
        $this->tanggal_selesai = Carbon::create($startDate->year, $startDate->month, $actualEndDay)->format('Y-m-d');
    }

    public function updatedMingguKe()
    {
        $this->setTanggalByMinggu($this->mingguKe);
    }

    public function getTanggalRange()
    {
        $dates = [];
        $start = Carbon::parse($this->tanggal_mulai);
        $end = Carbon::parse($this->tanggal_selesai);

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }

    public function getMahasiswasProperty()
    {
        if (!$this->kelompok_id) {
            return collect([]);
        }

        $query = KelompokUser::where('kelompok_id', $this->kelompok_id)
            ->where('role', 'mahasiswa')
            ->with('user');

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        $anggota = $query->get();

        if ($anggota->isEmpty()) {
            return collect([]);
        }

        $data = [];
        $tanggalRange = $this->getTanggalRange();

        foreach ($anggota as $item) {
            $user = $item->user;
            $dm = is_string($user->data_mahasiswa)
                ? json_decode($user->data_mahasiswa, true)
                : ($user->data_mahasiswa ?? []);

            $row = [
                'id' => $user->id,
                'nama' => $dm['nama_mahasiswa'] ?? $user->name,
                'nim' => $dm['nim'] ?? '-',
                'prodi' => $dm['nama_program_studi'] ?? '-',
                'has_submitted' => false,
                'has_revisi' => false,
                'has_approved' => false,
            ];

            foreach ($tanggalRange as $tanggal) {
                $laporan = LaporanHarian::where('user_id', $user->id)
                    ->where('periode_id', $this->periode_id)
                    ->where('kegiatan_id', $this->kegiatan_id)
                    ->whereDate('tanggal', $tanggal)
                    ->orderByRaw("FIELD(status, 'submitted', 'revisi', 'approved')")
                    ->first();

                $status = $laporan ? $laporan->status : '-';
                $row[$tanggal] = [
                    'status' => $status,
                    'laporan_id' => $laporan ? $laporan->id : null,
                ];

                if ($status == 'submitted')
                    $row['has_submitted'] = true;
                if ($status == 'revisi')
                    $row['has_revisi'] = true;
                if ($status == 'approved')
                    $row['has_approved'] = true;
            }

            $data[] = $row;
        }

        // Sorting: Ada submitted (3) > Ada revisi (2) > Ada approved (1) > Tidak ada (0)
        usort($data, function ($a, $b) {
            $getPriority = function ($item) {
                if ($item['has_submitted'])
                    return 3;
                if ($item['has_revisi'])
                    return 2;
                if ($item['has_approved'])
                    return 1;
                return 0;
            };

            $priorityA = $getPriority($a);
            $priorityB = $getPriority($b);

            if ($priorityA != $priorityB) {
                return $priorityB <=> $priorityA;
            }

            return strcmp($a['nama'], $b['nama']);
        });

        return collect($data);
    }

    public function back()
    {
        return redirect()->route('kegiatan.index', [
            'role' => $this->role,
            'jenisKegiatan' => $this->jenisKegiatan
        ]);
    }

    public function render()
    {
        return view('livewire.laporanrekap.index', [
            'mahasiswas' => $this->mahasiswas,
            'tanggalRange' => $this->getTanggalRange(),
            'timeline' => $this->timeline,
        ]);
    }
}