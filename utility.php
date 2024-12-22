<?php
if(!session_id())
    session_start();
    
include ("connection.php");

function pay_esewa($amt, $pid)
{   
       
    $url = "https://uat.esewa.com.np/epay/main";
    $data = [
        'amt' => $amt,
        'pdc' => 0,
        'psc' => 0,
        'txAmt' => 0,
        'tAmt' => $amt,
        'pid' => $pid,
        'scd' => 'EPAYTEST',
        'su' => 'http://localhost/Canteen-Token-Generator/',
        'fu' => 'http://localhost/onlineliquorstore/payment/payFail.php?q=fu'
    ];
?>
<form id="myForm" action="<?= $url ?>" method="post">
    <?php
        foreach ($data as $name => $value) {
            echo '<input type="hidden" name="' . htmlentities($name) . '" value="' . htmlentities($value) . '">';
        }
        ?>
</form>
<script type="text/javascript">
document.getElementById('myForm').submit();
</script>
<?php
}
?>