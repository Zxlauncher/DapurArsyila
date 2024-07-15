<!DOCTYPE html>
<html>
<head>
    <title>Midtrans Payment</title>
    <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</head>
<body>
<script type="text/javascript">
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result){
            window.location.href = '{{ route('payment.success') }}?order_id=' + result.order_id;
        },
        onPending: function(result){
            window.location.href = '{{ route('payment.success') }}?order_id=' + result.order_id;
        },
        onError: function(result){
            window.location.href = '{{ route('payment.cancel') }}';
        }
    });
</script>
</body>
</html>
