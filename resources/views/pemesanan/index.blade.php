@extends('layout.admin')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Pemesanan</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item active">Pemesanan</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
          <div class="card">
            <div class="card-header">
              <a href="{{route('produk.create')}}" type="button" class="btn btn-info">Create +</a>
                
            </div>
            <!-- /.card-header -->
            <div class="card">
              <div class="card-header">
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Gambar</th>
                      <th scope="col">Nama Produk</th>
                      <th scope="col">Harga</th>
                      <th scope="col">Ukuran</th>
                      <th scope="col">Warna</th>
                      <th scope="col">Stok</th>
                      <th scope="col">Deskripsi</th>
                      <th scope="col">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                      @php
                          $no = 1;   
                      @endphp
                      @foreach ($data as $row)
                      <tr>
                        <th scope="row">{{ $no++ }}</th>
                        <td>
                            <img src="{{  $row-> gambar }}" alt="" style=" width: 50px; ">
                        </td>
                        <td>{{ $row-> nama_produk }}</td>
                        <td>{{ $row-> harga }}</td>
                        <td>{{ $row-> ukuran }}</td>
                        <td>{{ $row-> warna }}</td>
                        <td>{{ $row-> stok }}</td>
                        <td>{{ $row-> deskripsi }}</td>
                        <td>
                            <a href="{{ route('produk.edit', $row->id) }}" type="button" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                            <form class='formdelete' action="{{ route('produk.destroy', $row->id)}}" 
                              method="post">
                              @csrf
                              @method('delete')
                              <button class="btn btn-danger" 
                              type="submit"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Gambar</th>
                        <th scope="col">Nama Produk</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Ukuran</th>
                        <th scope="col">Warna</th>
                        <th scope="col">Stok</th>
                        <th scope="col">Deskripsi</th>
                        <th scope="col">Aksi</th>
                      </tr>
                    </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card-body -->
          </div>
        </div>
        </div>
      </div>
      </div>
      
  
    <!-- Optional JavaScript; choose one of the two! -->
  
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  
  </body>
  </div> 
@endsection