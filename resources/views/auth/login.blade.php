@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block"><img class="" style="width: 100%;height: 100%" src="{{ URL::asset('img/login_img.jpg') }}"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">{{ trans('text.authentification') }}</h1>
                  </div>

                      <form class="" action="{{ url('authentification1') }}" method="post">
                          {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="control-label">NNI الرقم الوطني</label>
                        <div class="">
                            <input id="" type="number" class="form-control form-control-user " name="nni"  >

                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="control-label">Numéro d'Inscription رفم التسجيل </label>
                        <div class="">
                            <input id="" type="" class="form-control form-control-user " name="nodos" required>

                        </div>
                    </div>
                    <div class="form-group">

                        <button type="submit" class="btn btn-user btn-primary btn-block">
                            التسجيل
                            <br>
                            {{ trans('text.connexion') }}
                        </button>
                    </div>
                  </form>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
