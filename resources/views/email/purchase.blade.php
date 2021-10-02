<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title></title>
  <style type="text/css">
			@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap');
  </style>
</head>

<body bgcolor="#e1e5e8" style="margin-top:0 ;margin-bottom:0 ;margin-right:0 ;margin-left:0 ;padding-top:0px;padding-bottom:0px;padding-right:0px;padding-left:0px;background-color:#fff;">
  <center style="width:100%;table-layout:fixed;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#fff;">
    <div style="max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto;">
      <table align="center" cellpadding="0" style="border-spacing:0;font-family:'Muli',Arial,sans-serif;;Margin:0 auto;width:100%;">
        <tbody>
          <tr>
            <td align="center" class="vervelogoplaceholder" height="143" style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;height:143px;vertical-align:middle;" valign="middle"><span class="sg-image"><a href="{{$data['siteurl']}}" target="_blank"><img alt="{{$data['vchsitename']}}" height="34" src="{{$data['vlogo']}}" style="border-width: 0px;width: 230px;height: 85px;" width="160"></a></span></td>
          </tr>
          <!-- Start of Email Body-->
          <tr>
            <td class="one-column" style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;background-color:#{{$data['surface']}};box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">
              <table style="border-spacing:0;" width="100%">
                <tbody>
                  <tr>
                    <td class="inner contents center" style="padding-top: 9px;padding-bottom:38px;padding-right: 15px;padding-left: 15px;text-align:center;">
                      <center>
                        <p class="h1 center" style="padding-bottom: 4px;Margin:0;text-align:center;font-family:'Roboto', sans-serif;font-weight: 700;font-size: 20px;line-height: 26px;color: #{{$data['surfacetext_iconcolor']}};">Receipt from {{$data['vchsitename']}}</p>

						<p style="padding-bottom: 20px;Margin:0; text-align:center;"><a style="font-family:'Roboto', sans-serif;font-weight: 400;font-size: 16px;line-height:20px;color: #{{$data['hyperlink']}}; text-decoration: none" href="{{$data['receipt_url']}}">Download receipt PDF</a></p>

						<div class="main_section" style="text-align:left; display: inline; clear: both;">
							<div class="first_section" style="width: 50%; float:left;"><p style="font-family:'Roboto', sans-serif;font-weight: 700;font-size: 16px;line-height: 18px; color: #{{$data['surfacetext_iconcolor']}}; text-transform:uppercase; margin-bottom: 0">Amount paid</p><p style="font-family:'Roboto', sans-serif;font-weight: 400;font-size: 14px;line-height: 18px;color:#{{$data['surfacetext_iconcolor']}};margin-top: 0">${{$data['strip_amount']}}</p></div>
							
							<div class="second_section" style="width: 50%; float:left; text-align:right;"><p style="font-family:'Roboto', sans-serif;font-weight: bold;font-size: 16px;line-height: 18px; color: #{{$data['surfacetext_iconcolor']}}; text-transform:uppercase; margin-bottom: 0">date paid</p><p style="font-family:'Roboto', sans-serif;font-weight: 400;font-size: 14px;line-height: 18px;color:#{{$data['surfacetext_iconcolor']}}; margin-top: 0">{{ $data['payment_time']}}</p></div>
						</div>
						
							<p style="font-family:'Roboto', sans-serif; clear: both; Margin: 0 0 5px 0;text-align: left;max-width: 100%;font-weight: 700;font-size: 16px;line-height: 20px; color: #{{$data['surfacetext_iconcolor']}}; text-transform:uppercase;">summary</p>
							
						<div class="summary_section" style="text-align:left; width: 97%; display: inline-block; clear: both; padding: 5px 8px;background: #{{$data['background_color']}};">
							<p style="font-family:'Roboto', sans-serif; clear: both; Margin:0;text-align: left;max-width: 100%;font-weight: 400;font-size: 16px;line-height: 20px; color: #{{$data['bgtext_iconcolor']}}; text-transform:uppercase;">{{$data['package_startdate']}} - {{$data['expiry_date']}}</p>
							<div class="first_section" style="width: 50%; float:left;margin: 20px 0 10px"><p style="font-family:'Roboto', sans-serif;font-weight: 400;font-size: 14px;line-height: 18px; color:#{{$data['bgtext_iconcolor']}};margin:0;">{{$data['package_title']}} - {{$data['purchase_type']}} - {{$data['package_name']}}</p></div>
						
							<div class="third_section" style="width: 50%; float: right; margin: 20px 0 10px;"><p style="font-family:'Roboto', sans-serif;font-weight: 400;font-size: 14px;line-height: 18px; color: #{{$data['bgtext_iconcolor']}}; text-align:right;margin:0;">${{$data['strip_amount']}}</p></div>
							
							<div class="seconds_section" style="margin-top:50px;margin-bottom:50px;clear: both;border-top: 1px solid #cccccc">
							<div class="first_section" style="width: 50%; float:left;margin: 25px 0"><p style="font-family:'Roboto', sans-serif;font-weight: 700;font-size: 16px;line-height: 20px;color:#{{$data['bgtext_iconcolor']}};margin:0;">Subtotal</p>
							<p style="font-family:'Roboto', sans-serif;text-align:left; line-height: 18px;font-weight: 400;font-size: 14px; color: #{{$data['bgtext_iconcolor']}};margin: 10px 0 0;">Tax (0%)</p></div>
						
							<div class="third_section" style="width: 50%; float: right;margin: 25px 0"><p style="font-family:'Roboto', sans-serif;font-weight: 700;font-size: 16px;line-height: 20px; color: #{{$data['bgtext_iconcolor']}}; text-align:right;margin:0;">${{$data['strip_amount']}}</p><p style="font-family:'Roboto', sans-serif;text-align:right; line-height: 18px;font-weight: 400;font-size: 14px; color: #{{$data['bgtext_iconcolor']}};margin: 10px 0 0">$0.00</p></div></div>
							
							<div class="thirds_section" style="margin-top:50px;clear: both;">
							<div class="first_section" style="width: 50%; float:left;margin: 5px 0;"><p style="font-family:'Roboto', sans-serif;font-weight: 700;font-size: 16px;line-height: 20px;color:#{{$data['bgtext_iconcolor']}};margin:0;">Amount paid</p></div>
						
							<div class="third_section" style="width: 50%; float: right;margin:5px 0;"><p style="font-family:'Roboto', sans-serif;font-weight: 700;font-size: 16px;line-height: 20px; color: #{{$data['bgtext_iconcolor']}}; text-align:right;margin:0;">${{$data['strip_amount']}}</p>
						</div>
					</div>
					</center>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <!-- Social Media -->
          <tr>
            <td align="center" style="padding-bottom:0;padding-right:0;padding-left:0;padding-top: 13px;font-size: 12px;" valign="middle"><span class="sg-image" ><a style="text-decoration: none;color: #{{$data['hyperlink']}};line-height: 16px;font-weight: 300;" href="{{$data['siteurl']}}/privacypolicy" target="_blank">Privacy Policy</a></span>
			<span style="color: #{{$data['bgtext_iconcolor']}};margin: 0 15px;">|</span>
			<span class="sg-image" ><a style="text-decoration: none; color: #{{$data['hyperlink']}};line-height: 16px;font-weight: 300;" href="{{$data['siteurl']}}/termscondition" target="_blank">Terms & Conditions</a></span>
			<span style="color: #{{$data['bgtext_iconcolor']}};margin: 0 15px;">|</span>
            <span class="sg-image" ><a style="text-decoration: none; color: #{{$data['hyperlink']}};line-height: 16px;font-weight: 300;" href="{{$data['siteurl']}}/userlicence" target="_blank">User Licence</a></span></td>
          </tr>
          <tr>
            <td height="15">
              
            </td>
          </tr>
          <!-- Footer -->
          <tr>
            <td style="padding-top:0;padding-bottom:0;padding-right:30px;padding-left:30px;text-align:center;Margin-right:auto;Margin-left:auto;">
              <center>
                <p style="font-family:'Roboto', sans-serif;Margin:0;text-align:center;Margin-right:auto;Margin-left:auto;color: #{{$data['bgtext_iconcolor']}};font-weight: 300;font-size: 12px;line-height: 16px;">{{$data['vchsitename']}} All rights reserved.
                </p>
              </center>
            </td>
          </tr>
          <tr>
            <td height="40">
              <p style="line-height: 40px; padding: 0 0 0 0; margin: 0 0 0 0;">&nbsp;</p>

              <p>&nbsp;</p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </center>
</body>