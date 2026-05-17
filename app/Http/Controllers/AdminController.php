<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\Pretest;
use App\Models\QuizResult;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_lecturers' => User::where('role', 'lecturer')->count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_materials' => Material::count(), 
            'total_quizzes' => Quiz::count(), 
            'total_pretests' => Pretest::count(), 
            'total_pretes' => Pretest::count(), 
            'remedial_active' => 0, 
        ];

        $levelDistribution = [
            'Pemula' => User::where('role', 'student')->where('level', 'Pemula')->count(),
            'Menengah' => User::where('role', 'student')->where('level', 'Menengah')->count(),
            'Lanjutan' => User::where('role', 'student')->where('level', 'Lanjutan')->count(),
        ];

        $recentUsers = User::where('role', 'student')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'levelDistribution', 'recentUsers'));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('status')) {
            $isActive = $request->status === 'active' ? 1 : 0;
            $query->where('is_active', $isActive);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,lecturer,student'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'level' => $request->role === 'student' ? 'Pemula' : null,
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,lecturer,student'
        ]);

        $data = $request->only(['name', 'email', 'role']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function toggleUserStatus(User $user)
    {
        $user->update([
            'is_active' => !$user->is_active
        ]);

        $statusMessage = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Status pengguna berhasil $statusMessage.");
    }

    public function destroyUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function importUsers(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getPathname(), "r");
        
        $header = true;
        $imported = 0;
        $errors = [];

        while (($row = fgetcsv($handle, 1000, ",")) !== false) {
            if ($header) {
                $header = false;
                continue;
            }

            $name = $row[0] ?? null;
            $email = $row[1] ?? null;
            $password = !empty($row[2]) ? $row[2] : 'password123';

            if ($name && $email) {
                if (User::where('email', $email)->exists()) {
                    $errors[] = "Email $email sudah terdaftar, dilewati.";
                    continue;
                }

                User::create([
                    'name' => trim($name),
                    'email' => trim($email),
                    'password' => Hash::make(trim($password)),
                    'role' => 'student',
                    'level' => 'Pemula',
                    'is_active' => true,
                ]);
                $imported++;
            }
        }
        fclose($handle);

        if (count($errors) > 0) {
            return back()->with('warning', "$imported pengguna berhasil diimport dengan beberapa catatan.")->with('import_errors', $errors);
        }

        return back()->with('success', "$imported pengguna berhasil diimport.");
    }

    public function settings()
    {
        $configs = [
            'threshold_level1' => 40,
            'threshold_level3' => 70,
            'quiz_passing_grade' => 60,
            'session_timeout_hours' => 8,
            'autosave_interval_seconds' => 30,
        ];

        return view('admin.settings.index', compact('configs'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'threshold_level1' => 'required|numeric|min:0|max:100',
            'threshold_level3' => 'required|numeric|min:0|max:100',
            'quiz_passing_grade' => 'required|numeric|min:0|max:100',
            'session_timeout_hours' => 'required|numeric|min:1|max:24',
            'autosave_interval_seconds' => 'required|numeric|min:10|max:120',
        ]);

        foreach ($request->except(['_token', '_method']) as $key => $value) {
            DB::table('system_configs')->updateOrInsert(['key' => $key], ['value' => $value]);
        }

        return back()->with('success', 'Konfigurasi sistem berhasil di-update secara mutlak!');
    }

    public function reports()
    {
        $students = User::where('role', 'student')->latest()->get();
        $remedials = collect([]); 

        return view('admin.reports.index', compact('students', 'remedials'));
    }

    public function exportReport()
    {
        $results = QuizResult::with(['user', 'quiz'])->get();
        
        $csv = "Nama Mahasiswa,Kuis,Skor,Status\n";
        foreach($results as $r) {
            $status = $r->is_passed ? 'Lulus' : 'Gagal';
            $nama = $r->user->name ?? 'Unknown';
            $kuis = $r->quiz->title ?? 'Unknown';
            $csv .= "{$nama},{$kuis},{$r->score},{$status}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="Laporan_AlgoLearn_'.date('Ymd').'.csv"');
    }
}