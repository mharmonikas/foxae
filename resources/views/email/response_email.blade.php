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
      <table align="center" cellpadding="0" style="border-spacing:0;font-family:'Muli',Arial,sans-serif;color:#{{$data['surfacetext_iconcolor']}};Margin:0 auto;width:100%;">
        <tbody>
          <tr>
            <td align="center" class="vervelogoplaceholder" height="143" style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;height:143px;vertical-align:middle;" valign="middle"><span class="sg-image"><a href="{{$data['siteurl']}}" target="_blank"><img alt="{{ $data['vchsitename']}}" height="34" src="{{ $data['vlogo'] }}" style="border-width: 0px;width: 230px;height: 85px;"></a></span></td>
          </tr>
          <!-- Start of Email Body-->
          <tr>
            <td class="one-column" style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;background-color:#{{$data['surface']}};box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">
              <table style="border-spacing:0;" width="100%">
                <tbody>
                  <tr>
                    <td class="inner contents center" style="padding-top: 9px;padding-bottom:8px;padding-right: 13px;padding-left: 13px;text-align:left;">
                      <center>
                        <p class="h1 center" style="padding-bottom: 15px;Margin:0;text-align:left;font-family:'Roboto', sans-serif;font-weight: 700;font-size: 20px;line-height: 26px;color: #{{$data['surfacetext_iconcolor']}};">Dear {{ ucfirst($data['vchfirst_name']) }},</p>

                        <p class="description center" style="font-family:'Roboto', sans-serif;Margin:0;text-align: left;max-width: 100%;line-height:20px;Margin-bottom:15px;margin-left: auto;margin-right: auto; font-size: 14px">Thank you contacting our support team! Your message has been received and forwarded to the team. One of our support team staff will contact you as soon as possible!</p>
                       
					    <p class="h1 center" style="padding-bottom: 15px;Margin:0;text-align:left;font-family:'Roboto', sans-serif;font-weight: 700;font-size: 18px;line-height: 24px;color: #{{$data['surfacetext_iconcolor']}};">Your issue:</p>
						
					    <p class="description center" style="font-family:'Roboto', sans-serif;Margin:0;text-align: left;max-width: 100%;line-height:18px;Margin-bottom:15px;margin-left: auto;margin-right: auto; height:222px; font-size: 18px;">{{$data['query']}}</p>
                        </center>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <!-- Social Media -->
          <tr>
            <td align="center" style="padding-bottom:0;padding-right:0;padding-left:0;padding-top: 13px;font-size: 14px;" valign="middle"><span class="sg-image" ><a style="text-decoration: none;color: #{{$data['hyperlink']}};line-height: 11px;font-weight: 300;" href="{{$data['siteurl']}}/privacypolicy" target="_blank">Privacy Policy</a></span>
			<span style="color: #{{$data['bgtext_iconcolor']}};margin: 0 15px;">|</span>
			<span class="sg-image" ><a style="text-decoration: none; color: #{{$data['hyperlink']}};line-height: 11px;font-weight: 300;" href="{{$data['siteurl']}}/termscondition" target="_blank">Terms & Conditions</a></span>
			<span style="color: #{{$data['bgtext_iconcolor']}};margin: 0 15px;">|</span>
            <span class="sg-image" ><a style="text-decoration: none; color: #{{$data['hyperlink']}};line-height: 11px;font-weight: 300;" href="{{$data['siteurl']}}/userlicence" target="_blank">User Licence</a></span></td>
          </tr>
          <tr>
            <td height="15">
              
            </td>
          </tr>
          <!-- Footer -->
          <tr>
            <td style="padding-top:0;padding-bottom:0;padding-right:30px;padding-left:30px;text-align:center;Margin-right:auto;Margin-left:auto;">
              <center>
                <p style="font-family:'Roboto', sans-serif;Margin:0;text-align:center;Margin-right:auto;Margin-left:auto;color: #{{$data['bgtext_iconcolor']}};font-weight: 300;font-size: 14px;line-height: 18px;"><span style="color: #{{$data['bgtext_iconcolor']}};">{{ ucfirst($data['vchsitename']) }}</span> All rights reserved.
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