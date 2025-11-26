<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventController extends Controller
{
    /* ===========================
     |         PUBLIK
     |===========================*/

    // Daftar event terpublikasi (yang sedang/akan berlangsung duluan)
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $events = Event::published()
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('title', 'like', "%{$q}%")
                      ->orWhere('deskripsi', 'like', "%{$q}%")
                      ->orWhere('location', 'like', "%{$q}%");
                });
            })
            ->orderByRaw("CASE WHEN end_at IS NULL THEN 0 WHEN end_at < NOW() THEN 1 ELSE 0 END ASC") // prioritaskan yang belum lewat
            ->orderBy('start_at', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('event.index', compact('events', 'q'));
    }

    // Detail event berdasarkan slug (hanya yang published)
    public function show(Event $event)
    {
        if (!$event->is_published && !$this->isAdmin()) {
            abort(404);
        }
        return view('event.show', compact('event'));
    }

    /* ===========================
     |         ADMIN
     |===========================*/

    // List semua event (admin)
    public function adminIndex(Request $request)
    {
        $this->mustBeAdmin();

        $q     = trim((string) $request->get('q', ''));
        $pub   = $request->get('published'); // '1' / '0' / null (semua)
        $range = $request->only(['start_from', 'start_to']); // optional filter tanggal

        $events = Event::query()
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('title', 'like', "%{$q}%")
                      ->orWhere('deskripsi', 'like', "%{$q}%")
                      ->orWhere('location', 'like', "%{$q}%");
                });
            })
            ->when($pub !== null && ($pub === '0' || $pub === '1'), function ($qr) use ($pub) {
                $qr->where('is_published', $pub === '1');
            })
            ->when(!empty($range['start_from']), function ($qr) use ($range) {
                $qr->whereDate('start_at', '>=', $range['start_from']);
            })
            ->when(!empty($range['start_to']), function ($qr) use ($range) {
                $qr->whereDate('start_at', '<=', $range['start_to']);
            })
            ->latest('start_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.event.index', compact('events', 'q', 'pub', 'range'));
    }

    // Form create (admin)
    public function create()
    {
        $this->mustBeAdmin();
        return view('admin.event.create');
    }

    // Simpan event baru (admin)
    public function store(Request $request)
    {
        $this->mustBeAdmin();

        $data = $this->validateEvent($request);

        // Normalisasi datetime-local (jika form pakai input[type=datetime-local])
        $data['start_at'] = $this->parseDateTimeLocal($data['start_at']);
        $data['end_at']   = $data['end_at'] ? $this->parseDateTimeLocal($data['end_at']) : null;

        // Cegah end < start
        if ($data['end_at'] && $data['end_at']->lt($data['start_at'])) {
            return back()->withInput()->withErrors(['end_at' => 'Tanggal berakhir tidak boleh lebih awal dari tanggal mulai.']);
        }

        // Slug unik (boleh override jika dikirim), kalau tidak -> auto
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug(Str::limit($data['title'], 60, ''), '-') . '-' . Str::random(6);
        } else {
            // pastikan unik
            if (Event::where('slug', $data['slug'])->exists()) {
                return back()->withInput()->withErrors(['slug' => 'Slug sudah digunakan.']);
            }
        }

        Event::create($data);

        return redirect()->route('admin.event.index')->with('success', 'Event berhasil dibuat.');
    }

    // Form edit (admin) — binding lewat slug
    public function edit(Event $event)
    {
        $this->mustBeAdmin();
        return view('admin.event.edit', compact('event'));
    }

    // Update event (admin)
    public function update(Request $request, Event $event)
    {
        $this->mustBeAdmin();

        $data = $this->validateEvent($request, $event);

        $data['start_at'] = $this->parseDateTimeLocal($data['start_at']);
        $data['end_at']   = $data['end_at'] ? $this->parseDateTimeLocal($data['end_at']) : null;

        if ($data['end_at'] && $data['end_at']->lt($data['start_at'])) {
            return back()->withInput()->withErrors(['end_at' => 'Tanggal berakhir tidak boleh lebih awal dari tanggal mulai.']);
        }

        // Jika slug kosong, biarkan slug lama; jika ada dan berubah -> cek unik
        if (!empty($data['slug']) && $data['slug'] !== $event->slug) {
            if (Event::where('slug', $data['slug'])->where('id', '!=', $event->id)->exists()) {
                return back()->withInput()->withErrors(['slug' => 'Slug sudah digunakan.']);
            }
        } else {
            unset($data['slug']); // jangan sentuh slug jika tidak diubah
        }

        $event->update($data);

        return redirect()->route('admin.event.index')->with('success', 'Event berhasil diperbarui.');
    }

    // Hapus event (admin)
    public function destroy(Event $event)
    {
        $this->mustBeAdmin();

        $event->delete();

        return redirect()->route('admin.event.index')->with('success', 'Event dihapus.');
    }

    // Toggle publish/unpublish cepat (admin)
    public function togglePublish(Event $event)
    {
        $this->mustBeAdmin();

        $event->update(['is_published' => !$event->is_published]);

        return back()->with('success', 'Status publikasi diubah.');
    }

    /* ===========================
     |       Helper & Validasi
     |===========================*/

    private function mustBeAdmin(): void
    {
        if (! $this->isAdminOrWaka()) abort(403);
    }

    private function isAdminOrWaka(): bool
    {
        return Auth::check() && in_array(Auth::user()->role, ['admin', 'waka']);
    }


    private function isAdmin(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Validasi request create/update event.
     * - `start_at` & `end_at` diasumsikan string dari input form (datetime-local) lalu diparse manual.
     */
    private function validateEvent(Request $request, ?Event $existing = null): array
    {
        return $request->validate([
            'title'        => ['required', 'string', 'max:200'],
            'slug'         => ['nullable', 'string', 'max:220', Rule::unique('events', 'slug')->ignore(optional($existing)->id, 'id')],
            'deskripsi'    => ['nullable', 'string'],
            'location'     => ['nullable', 'string', 'max:255'],
            'start_at'     => ['required', 'string'], // parse manual
            'end_at'       => ['nullable', 'string'], // parse manual
            'is_published' => ['sometimes', 'boolean'],
        ]);
    }

    /**
     * Parse input dari <input type="datetime-local"> -> Carbon instance.
     * Menerima juga format tanggal standar agar fleksibel.
     */
    private function parseDateTimeLocal(?string $val): ?Carbon
    {
        if (!$val) return null;

        // kalau ada 'T', itu format datetime-local (Y-m-d\TH:i)
        if (preg_match('~^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}~', $val)) {
            return Carbon::createFromFormat('Y-m-d\TH:i', substr($val, 0, 16), config('app.timezone', 'UTC'))->setTimezone('UTC');
        }

        // fallback ke parser Carbon biasa
        try {
            return Carbon::parse($val);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
