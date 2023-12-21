<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\User;

class AdminController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    

    public function index(){
        $data = [
            'title' => 'Admin Toko'
        ];
        return view('contents.admin.home', $data);
    }

    // produk
    public function produk(Request $request)
    {
        $reqsearch = $request->get('search');  
        $produkdb = Produk::leftJoin('kategori','produk.id_kategori','=','kategori.id')
            ->select('kategori.nama_kategori','produk.*')
            ->when($reqsearch, function($query, $reqsearch){
                $search = '%'.$reqsearch.'%';
                return $query->whereRaw('nama_kategori like ? or nama_produk like ?', [
                        $search, $search
                    ]);
            });
        $data = [
            'title'     => 'Data Produk',
            'kategori'  => Kategori::All(),
            'produk'    => $produkdb->latest()->paginate(5),
            'request'   => $request
        ];
        return view('contents.admin.produk', $data);
    }

    public function edit_produk(Request $request)
    {
        $data = [
            'edit' => Produk::findOrFail($request->get('id')),
            'kategori' => Kategori::All(),
        ];
        return view('components.admin.produk.edit', $data);
    }

    // data proses produk 
    public function create_produk(Request $request)
{
    $validator = Validator::make($request->all(), [
        "id_kategori"   => "required",
        "gambar"        => "required|image|max:10240",
        "nama_produk"   => "required",
        "deskripsi"     => "required",
        "harga_jual"    => "required",
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput()->with("failed", " Gagal Insert Data ! ");
    }

    $image = $request->file('gambar');
    $imageName = 'produk_' . time() . '.' . $image->getClientOriginalExtension();
    $image->storeAs('public/gambar', $imageName);

    Produk::create([
        'id_kategori'   => $request->input("id_kategori"),
        'gambar'        => $imageName,
        'nama_produk'   => $request->input("nama_produk"),
        'deskripsi'     => $request->input("deskripsi"),
        'harga_jual'    => $request->input("harga_jual"),
        'created_at'    => now(),
    ]);

    return redirect()->back()->with("success", " Berhasil Insert Data ! ");
}

public function update_produk(Request $request)
{
    $validator = Validator::make($request->all(), [
        "id"            => "required",
        "id_kategori"   => "required",
        "nama_produk"   => "required",
        "deskripsi"     => "required",
        "harga_jual"    => "required",
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->with("failed", " Gagal Update Data ! ");
    }

    $produk = Produk::findOrFail($request->input('id'));

    if ($request->file('gambar')) {
        $validator = Validator::make($request->all(), [
            "gambar" => "required|image|max:10240",
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with("failed", " Gagal Update Data ! ");
        }

        $image = $request->file('gambar');
        $imageName = 'produk_' . time() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/gambar', $imageName);
        $gambar = $imageName;
    } else {
        $gambar = $produk->gambar;
    }

    $produk->update([
        'id_kategori'   => $request->input("id_kategori"),
        'gambar'        => $gambar,
        'nama_produk'   => $request->input("nama_produk"),
        'deskripsi'     => $request->input("deskripsi"),
        'harga_jual'    => $request->input("harga_jual"),
        'updated_at'    => now(),
    ]);

    return redirect()->back()->with("success", " Berhasil Update Data Produk " . $request->input("nama_produk") . ' !');
}

public function delete_produk(Request $request, $id)
{
    $produk = Produk::findOrFail($id);
    $produk->delete();
    return redirect()->back()->with("success", " Berhasil Delete Data Produk ! ");
}

    // kategori
    public function kategori(Request $request)
    {
        if(!empty($request->get('id'))){
            $edit = Kategori::findOrFail($request->get('id'));
        }
        else{
            $edit = '';
        }

        $data = [
            'title'     => 'Data Kategori',
            'kategori'  => Kategori::paginate(5),
            'edit'      => $edit,
            'request'   => $request
        ];
        return view('contents.admin.kategori', $data);
    }

    // data proses kategori
    public function create_kategori(Request $request)
{
    $validator = Validator::make($request->all(), [
        "nama_kategori" => "required",
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput()->with("failed", " Gagal Insert Data ! ");
    }

    Kategori::create([
        'nama_kategori' => $request->input("nama_kategori"),
        'created_at'    => now(),
    ]);

    return redirect()->back()->with("success", " Berhasil Insert Data ! ");
}

public function update_kategori(Request $request)
{
    $validator = Validator::make($request->all(), [
        "id"            => "required",
        "nama_kategori" => "required",
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->with("failed", " Gagal Update Data ! ");
    }

    Kategori::findOrFail($request->input('id'))->update([
        'nama_kategori' => $request->input("nama_kategori"),
        'updated_at' => now(),
    ]);

    return redirect()->back()->with("success", " Berhasil Update Data ! ");
}


public function delete_kategori(Request $request, $id)
{
    $kategori = Kategori::findOrFail($id);
    $kategori->delete();
    return redirect()->back()->with("success", " Berhasil Delete Data ! ");
}

    // profil
    public function profil(Request $request)
    {
        $data = [
            'title'   => 'Data Profil',
            'edit'    => User::findOrFail(auth()->user()->id),
            'request' => $request,
        ];
        return view('contents.admin.profil', $data);
    }

    // data proses profil
    public function update_profil(Request $request)
{
    $validator = Validator::make($request->all(), [
        "name"                  => "required",
        "email"                 => "required",
        "password"              => "required|min:6",
        "password_confirmation" => "required|min:6|same:password",
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->with("failed", " Gagal Update Data ! ");
    }

    User::findOrFail(auth()->user()->id)->update([
        'name'          => $request->input("name"),
        'email'         => $request->input("email"),
        'phone'         => $request->input("phone"),
        'address'       => $request->input("address"),
        'password'      => Hash::make($request->input("password")),
        'updated_at'    => now(),
    ]);

    return redirect()->back()->with("success", " Berhasil Update Data ! ");
}
}
