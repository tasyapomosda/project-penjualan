<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Debt;
use App\Models\User;
use Illuminate\Http\Request;

class StafController extends Controller
{
    // Daftar staf — route: admin.staf
    public function index()
    {
        $stafList = User::where('role', 'staf')->orderBy('name')->get();

        // Total hutang aktif per nama. Nama staf dipakai sebagai nama pembeli
        // pada tabel hutang, jadi dicocokkan lewat nama.
        $hutangAktif = Debt::where('is_paid', false)
            ->selectRaw('nama_pembeli, SUM(nominal) as total')
            ->groupBy('nama_pembeli')
            ->pluck('total', 'nama_pembeli');

        return view('admin.staf', compact('stafList', 'hutangAktif'));
    }

    // Tambah akun staf — route: admin.staf-store
    public function store(Request $request)
    {
        // Nama harus unik karena dipakai untuk mengelompokkan hutang
        $data = $request->validate([
            'name'     => 'required|string|max:100|unique:users,name',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $staf = new User([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'], // di-hash otomatis oleh cast 'hashed'
        ]);
        // 'role' sengaja diisi eksplisit (tidak masuk $fillable)
        $staf->role = 'staf';
        $staf->save();

        return back()->with('success', 'Akun staf "' . $staf->name . '" berhasil dibuat.');
    }

    // Reset password staf — route: admin.staf-password
    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:8',
        ]);

        // Hanya akun ber-role 'staf' yang bisa diubah dari halaman ini
        $staf = User::where('role', 'staf')->findOrFail($id);
        $staf->password = $request->password;
        $staf->save();

        return back()->with('success', 'Password ' . $staf->name . ' berhasil direset.');
    }

    // Hapus akun staf — route: admin.staf-delete
    public function destroy($id)
    {
        $staf = User::where('role', 'staf')->findOrFail($id);

        // Cegah penghapusan kalau masih ada hutang yang belum lunas
        $adaHutang = Debt::where('is_paid', false)
            ->where('nama_pembeli', $staf->name)
            ->exists();

        if ($adaHutang) {
            return back()->withErrors([
                'hapus' => $staf->name . ' masih punya hutang aktif. Lunasi dulu di halaman Rekap Hutang.',
            ]);
        }

        $staf->delete();

        return back()->with('success', 'Akun staf "' . $staf->name . '" dihapus.');
    }
}