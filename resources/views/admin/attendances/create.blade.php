<x-admin-layout title="QR Kehadiran" header="Tampilkan QR agar member dapat check-in dan check-out.">
    <x-slot name="actions">
        <a href="{{ route('admin.attendances.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
            <x-icon name="arrow-left" class="h-4 w-4" />
            Kembali
        </a>
    </x-slot>

    <x-card title="QR Kehadiran Gym" subtitle="Member scan QR ini dari dashboard mereka untuk berganti status check-in atau check-out.">
        <div class="flex flex-col items-center gap-5 py-4 text-center">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700">
                <canvas data-attendance-qr="{{ $attendanceQrToken }}" width="280" height="280" aria-label="QR kehadiran gym"></canvas>
            </div>
            <p class="max-w-md text-sm text-slate-500 dark:text-slate-400">Biarkan halaman ini terbuka di layar resepsionis. Setiap member wajib login sebelum menggunakan kamera untuk scan.</p>
        </div>
    </x-card>
</x-admin-layout>
