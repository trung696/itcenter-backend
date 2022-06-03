<h1 class=" text-info">
    <i class="fa fa-caret-right btn-spx-toggle-toolbar" style="cursor: pointer"></i>
    {{$_title}}
    <small>
        <ol class="breadcrumb">
            <li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
            @isset($routeIndex)
                <li><a href="{{ route($routeIndex) }}">{{$routeIndexText}}</a></li>
            @endisset
            @isset($_action)
                <li lass="active">{{$_action}}</li>
            @endisset
        </ol>
    </small>
</h1>
