<?php

namespace App\Livewire\Admin;

use App\Models\AktivitasLog;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Title('Manajemen Pengguna - COTTON.id')]
#[Layout('layouts.admin')]
class ManajemenPengguna extends Component
{
    use WithPagination;

    public const ROLES = [
        'admin'             => 'Administrator Sistem',
        'pemotongan'        => 'Operator Pemotongan Kain',
        'pemotongan_kain'   => 'Operator Pemotongan Kain',
        'sablon'            => 'Operator Sablon & Cetak',
        'penjahitan'        => 'Operator Penjahitan (Sewing)',
        'steam'             => 'Operator Steam & Finishing',
        'operator_produksi' => 'Operator Produksi Umum',
    ];

    public string $search = '';
    public string $filterRole = 'semua';
    public int $perPage = 10;

    public string $pesanSukses = '';
    public string $pesanError = '';

    // Modal Create & Edit
    public bool $showModalForm = false;
    public bool $isEditMode = false;
    public ?int $userId = null;

    // Form inputs
    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $role = 'operator_produksi';
    public string $telepon = '';
    public string $password = '';
    public string $password_confirmation = '';

    // Modal Delete
    public bool $showModalDelete = false;
    public ?int $userToDeleteId = null;
    public string $userToDeleteName = '';

    protected $queryString = [
        'search'     => ['except' => ''],
        'filterRole' => ['except' => 'semua'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterRole(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'filterRole']);
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->resetForm();
        $this->isEditMode = false;
        $this->userId = null;
        $this->role = 'operator_produksi';
        $this->showModalForm = true;
    }

    public function openEditModal(int $id): void
    {
        $this->resetValidation();
        $user = User::findOrFail($id);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->username = $user->username ?? '';
        $this->email = $user->email;
        $this->role = $user->role;
        $this->telepon = $user->telepon ?? '';
        $this->password = '';
        $this->password_confirmation = '';

        $this->isEditMode = true;
        $this->showModalForm = true;
    }

