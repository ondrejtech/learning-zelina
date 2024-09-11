<!DOCTYPE html>
<html lang="cs">

<head>
	<meta charset="utf-8" />
	<title> js change url </title>

</head>

<body>

<button type="button" class="app_button">button click</button>


<script>

var d = document;

var app_button = d.querySelector(".app_button");

function ____app_button__click() {

	if(window.history.pushState) {
		window.history.pushState("", "", "/paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=EC-0JL40811ER421181P");
	} else {
		window.location.href = "";
	}

}


app_button.addEventListener("click", ____app_button__click);


/*


https://3dsecure.gpwebpay.com/pgw/card?pgwSessionId=SqwhjV6nIIExmyS1xp7i9PdV26GbSNjS
https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=EC-0JL40811ER421181P
https://secure.paxum.com/payment/processPaymentLogin.php?view=views/processPaymentLogin.xsl
https://merchant.wmtransfer.com/lmi/SignedLoginFormNewWC.asp


/3dsecure.gpwebpay.com/pgw/card?pgwSessionId=SqwhjV6nIIExmyS1xp7i9PdV26GbSNjS
/paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=EC-0JL40811ER421181P
/secure.paxum.com/payment/processPaymentLogin.php?view=views/processPaymentLogin.xsl
/merchant.wmtransfer.com/lmi/SignedLoginFormNewWC.asp


*/


</script>

</body>


</html>

