<!DOCTYPE html>
<html>
<head>
<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    text-align: center;
	width: 100%;
}
.order_form {
    text-align: center;
    width: 90%;
    margin: 0 auto;
    color: #2180d4 !important;
    padding: 20px;
    background: #fff;
    box-shadow: -1px 0px 10px 6px #eee;
}
.bg_color h2 {
    color: #000;
    text-align: left;
    padding:7px;
}
.bg_color{background:#e2f3ff;}
table tr td:first-child {
    color: #2180d4 !important;
    font-weight: 700;
}
.Delivery p a {
    color: #2180d4 !important;
}
tr.total_amount{
    line-height: 20px;
    font-weight: 600 !important;
}
tr.total_amount td {
    font-weight: 600 !important;
}
tr.total_amount:hover {
    background: transparent;
}
td, th {
  text-align: left;
  padding: 8px;
}
.note, .Delivery {
    text-align: left;
	color: #000;
}
.email_ft p, .note p {
    margin: 3px 0;
}
.note h2 {
    text-decoration: underline;
}
.text-center p {
    text-align: center;
    margin: 3px 0 0;
}
.text-center {
    margin: 20px 0 0 ;
}
.text-center p:first-child {
    font-weight: 600;
}
.top_logo img{width:100%}
</style>
</head>
<body>
<div class="order_form">
<div class="top_logo">
<img src="{{ $data['vlogo'] }}" style="height: 50px;width: 100px;">
</div>
<p>Welcome to the {{ ucfirst($data['vchsitename']) }}</p>
<p>Hi {{ ucfirst($data['vchfirst_name']) }}, </p>

<div class="note">
<p style="text-align: center;padding: 0 30%;">Thanks for signing up! We just need you to verify your email address to complete setting up your account</p>

<p style="text-align: center;padding: 0 30%;margin-top: 30px;"> <a href="{{$data['siteurl']}}/verifyaccount/{{$data['userid']}}" style="border: 1px solid #3167d8;padding: 3% 16%;font-size: 20px;text-decoration: none;background: #3167d8;color: #fff;border-radius: 25px;">Verify My Email</a></p>

</div>
<div class="Delivery">


<div class="text-center">
<p><a href="#"> Copyright {{date("Y")}} {{ ucfirst($data['vchsitename']) }}</a></p>

</div>
</div>
</div>
</body>
</html>
