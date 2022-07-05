<form class="form-horizontal" action="{{route('route_frontend_store')}}" method="post">
        @csrf
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <!-- @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror -->
                    <div class="form-group">
                        <label for="name" class="col-md-3 col-sm-4 control-label">Name <span class="text-danger">(*)</span></label>
                        <div class="col-md-9 col-sm-8">
                            <input type="text" name="name" @error('name') is-invalid @enderror id="name" class="form-control">
                            <span id="mes_sdt"></span>
                        </div>
                    </div>

                    <!-- @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror -->
                    <div class="form-group">
                        <label for="email" class="col-md-3 col-sm-4 control-label">Email <span class="text-danger">(*)</span></label>
                        <div class="col-md-9 col-sm-8">
                            <input type="text" name="email" @error('email') is-invalid @enderror id="email" class="form-control" >
                            <span id="mes_sdt"></span>
                        </div>
                    </div>
<!--                    
                    @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror -->
                    <div class="form-group">
                        <label for="email" class="col-md-3 col-sm-4 control-label">Ngay sinh <span class="text-danger">(*)</span></label>
                        <div class="col-md-9 col-sm-8">
                            <input type="date" name="birthday" @error('birthday') is-invalid @enderror id="birthday" class="form-control" >
                            <span id="birthday"></span>
                        </div>
                    </div>
                  
                    <!-- @error('address')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror -->
                    <div class="form-group">
                        <label for="address" class="col-md-3 col-sm-4 control-label">Phone <span class="text-danger">(*)</span></label>
                        <div class="col-md-9 col-sm-8">
                            <input type="text" name="phone" @error('phone') is-invalid @enderror id="phone" class="form-control" >
                            <span id="phone"></span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary"> Save</button>
        </div>
    </form>