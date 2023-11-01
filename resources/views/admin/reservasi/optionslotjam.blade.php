@if (count($slotJams) == 0)
    <option selected disabled>Tidak Ada Slot Jam Tersedia</option>
@else
    <option selected disabled>Pilih Slot Jam</option>
    @foreach ($slotJams as $slotJam)
        <option value="{{ $slotJam->id }}">
            {{ $slotJam->jam }}</option>
    @endforeach
@endif
