@extends('layouts.tabler')

@section('content')

    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="user"></i></div>
                            Configuración de cuenta - Perfil
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-4">
        @include('profile.component.menu')

        <hr class="mt-0 mb-4" />

        @include('partials.session')

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('patch')


                <div class="col-xl-8">
                    <!-- Account details card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            Detalles de la cuenta
                        </div>
                        <div class="card-body">
                            <!-- Form Group (username) -->
                            <div class="mb-3">
                                <label class="small mb-1" for="username">Usuario</label>
                                <input class="form-control form-control-solid @error('username') is-invalid @enderror"
                                    id="username" name="username" type="text" placeholder=""
                                    value="{{ old('username', $user->username) }}" autocomplete="off" />
                                @error('username')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <!-- Form Group (name) -->
                            <div class="mb-3">
                                <label class="small mb-1" for="name">Nombre completo</label>
                                <input class="form-control form-control-solid @error('name') is-invalid @enderror"
                                    id="name" name="name" type="text" placeholder=""
                                    value="{{ old('name', $user->name) }}" autocomplete="off" />
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="small mb-1" for="email">
                                    Correo electrónico
                                </label>

                                <input class="form-control form-control-solid @error('photo') is-invalid @enderror"
                                    id="email" name="email" type="text" placeholder=""
                                    value="{{ old('email', $user->email) }}" autocomplete="off" />
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button class="btn btn-primary" type="submit">
                                {{ __('Actualizar') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('page-scripts')
    <script src="{{ asset('assets/js/img-preview.js') }}"></script>
@endpush
