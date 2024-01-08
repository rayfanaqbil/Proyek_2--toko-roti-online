@extends('layouts.frontend')
@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-sm-9 mx-auto">
            <!--product -->
            <div class="product">
                <h4 class="mb-4"><b>{{ $title }}</b></h4>
                <div class="row">
                    <div class="col-sm-4">
                        <img src="{{ url_images('gambar', $edit->gambar) }}" class="img-fluid w-100 mb-3">
                    </div>
                    <div class="col-sm-8 detail-produk">
                        <div class="row mt-3">
                            <div class="col-sm-4"><b>Kategori</b></div>
                            <div class="col-sm-8">
                                <a class="text-produk" href="{{ url('kategori/'.$edit->id) }}">
                                    {{ $edit->nama_kategori }}
                                </a>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-4"><b>Nama Produk</b></div>
                            <div class="col-sm-8"><?= $edit->nama_produk;?></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-4"><b>Harga jual</b></div>
                            <div class="col-sm-8 text-success"><h4><b>Rp<?= number_format($edit->harga_jual);?>,-</b></h4></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-4"><b>Deskripsi</b></div>
                            <div class="col-sm-8"><?= $edit->deskripsi;?></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-4"><b></b></div>
                            <div class="col-sm-8">
                                <a class="btn btn-success btn-md" 
                                href="https://api.whatsapp.com/send/?phone=6285883803206&text=Halo+Admin+Saya+ingin+membeli+produk+{{url('produk/'.$edit->nama_produk)}}%0A%0ANama%20%3A%20%0AAlamat%20%3A%20%0ABarang%20Pesanan%20%3A%20%0A%0ATransfer%20ke%20Rekening%20BNI%20%3A%208241002201150001%0A%0AJangan%20lupa%20mengirim%20bukti%20pembayaran%2C%20Terima%20kasih%20sudah%20berbelanja%20%3A)">
                                    <i class="fab fa-whatsapp"></i> Pesan Sekarang    
                                </a>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')

@endsection