    public function closeModalForm(): void
    {
        $this->showModalForm = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function simpan(): void
    {
        $this->resetMessages();

        $rules = [
            'name'     => ['required', 'string', 'max:255'],
            'username' => [
                'nullable',
                'string',
                'max:50',
                'alpha_dash',
                $this->isEditMode
                    ? Rule::unique('users', 'username')->ignore($this->userId)
                    : Rule::unique('users', 'username'),
            ],
            'email'    => [
                'required',
                'email',
                'max:255',
                $this->isEditMode
                    ? Rule::unique('users', 'email')->ignore($this->userId)
                    : Rule::unique('users', 'email'),
            ],
            'role'     => ['required', Rule::in(array_keys(self::ROLES))],
            'telepon'  => ['nullable', 'string', 'max:20'],
        ];

        if ($this->isEditMode) {
            $rules['password'] = ['nullable', 'string', 'min:6', 'confirmed'];
        } else {
            $rules['password'] = ['required', 'string', 'min:6', 'confirmed'];
        }

        $validated = $this->validate($rules, [
            'name.required'      => 'Nama lengkap pengguna wajib diisi.',
            'username.unique'    => 'Username ini sudah digunakan oleh akun lain.',
            'username.alpha_dash'=> 'Username hanya boleh berisi huruf, angka, strip, dan garis bawah.',
            'email.required'     => 'Alamat email wajib diisi.',
            'email.email'        => 'Format alamat email tidak valid.',
            'email.unique'       => 'Alamat email sudah terdaftar di sistem.',
            'role.required'      => 'Pilih peran/hak akses pengguna.',
            'password.required'  => 'Kata sandi wajib diisi.',
            'password.min'       => 'Kata sandi minimal terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $data = [
            'name'     => trim($validated['name']),
            'username' => !empty($validated['username']) ? trim($validated['username']) : null,
            'email'    => strtolower(trim($validated['email'])),
            'role'     => $validated['role'],
            'telepon'  => !empty($validated['telepon']) ? trim($validated['telepon']) : null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        if ($this->isEditMode && $this->userId) {
            $user = User::findOrFail($this->userId);
            $user->update($data);

            AktivitasLog::catat(
                aksi: "Memperbarui data pengguna: {$user->name} ({$user->role})",
                referensi: "USR-{$user->id}",
                tipe: 'auth',
                warna: 'amber',
                keterangan: "Perubahan akun staf/operator. Email: {$user->email}, Role: {$user->role}."
            );

            $this->pesanSukses = "Data pengguna '{$user->name}' berhasil diperbarui.";
        } else {
            $user = User::create($data);

            AktivitasLog::catat(
                aksi: "Menambahkan pengguna baru: {$user->name} ({$user->role})",
                referensi: "USR-{$user->id}",
                tipe: 'auth',
                warna: 'emerald',
                keterangan: "Akun baru dibuat dengan email {$user->email} dan role {$user->role}."
            );

            $this->pesanSukses = "Pengguna baru '{$user->name}' berhasil didaftarkan.";
        }

        $this->closeModalForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->resetMessages();

        if (auth()->id() === $id) {
            $this->pesanError = 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif login.';
            return;
        }

        $user = User::findOrFail($id);
        $this->userToDeleteId = $user->id;
        $this->userToDeleteName = $user->name;
        $this->showModalDelete = true;
    }

    public function closeModalDelete(): void
    {
        $this->showModalDelete = false;
        $this->userToDeleteId = null;
        $this->userToDeleteName = '';
    }

    public function hapusUser(): void
    {
        $this->resetMessages();

        if (!$this->userToDeleteId) {
            return;
        }

        if (auth()->id() === $this->userToDeleteId) {
            $this->pesanError = 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif login.';
            $this->closeModalDelete();
            return;
        }

        $user = User::findOrFail($this->userToDeleteId);
        $nama = $user->name;
        $role = $user->role;
        $id = $user->id;

        $user->delete();

        AktivitasLog::catat(
            aksi: "Menghapus akun pengguna: {$nama} ({$role})",
            referensi: "USR-{$id}",
            tipe: 'auth',
            warna: 'rose',
            keterangan: "Penghapusan akun staf oleh administrator."
        );

        $this->pesanSukses = "Pengguna '{$nama}' telah berhasil dihapus dari sistem.";
        $this->closeModalDelete();
    }

    public function exportCsv(): StreamedResponse
    {
        $users = $this->queryUsers()->get();
        $namaFile = 'data-pengguna-cotton-id-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($users): void {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'ID',
                'Nama Lengkap',
                'Username',
                'Email',
                'Role / Hak Akses',
                'Nomor Telepon/WA',
                'Tanggal Terdaftar',
            ]);

            foreach ($users as $user) {
                fputcsv($handle, [
                    $user->id,
                    $user->name,
                    $user->username ?? '-',
                    $user->email,
                    self::ROLES[$user->role] ?? $user->role,
                    $user->telepon ?? '-',
                    $user->created_at ? $user->created_at->translatedFormat('d F Y H:i') : '-',
                ]);
            }

            fclose($handle);
        }, $namaFile, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function resetForm(): void
    {
        $this->userId = null;
        $this->name = '';
        $this->username = '';
        $this->email = '';
        $this->role = 'operator_produksi';
        $this->telepon = '';
        $this->password = '';
        $this->password_confirmation = '';
    }

    private function resetMessages(): void
    {
        $this->pesanSukses = '';
        $this->pesanError = '';
    }

    private function queryUsers(): Builder
    {
        return User::query()
            ->when($this->search !== '', function (Builder $query): void {
                $search = trim($this->search);
                $query->where(function (Builder $sub) use ($search): void {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('telepon', 'like', "%{$search}%");
                });
            })
            ->when($this->filterRole !== 'semua', function (Builder $query): void {
                $query->where('role', $this->filterRole);
            })
            ->latest('id');
    }

    public function render(): View
    {
        $totalPengguna = User::count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalWorkshop = User::whereIn('role', ['pemotongan_kain', 'sablon', 'penjahitan', 'steam', 'operator_produksi'])->count();
        $totalTerbaru = User::whereDate('created_at', '>=', now()->subDays(30))->count();

        $users = $this->queryUsers()->paginate($this->perPage);

        return view('livewire.admin.manajemen-pengguna', [
            'users'         => $users,
            'totalPengguna' => $totalPengguna,
            'totalAdmin'    => $totalAdmin,
            'totalWorkshop' => $totalWorkshop,
            'totalTerbaru'  => $totalTerbaru,
            'roles'         => self::ROLES,
        ]);
    }
}

