<div class="form-group row">
    <div class="form-group col-md-12">
        <table id="tabelInformasiDetailPenjualan" class="table table-bordered dt-responsive nowrap text-center"
            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
            <thead>
                <tr>
                    <th hidden>tanggal</th>
                    <th>Tanggal Penjualan</th>
                    <th>Item Penjualan</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($dataSet as $data)
                    <tr>
                        <td hidden>
                            {{ $data['tanggal'] }}
                        </td>
                        <td>
                            {{ $data['tanggalPenjualan'] }}
                        </td>
                        <td>
                            {!! implode(',<br>', $data['detail']) !!}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
