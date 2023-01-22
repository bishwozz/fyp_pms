@foreach ($unreadNotifications as $notification)
	@if ($notification->type === "App\Notifications\MinimumStockAlertNotification")
		{{-- <a class="dropdown-item" style="padding: 1em;
		border-radius: 0 0 2px 2px;
		color: #fff !important;
		background-color: #ffc107 !important;
		font-family: sans-serif;
		line-height: 1.15;
		font-size: 14px;" href="{{ route('stock.notification.markread', $notification->id) }}">
			<i class="la la-warning" style="font-size: 32px;"></i>
			{!! $notification->data['message'] !!}
		</a>
		<hr> --}}

		<div class="notification warning" style="background-color: #fff;
		padding: 15px;
		width: 450px;
		border-radius: 20px;
		box-shadow: 0 2px 5px #00000033;
		cursor: pointer;
		font-weight: 500;
		display: flex;
		align-items: center;
		gap: 15px;
		position: relative;
		overflow: hidden;
		border: 1px solid #ef94007c;
  		background-color:  #ef94002d;
		">
			<i class="fa fa-solid fa-exclamation" style="color: #fff;
			border-radius: 50%;
			font-size: 20px;
			width: 37px;
			height: 37px;
			display: flex !important;
			justify-content: center;
			align-items: center;
			background-color: #ef9400c4;
			"></i>
			<a href="{{ route('stock.notification.markread', $notification->id) }}">
			<span style="font-size: 18px;
			color: #5d6672 !important;
			line-height: 1.6;
			white-space: nowrap;">{!! $notification->data['message'] !!}</span>
			</a>
		  </div>
	@endif
@endforeach
