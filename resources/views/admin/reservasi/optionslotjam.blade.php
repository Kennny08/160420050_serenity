@if (count($slotJams) == 0)
    <option selected disabled>Tidak Ada Slot Jam Tersedia</option>
@else
    <option selected disabled>Pilih Slot Jam</option>
    @foreach ($slotJams as $slotJam)
        @if ($slotJam->status == 'aktif')
            <option value="{{ $slotJam->id }}">{{ $slotJam->jam }}</option>
        @else
            <option class="text-danger" disabled value="{{ $slotJam->id }}">{{ $slotJam->jam }} - Tutup</option>
        @endif
    @endforeach
@endif
