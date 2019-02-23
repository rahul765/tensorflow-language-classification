@if(isset($used_compare_compare_selected) && count($used_compare_compare_selected) > 0)
<ul class="list-group navbar-fixed-top compare-widget">
    @foreach($used_compare_compare_selected as $used_vehicle)
    <li class="list-group-item">
        <a href="{{ route('used-vehicle-compare-pop', [$used_vehicle->id]) }}" type="button" class="pull-right btn btn-danger" style="padding:1px 2px;font-size:10px;"><i class="fa fa-close"></i> </a>
        <div class="thumbnail">
            @if(isset($used_vehicle->thumbnail))
                <img src="{{asset('images/cache/small/' . $used_vehicle->thumbnail)}}" class="img-responsive" />
            @elseif(isset($used_vehicle->vehicle->thumbnail))
                <img src="{{asset('images/cache/medium/' . $used_vehicle->vehicle->thumbnail)}}" class="img-responsive" />
            @else
                <img src="{{asset('/carmazic/img/car-clipart.jpg')}}" class="img-responsive" />
            @endif
            <div class="caption">
                <strong>{{ $used_vehicle->vehicle->bname }} {{ $used_vehicle->vehicle->model }} {{ $used_vehicle->vehicle->variant }}</strong>
            </div>
        </div>
    </li>
    @endforeach
    @if(count($used_compare_compare_selected) > 1)
    <li class="list-group-item">
        <a href="{{ route('used-vehicle-compare', ['ids' => $ready_to_compare]) }}" type="button" class="pull-right btn btn-primary">Compare</a>
    </li>
    @endif
</ul>
@endif