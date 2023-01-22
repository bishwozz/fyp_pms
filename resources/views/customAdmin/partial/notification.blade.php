@foreach ($unreadNotifications as $notification)
	@if ($notification->type === "App\Notifications\MinimumStockAlertNotification")
		<a class="dropdown-item" href="{{ route('stock.notification.markread', $notification->id) }}">
			<i class="la la-user"></i>
			{!! $notification->data['message'] !!}
		</a>
	@endif
@endforeach
