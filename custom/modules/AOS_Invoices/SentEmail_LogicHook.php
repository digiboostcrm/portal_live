<?php

Class SentEmail_LogicHook{
	
	function sent_email($bean, $event, $arguments){
			
			if(empty($bean->fetched_row['id']) && !empty($bean->billing_account)){
				/*
				$html = "Dear $bean->billing_account,<br>

					The following Your Invoice Details are :<br>

					Invoice Title :   $bean->name<br>
					Invoice Number:   $bean->number<br>
					Invoice Date:   $bean->invoice_date<br>
					Quote Number:    $bean->quote_number<br>
					Quote Date:   $bean->quote_date<br>
					Due Date:   $bean->due_date<br>
					Status:     $bean->status<br><br><br>
					Total Amount:     $bean->total_amount<br><br>
					";
				$html .=	"If you have any further questions or concerns, please do not hesitate to contact us via phone at the numbers listed below.<br>";

				$html .= "As always, it is our pleasure to assist in any way possible!<br>";

				$html .= "Thank you,";
				*/
				
				$html = '<html>
					<head>
						<meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
						<title></title>
						<style type="text/css">
							p{
								margin-top: 15px;
								line-height: 24px;
							}
						</style>
					</head>
					<body>
						<table border="0" cellspacing="0" cellpadding="0" width="100%">
							<tbody>
								<tr>
									<td align="center" valign="top">
										<table style="border-collapse:collapse;" border="0" cellspacing="0" cellpadding="0" align="center">
											<tbody>
												<tr>
													<td align="center">
														<table border="0" cellspacing="0" cellpadding="0" width="700" bgcolor="#fff">
															<tbody>
																<tr>
																	<td align="center"><img style="display: block;" src="https://digiboost.com/dev/portal/assets/images/email-banner.jpg" border="0" alt="" width="700"></td>                                                        
																</tr>
																<tr>
																	<td style="background-color: #FAFAFA;border-bottom:3px solid #00aeef;font-family: helvetica, arial, verdana, sans-serif; font-size: 14px;color:#505050;padding:40px 32px">
																		Dear <strong style="color:#505050;">'.$bean->billing_account.',</strong>,
																		<p>
																			The following Your Invoice Details are :
																		</p>
																		<p color="balck">
																			<b>Invoice Title :</b>   '.$bean->name.'<br>
																			<b>Invoice Number:</b>   '.$bean->number.'<br>
																			<b>Invoice Date:</b>   '.$bean->invoice_date.'<br>
																			<b>Quote Number:</b>    '.$bean->quote_number.'<br>
																			<b>Quote Date:</b>   '.$bean->quote_date.'<br>
																			<b>Due Date:</b>   '.$bean->due_date.'<br>
																			<b>Status:</b>     '.$bean->status.'<br></br>
																			<b>Total Amount:</b>     '.$bean->total_amount.'</br></br>
																			
																		</p>
																		<br/>
																		<p>As always, it is our pleasure to assist you in any way possible!</p>
																		<p>
																			Thank you,<br/ >
																			DIGIBOOST<br/ >
																			<a href="https://digiboost.com" target="_blank">digiboost.com</a>
																		</p>
																	</td>
																</tr>
																<tr>
																	<td style="background-color: #FAFAFA;font-family: helvetica, arial, verdana, sans-serif; font-size: 14px;color:#505050;padding:5px 32px 20px 32px">
																		<p style="font-size:12px; text-align:center;">
																			115 E Travis St #412, San Antonio, Texas, 78205<br>
																			Tel: <span style="color:#0099ff"> 1 (210) 227-DIGI (3444)</span>,
																			Email: <a href="mailto:sales@digiboost.com">sales@digiboost.com</a>,
																			Website: <a href="https://digiboost.com" target="_blank">https://digiboost.com</a><br>
																		</p>
																		<p style="text-align:center; margin-bottom: 0px; margin-top:30px"><strong>*** This is an automatically generated email, please do not reply ***</strong></p>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
						</table>
					</body>
				</html>
				';
				
				
				$accBean = BeanFactory::getBean('Accounts', $bean->billing_account_id);	
				$emailTo = $accBean->email1;
				
				require_once('include/SugarPHPMailer.php'); 
							$emailObj = new Email(); 
							$defaults = $emailObj->getSystemDefaultEmail(); 
							$mail = new SugarPHPMailer(); 
							$mail->setMailerForSystem(); 
							$mail->From = 'do_not_reply@digiboost.com'; 
							$mail->FromName = 'Digiboost Staff'; 
							$mail->Subject = "Reminder Regarding To Contracts Dates " ;
							$mail->Body_html = from_html($html);
							$mail->Body = wordwrap($html,90000);
							$mail->isHTML(true); // set to true if content has html tags
							
							$mail->AddAddress($emailTo); 
							@$mail->Send();
				
				
				
			}
				
			
		
	}
}