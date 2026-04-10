<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;

class AdminDosenController extends Controller
{
    // Menampilkan daftar dosen + fitur pencarian
    public function index(Request $request)
    {
        $q = $request->query('q');

        $dosens = Dosen::query()
            ->withCount([
                'proposals as assigned_count' => fn($x) =>
                    $x->where('status', 'verified'),
            ])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'ilike', "%{$q}%")
                       ->orWhere('title', 'ilike', "%{$q}%")
                       ->orWhere('expertise', 'ilike', "%{$q}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.dosens.index', compact('dosens'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'title' => ['nullable', 'string', 'max:50'],
            'expertise' => ['nullable', 'string', 'max:255'],
            'quota' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        if (isset($data['expertise'])) {
            $data['expertise'] = trim(preg_replace('/\s+/', ' ', $data['expertise']));
        }

        $data['quota'] = $data['quota'] ?? 0;

        Dosen::create($data);

        return back()->with('success', 'Dosen berhasil ditambahkan.');
    }

    public function update(Request $request, Dosen $dosen)
    {
        $data = $request->validate([
            'quota' => ['required', 'integer', 'min:0', 'max:999'],
            'expertise' => ['required', 'string', 'max:255'],
        ]);

        $data['expertise'] = trim(preg_replace('/\s+/', ' ', $data['expertise']));

        $dosen->update($data);

        return back()->with('success', 'Data dosen berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen)
    {
        $dosen->delete();

        return back()->with('success', 'Dosen berhasil dihapus.');
    }
}