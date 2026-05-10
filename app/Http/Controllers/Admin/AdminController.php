<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\RemedialRecommendation;
use App\Models\Pretest;
use App\Models\SystemConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    // ─── DASHBOARD ────────────────────────────────────────────────
    public function dashboard()
    {
        $stats = [
            'total_students'   => User::where('role', 'student')->count(),
            'total_lecturers'  => User::where('role', 'lecturer')->count(),
            'total_materials'  => Material::count(),
            'total_quizzes'    => Quiz::count(),
            'remedial_active'  => RemedialRecommendation::where('is_completed', false)->count(),
            'pretest_done'     => User::where('role', 'student')->where('pretest_completed', true)->count(),
        ];

        $levelDistribution = [
            'Pemula'   => User::where('role', 'student')->where('level', 'Pemula')->count(),
            'Menengah' => User::where('role', 'student')->where('level', 'Menengah')->count(),
            'Lanjutan' => User::where('role', 'student')->where('level', 'Lanjutan')->count(),
        ];

        $recentUsers = User::where('role', 'student')
            ->latest()
            ->take(6)
            ->get();

        $recentResults = QuizResult::with(['user', 'quiz.material'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'levelDistribution', 'recentUsers', 'recentResults'
        ));
    }

    // ─── MANAJEMEN PENGGUNA ───────────────────────────────────────
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:student,lecturer,admin',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil dibuat.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'     => 'required|in:student,lecturer,admin',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'role'  => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function toggleUserStatus(User $user)
    {
        // Jangan nonaktifkan diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();
        return back()->with('success', 'Akun pengguna berhasil dihapus.');
    }

    // Import massal mahasiswa dari CSV
    public function importUsers(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file    = $request->file('csv_file');
        $handle  = fopen($file->getRealPath(), 'r');
        $header  = fgetcsv($handle); // skip baris pertama (header)
        $count   = 0;
        $errors  = [];
        $row_num = 2;

        while (($row = fgetcsv($handle)) !== false) {
            // Format CSV: name, email, password (opsional)
            if (count($row) < 2) {
                $errors[] = "Baris {$row_num}: format tidak valid.";
                $row_num++;
                continue;
            }

            [$name, $email] = $row;
            $password = $row[2] ?? 'password123';

            if (User::where('email', trim($email))->exists()) {
                $errors[] = "Baris {$row_num}: Email {$email} sudah terdaftar.";
                $row_num++;
                continue;
            }

            User::create([
                'name'      => trim($name),
                'email'     => trim($email),
                'password'  => Hash::make(trim($password)),
                'role'      => 'student',
                'is_active' => true,
            ]);

            $count++;
            $row_num++;
        }

        fclose($handle);

        $msg = "{$count} akun mahasiswa berhasil diimport.";
        if (!empty($errors)) {
            $msg .= ' ' . count($errors) . ' baris gagal.';
            return back()->with('warning', $msg)->with('import_errors', $errors);
        }

        return back()->with('success', $msg);
    }

    // ─── KONFIGURASI SISTEM ───────────────────────────────────────
    public function settings()
    {
        $configs = [
            'threshold_level1' => SystemConfig::getValue('threshold_level1', 40),
            'threshold_level3' => SystemConfig::getValue('threshold_level3', 70),
            'quiz_passing_grade' => SystemConfig::getValue('quiz_passing_grade', 60),
            'session_timeout_hours' => SystemConfig::getValue('session_timeout_hours', 8),
            'autosave_interval_seconds' => SystemConfig::getValue('autosave_interval_seconds', 30),
        ];

        return view('admin.settings.index', compact('configs'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'threshold_level1'           => 'required|integer|min:0|max:100',
            'threshold_level3'           => 'required|integer|min:0|max:100',
            'quiz_passing_grade'         => 'required|integer|min:0|max:100',
            'session_timeout_hours'      => 'required|integer|min:1|max:24',
            'autosave_interval_seconds'  => 'required|integer|min:10|max:120',
        ]);

        if ($validated['threshold_level1'] >= $validated['threshold_level3']) {
            return back()->with('error', 'Ambang batas Level 1 harus lebih kecil dari ambang batas Level 3.')
                         ->withInput();
        }

        foreach ($validated as $key => $value) {
            SystemConfig::setValue($key, $value);
        }

        return back()->with('success', 'Konfigurasi sistem berhasil disimpan.');
    }

    // ─── LAPORAN ─────────────────────────────────────────────────
    public function reports()
    {
        $students = User::where('role', 'student')
            ->with(['pretests' => fn($q) => $q->latest()->limit(1)])
            ->get();

        $quizResults = QuizResult::with(['user', 'quiz.material'])->get();

        $remedials = RemedialRecommendation::with(['user', 'material'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.reports.index', compact('students', 'quizResults', 'remedials'));
    }

    public function exportReport(Request $request)
    {
        $format = $request->query('format', 'csv');
        $type   = $request->query('type', 'students');

        if ($type === 'students') {
            $data = User::where('role', 'student')
                ->select('name', 'email', 'level', 'pretest_completed', 'is_active', 'created_at')
                ->get();

            $filename = 'laporan_mahasiswa_' . now()->format('Ymd_His');
            $headers  = ['Nama', 'Email', 'Level', 'Pretest Selesai', 'Status Aktif', 'Tanggal Daftar'];
            $rows     = $data->map(fn($u) => [
                $u->name,
                $u->email,
                $u->level,
                $u->pretest_completed ? 'Ya' : 'Belum',
                $u->is_active ? 'Aktif' : 'Nonaktif',
                $u->created_at->format('d/m/Y'),
            ])->toArray();
        } elseif ($type === 'quiz_results') {
            $data = QuizResult::with(['user', 'quiz.material'])
                ->get();

            $filename = 'laporan_nilai_kuis_' . now()->format('Ymd_His');
            $headers  = ['Mahasiswa', 'Email', 'Kuis', 'Materi', 'Nilai', 'Tanggal'];
            $rows     = $data->map(fn($r) => [
                $r->user->name ?? '-',
                $r->user->email ?? '-',
                $r->quiz->title ?? '-',
                $r->quiz->material->title ?? '-',
                $r->score,
                $r->created_at->format('d/m/Y'),
            ])->toArray();
        } else {
            return back()->with('error', 'Tipe laporan tidak dikenali.');
        }

        // Export CSV
        $csvContent = implode(',', $headers) . "\n";
        foreach ($rows as $row) {
            $csvContent .= implode(',', array_map(fn($v) => '"' . str_replace('"', '""', $v) . '"', $row)) . "\n";
        }

        return response($csvContent, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ]);
    }
}
