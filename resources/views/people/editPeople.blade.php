@extends('layouts.dashboard')

@push('csss')
<!-- DataTables -->
<link href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">

@endpush

@section('content')
<div class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">

         </div><!-- /.col -->
         <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
               <!-- <li class="breadcrumb-item"><a href="#">Home</a></li> -->
               <li class="breadcrumb-item active">Personal</li>
            </ol>
         </div><!-- /.col -->
      </div><!-- /.row -->
   </div><!-- /.container-fluid -->
</div>

<div class="content">
   <div class="container-fluid">
      <div class="row">
         <!-- /.col-md-6 -->
         <div class="col-12">
            <!-- card -->
            <div class="card">
               <div class="card-header">
                  <h3 class="card-title">DATOS - SOCIO</h3>
               </div><br>
               <!-- /.card-header -->
               <div class="card-body">
                  <form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" action="{{route('people.update', $person['id'])}}">
                     @method('PUT')
                     {{ csrf_field() }}

                     <div class="row">

                        <div class="col-md-2"></div>

                        <div class="col-md-4">

                           <div class="form-group">
                              <label for="identification_card"> Cédula </label>
                              <input type="text" class="form-control" name="identification_card" value="{{$person['identification_card']}}">
                           </div>
                           <div class="form-group">
                              <label for="first_name"> Nombre </label>
                              <input type="text" class="form-control" name="first_name" value="{{$person['first_name']}}">
                           </div>
                           <div class="form-group">
                              <label for="last_name"> Apellido </label>
                              <input type="text" class="form-control" name="last_name" value="{{$person['last_name']}}">
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="phone"> Teléfono </label>
                              <input type="text" class="form-control" name="phone" value="{{$person['phone']}}">
                           </div>
                           <div class="form-group">
                              <label for="email"> Correo electrónico </label>
                              <input type="text" class="form-control" name="email" value="{{$person['email']}}">
                           </div>
                        </div>
                     </div>
                     <br>
                     <div class="row">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                           <div class="container-fluid">
                              <button class="btn btn-success" type="submit">
                                 <i class="far fa-check-square"></i> Guardar
                              </button>
                              <a href="{{route('people.index')}}" class="btn btn-warning">
                                 <i class="far fa-window-close"></i> Cancelar
                              </a>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
               <!-- /.card-body -->
            </div>
            <!-- /.card -->
         </div>
      </div>
      <!-- /.row -->
   </div>
   <!-- /.container-fluid -->
</div>
@endsection

@push('scripts')
<!-- Page specific script -->
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>
<script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
@endpush