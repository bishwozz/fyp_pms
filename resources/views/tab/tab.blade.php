<div class="row mb-2">
  <div class="col-md-12">
        <ul class="nav nav-tabs flex-column flex-sm-row mt-2"id="myTab" role="tablist">
            @foreach ($links as $link)   
            @php
            $activeClass = '';
            $activeClass .= url($link['href']) == url()->current() ? ' active ': '';
            $activeClass .= url($link['href']) == url()->current() ? '': 'bg-primary text-white';
            @endphp
            <li role="presentation" class="nav-item border border-white">
                <a class="nav-link tab-link {{ $activeClass }} p-1 pr-2 pl-2" 
                href="{{ url($link['href']) }}" role="tab">{{ $link['label'] }}</a>
            </li>
            @endforeach
        </ul>
      </div>
</div>

<style>
  .nav-tabs .nav-link.active{
    color:#da4040;
    font-weight:550;
    border-color: rgba(241, 241, 241, 0.66);
    border-bottom: 2px solid #467fcf;
  }
</style>