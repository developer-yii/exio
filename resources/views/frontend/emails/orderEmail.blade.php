@component('mail::message')

<h1>You have received a new order.</h1>

## Order Details

- **Order No.:** {{ $customerOrder_details['id'] }}
- **Order Status:** {{ $customerOrder_details['order_status'] }}
@if($customerOrder_details['order_status'] == "Block")
- **Courier Charges:** Please Update Courier Charges
@endif
@component('mail::button', ['url' => route('customer_order', ['openModal' => 1, 'customer_order_id' => $customerOrder_details['id']]), 'color' => 'success'])
    View Order
@endcomponent
Thanks,<br>
{{ config('app.name') }}
@endcomponent