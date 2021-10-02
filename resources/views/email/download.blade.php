<!DOCTYPE html>
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
			<title>Download</title>
			<style>
			body{background:#eff2f7;font-family:Montserrat,sans-serif}.brder-pss tr{border-bottom:1px solid #ccc}.man-table{width:100%;background:#fff;max-width:650px}.logo_gr{padding:15px;background:#fff}.logo_gr img{width:200px;margin:0 auto}.fst-td{width:155px;height:22px;text-align:left;vertical-align:top;background:#0190e7;font-family:Arial;padding-left:10px;font-size:14px;color:#fff;padding-top:5px;padding-bottom:3px;line-height:20px}.result-td{height:22px;text-align:left;vertical-align:top;background:#f4f4f4;font-family:Arial;font-size:14px;color:#333;padding:5px 10px 5px 10px;line-height:20px}
			</style>
			</head> 
			<body leftmargin="0" topmargin="0" yahoo="fix" style="" marginheight="0" marginwidth="0" style="background: #eff2f7;font-family: 'Montserrat', sans-serif;">
			<div style="background: #eff2f7;font-family: 'Montserrat', sans-serif;">	
				<table class="man-table" align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#fff" >
				<tbody>
				<tr>
				  <td bgcolor="" valign="top">		
					<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"  >
						<tbody>
							<tr>
								<td class="logo_gr">
								<img src="{{ ucfirst($data['vlogo']) }}" width="200px" >
								</td>
							</tr>
						</tbody>
					</table>

				  <table class="deviceWidth new_temp" align="center"  border="0" cellpadding="0" cellspacing="0" width="100%" style="background:#fff">
					<tbody>
					  <tr>	
					 <td width="100%" align="center" style="display:inline-block;">
					  <h1 class="heading" style="font-weight: 100;">Welcome to the {{ ucfirst($data['vchsitename']) }}</h1>
					  <p class="paratext" style="padding:20px 20px;margin-bottom:0;font-size: 15px;margin: 0 0 0;text-align: center;color: #009688;font-weight: 600;">Hi {{ ucfirst($data['vchfirst_name']) }}, Please login and download the content form link
					  
					  </p>
					  <p class="descrption"><a href="{{ $data['downloadlink'] }}" style="border: 1px solid #4CAF50;padding: 10px 59px;  background: #009688;border-radius: 5px;  font-size: 25px; text-decoration: none;color: #fff;">Download</a></p>
					 </td>			
					</tr>
					</tbody>
				  </table> 
				
				  
				  <table class="template_footer" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 650px;background: #f9fafc;" >
						<tbody>
						  <tr>
							<td style="padding: 10px 0pt;" >
						  <p class="cp-right" style="color: #03A9F4;text-align: center; font-size: 14px;font-family: arial;margin: 0;" > Copyright 2020 {{ ucfirst($data['vchsitename']) }} <span style="font-size: 31px;padding: 0px 10px;top: 7px;position: relative;color: #bbb;">&#8226;</span> All Rights Reserved <span style="font-size: 31px;padding: 0px 10px;top: 7px;position: relative;color: #bbb;">&#8226;</span> Powered by {{ ucfirst($data['vchsitename']) }}</p>
							</td>
						  </tr>
						</tbody>
				  </table>
					</tr>
				   </tbody>
				</table>
			</div>	
			</body>
			</html>