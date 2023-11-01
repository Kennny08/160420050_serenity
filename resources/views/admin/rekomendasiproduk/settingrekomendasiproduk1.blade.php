@extends('layout.adminlayout')

@section('admincontent')
    <div class="page-title-box">

    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3>Rekomendasi Produk</h3>
                    <p class="sub-title">
                    </p>

                    <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2">
                            <div class="p-6">
                                <p>Hola! (Enterprise Corp.)</p>
                                <h3>Apriori</h3>
                                <br>
                                <h6>Support</h6>
                                <p>

                                    @foreach ($freqItemSets as $fis)
                                        <span>{{ $fis }}</span>

                                        <br>
                                    @endforeach
                                </p>
                                <hr>
                                <h6>Confidence</h6>
                                @foreach ($assocRules as $ar => $ar1)
                                    @foreach ($ar1 as $ar2 => $ar3)
                                        <p>jika {{ $ar }} maka {{ $ar2 }} dengan confidence
                                            {{ $ar3 }}%
                                        </p>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>







                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
@endsection

@section('javascript')
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
@endsection
