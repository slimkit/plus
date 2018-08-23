
<script src="{{ asset('assets/pc/js/pingpp.js')}}"></script>
<script>
var charge = <?php echo json_encode($charge) ?>;
// ping++ 创建支付宝支付
pingpp.createPayment(charge);
</script>
