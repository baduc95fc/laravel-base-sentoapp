@if(Session::has('message'))
    <div class="alert {{ Session::get('alert-class', 'bg-success') }}">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <p>{{ Session::get('message') }}</p>
    </div>
@endif
@if(Session::has('infor'))
    <p class="alert {{ Session::get('alert-class', 'alert bg-info fade in alert-dismissable') }}">{{ Session::get('infor') }}</p>
@endif
@if (count($errors) > 0)
    <div class="alert bg-danger">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (\Session::has('imageIsSelectedError'))
    <div class="alert bg-danger">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <ul>
            <li>{{\Session::get('imageIsSelectedError')}}</li>
        </ul>
    </div>
@endif
@if (\Session::has('videoIsSelectedError'))
    <div class="alert bg-danger">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <p>{{\Session::get('videoIsSelectedError')}}</p>
    </div>
@endif
@if(Session::has('success'))
    <div class="alert bg-success">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <p>{{ Session::get('success') }}</p>
    </div>
@endif

@if(Session::has('error'))
    <div class="alert bg-danger">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <p>{{ Session::get('error') }}</p>
    </div>
@endif