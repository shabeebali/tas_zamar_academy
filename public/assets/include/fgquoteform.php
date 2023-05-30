<?PHP

/*

    Contact Form from HTML Form Guide



    This program is free software published under the

    terms of the GNU Lesser General Public License.



This program is distributed in the hope that it will

be useful - WITHOUT ANY WARRANTY; without even the

implied warranty of MERCHANTABILITY or FITNESS FOR A

PARTICULAR PURPOSE.



@copyright html-form-guide.com 2010

*/

require_once("class.phpmailer.php");



/*

Interface to Captcha handler

*/

class FG_CaptchaHandler

{

    function Validate() { return false;}

    function GetError(){ return '';}

}

/*

FGContactForm is a general purpose contact form class

It supports Captcha, HTML Emails, sending emails

conditionally, File atachments and more.

*/

class FGContactForm

{

    var $receipients;

    var $errors;

    var $error_message;

    var $name;

	var $phone;

    var $email;

    var $message;

    var $from_address;

    var $form_random_key;

    var $conditional_field;

    var $arr_conditional_receipients;

    var $fileupload_fields;

    var $captcha_handler;



    var $mailer;



    function FGContactForm()

    {

        $this->receipients = array();

        $this->errors = array();

        $this->form_random_key = 'HTgsjhartag';

        $this->conditional_field='';

        $this->arr_conditional_receipients=array();

        $this->fileupload_fields=array();



        $this->mailer = new PHPMailer();

        $this->mailer->CharSet = 'utf-8';

    }



    function EnableCaptcha($captcha_handler)

    {

        $this->captcha_handler = $captcha_handler;

        session_start();

    }



    function AddRecipient($email,$name="")

    {

        $this->mailer->AddAddress($email,$name);

    }

	function AddRecipientToSender($email,$name="")

    {

        $this->mailer->AddAddress($email,$name);

    }

    function SetFromAddress($from)

    {

        $this->from_address = $from;

    }

    function SetFormRandomKey($key)

    {

        $this->form_random_key = $key;

    }

    function GetSpamTrapInputName()

    {

        return 'sp'.md5('KHGdnbvsgst'.$this->GetKey());

    }

    function SafeDisplay($value_name)

    {

        if(empty($_POST[$value_name]))

        {

            return'';

        }

        return htmlentities($_POST[$value_name]);

    }

    function GetFormIDInputName()

    {

        $rand = md5('TygshRt'.$this->GetKey());



        $rand = substr($rand,0,20);

        return 'id'.$rand;

    }





    function GetFormIDInputValue()

    {

        return md5('jhgahTsajhg'.$this->GetKey());

    }



    function SetConditionalField($field)

    {

        $this->conditional_field = $field;

    }

    function AddConditionalReceipent($value,$email)

    {

        $this->arr_conditional_receipients[$value] =  $email;

    }



    function AddFileUploadField($file_field_name,$accepted_types,$max_size)

    {



        $this->fileupload_fields[] =

            array("name"=>$file_field_name,

            "file_types"=>$accepted_types,

            "maxsize"=>$max_size);

    }



    function ProcessForm()

    {

        if(!isset($_POST['submitted']))

        {

           return false;

        }

        if(!$this->Validate())

        {

            $this->error_message = implode('<br/>',$this->errors);

            return false;

        }

        $this->CollectData();



        $ret = $this->SendFormSubmission();



        return $ret;

    }

	

	function ProcessFormToSender()

    {

        if(!isset($_POST['submitted']))

        {

           return false;

        }

        if(!$this->Validate())

        {

            $this->error_message = implode('<br/>',$this->errors);

            return false;

        }

        $this->CollectData();



        $ret = $this->SendFormSubmissionToSender();



        return $ret;

    }



    function RedirectToURL($url)

    {

        header("Location: $url");

        exit;

    }



    function GetErrorMessage()

    {

        return $this->error_message;

    }

    function GetSelfScript()

    {

        return htmlentities($_SERVER['PHP_SELF']);

    }



    function GetName()

    {

        return $this->name;

    }

    function GetEmail()

    {

        return $this->email;

    }

    function GetMessage()

    {

        return htmlentities($this->message,ENT_QUOTES,"UTF-8");

    }



/*--------  Private (Internal) Functions -------- */





    function SendFormSubmission()

    {

        $this->CollectConditionalReceipients();



        $this->mailer->CharSet = 'utf-8';

        

        $this->mailer->Subject = "Zamar Music Academy Inquiry from $this->name";



        $this->mailer->From = $this->GetFromAddress();



        $this->mailer->FromName = $this->name;



        $this->mailer->AddReplyTo($this->email);



        $message = $this->ComposeFormtoEmail();

		



        $textMsg = trim(strip_tags(preg_replace('/<(head|title|style|script)[^>]*>.*?<\/\\1>/s','',$message)));

        $this->mailer->AltBody = @html_entity_decode($textMsg,ENT_QUOTES,"UTF-8");

        $this->mailer->MsgHTML($message);



        $this->AttachFiles();



        if(!$this->mailer->Send())

        {

            $this->add_error("Failed sending email!");

            return false;

        }



        return true;

    }

	

	function SendFormSubmissionToSender()

    {

        $this->CollectConditionalReceipientsToSender();



        $this->mailer->CharSet = 'utf-8';

        

        $this->mailer->Subject = "Zamar Music Academy from $this->name";



        $this->mailer->From = $this->GetFromAddress();



        $this->mailer->FromName = $this->name;



        $this->mailer->AddReplyTo($this->email);



        $message = $this->ComposeFormtoEmailToSender();



        $textMsg = trim(strip_tags(preg_replace('/<(head|title|style|script)[^>]*>.*?<\/\\1>/s','',$message)));

        $this->mailer->AltBody = @html_entity_decode($textMsg,ENT_QUOTES,"UTF-8");

        $this->mailer->MsgHTML($message);



        $this->AttachFiles();



        if(!$this->mailer->Send())

        {

            $this->add_error("Failed sending email!");

            return false;

        }



        return true;

    }



    function CollectConditionalReceipients()

    {

        if(count($this->arr_conditional_receipients)>0 &&

          !empty($this->conditional_field) &&

          !empty($_POST[$this->conditional_field]))

        {

            foreach($this->arr_conditional_receipients as $condn => $rec)

            {

                if(strcasecmp($condn,$_POST[$this->conditional_field])==0 &&

                !empty($rec))

                {

                    $this->AddRecipient($rec);

                }

            }

        }

    }

	

	function CollectConditionalReceipientsToSender()

    {

        if(count($this->arr_conditional_receipients)>0 &&

          !empty($this->conditional_field) &&

          !empty($_POST[$this->conditional_field]))

        {

            foreach($this->arr_conditional_receipients as $condn => $rec)

            {

                if(strcasecmp($condn,$_POST[$this->conditional_field])==0 &&

                !empty($rec))

                {

                    $this->AddRecipientToSender($rec);

                }

            }

        }

    }



    /*

    Internal variables, that you donot want to appear in the email

    Add those variables in this array.

    */

    function IsInternalVariable($varname)

    {

        $arr_interanl_vars = array('scaptcha',

                            'submitted',

                            $this->GetSpamTrapInputName(),

                            $this->GetFormIDInputName()

                            );

        if(in_array($varname,$arr_interanl_vars))

        {

            return true;

        }

        return false;

    }



    function FormSubmissionToMail()

    {

        $ret_str='';

        foreach($_POST as $key=>$value)

        {
			if(is_array($_POST[$key]))
			{
				$value = implode(", ",$_POST[$key]);
			}

			if($key != 'g-recaptcha-response' && $key != 'termsandcond')

			{	

				if(!$this->IsInternalVariable($key))

				{

					$value = htmlentities($value,ENT_QUOTES,"UTF-8");

					$value = nl2br($value);
					$key = str_replace("_"," ",$key);
					$key = ucfirst($key);

					//$ret_str .= "<div class='label' style='padding:1%; background:#e6e8e9; color:#000; width:95%; display:inline-block;'>$key :</div><div class='value' style='width:95%;  margin-bottom:2%;  display:inline-block;padding:1%; background:#fff; color:#000; '>$value </div><p style='clear:both; margin:0; padding:0;'></p>\n";
					$ret_str .= '<tr><td height="20" style="padding-left:10px;" width="40%"><b>'.$key.'</b></td><td width="5%">:</td><td width="65%">'.$value.'</td></tr>';
				}

			}

        }

        foreach($this->fileupload_fields as $upload_field)

        {

            $field_name = $upload_field["name"];

            if(!$this->IsFileUploaded($field_name))

            {

                continue;

            }        

            

            $filename = basename($_FILES[$field_name]['name']);



            $ret_str .= "<div class='label'>File upload '$field_name' :</div><div class='value'>$filename </div>\n";

        }

        return $ret_str;

    }







    function GetMailStyle()

    {

        $retstr = "\n<style>".

        "body,.label,.value { font-family:Arial,Verdana; } ".

        ".label {font-weight:bold; margin-top:5px; font-size:1em; color:#333;} ".

        ".value {margin-bottom:15px;font-size:1.0em;padding-left:5px;} ".

        "</style>\n";



        return $retstr;

    }

    function GetHTMLHeaderPart()

    {

         $retstr = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">'."\n".

                   '<html><head><title></title>'.

                   '<meta http-equiv=Content-Type content="text/html; charset=utf-8">';

         $retstr .= $this->GetMailStyle();

         $retstr .= '<table align="center" border="0" cellpadding="0" cellspacing="0" style="background-color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:12px; border:1px solid #006; color:#000;" width="650">

	<tbody>

		<tr>

			<td align="center" style="padding:10px;" valign="top">

			<table border="0" cellpadding="0" cellspacing="0" width="100%">

				<tbody>

					<tr>

						<td align="center"><img src="http://zamarmusicacademy.ca/pic/logo2.png" /></td>

						

						

					</tr>

				</tbody>

			</table>

			</td>

		</tr>';

         $retstr .= '</head><body>';

         return $retstr;

    }

    function GetHTMLFooterPart()

    {

        $retstr ='<tr>

			<td align="center" bgcolor="#16518f" height="50" style="font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#ffffff;font-weight:normal; " valign="middle">

			<table border="0" cellpadding="0" cellspacing="0" width="95%">

				<tbody>

					<tr>

						
						<td width="53%" style="color:#fff;">
						Phone: <a href="tel:9059158788" style="color:#fff;">905-915-8788</a><br>
						Email: <a href="mailto:info@zamarmusicacademy.ca" style="color:#fff;">  info@zamarmusicacademy.ca </a>
						</td>

						

						<td align="right" width="47%" style="color:#fff;">
						Address : 8920 Highway 50, Unit 4 <br>
								  Brampton, ON <br>
								  L7A 4R9 
						
						</td>

					</tr>

				</tbody>

			</table>

			</td>

		</tr>

	</tbody>

</table>



</body>

</html>';

        return $retstr ;

    }

    function ComposeFormtoEmail()

    {

        $header = $this->GetHTMLHeaderPart();

        $formsubmission = $this->FormSubmissionToMail();

        $footer = $this->GetHTMLFooterPart();



        //$message = $header."<div style='background:#fff; padding:10px; text-align:left;'><center><img src=\"http://seowithus.com/webmaster1/EB-service/10-march-2017/images/logo.png\"></center><p>$formsubmission</p></div>".$footer;

        $message = $header;

		$message .='<tr>

			<td style="padding:10px 20px; " valign="top">

			<table border="0" cellpadding="0" cellspacing="0" width="100%">

				<tbody>

					<tr>

						<td height="20" style="background-color:#ECECEC;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#000;padding:7px 0 6px 13px;text-transform:uppercase; border:1px solid #ECECEC; border-bottom:0px;">Detail:</td>

					</tr>

					<tr>

						<td bgcolor="#fff" style="padding:10px 10px; border:1px solid #ECECEC;">

						<table border="0" cellpadding="0" cellspacing="0" width="100%">

							<tbody>

								<tr>

									<td valign="top" width="80%">

									<table cellpadding="0" cellspacing="0" width="100%">

										<tbody>';

		$message .= $formsubmission;

		$message .='</tbody>

									</table>

									</td>

								</tr>

							</tbody>

						</table>

						</td>

					</tr>

					<tr>

						<td>&nbsp;</td>

					</tr>

					<tr>

						<td bgcolor="#FFFFFF" height="5">&nbsp;</td>

					</tr>

					

				</tbody>

			</table>

			</td>

		</tr>';

		$message .= $footer;

        return $message;

    }

	

	function ComposeFormtoEmailToSender()

    {

        $header = $this->GetHTMLHeaderPart();

        $formsubmission = $this->FormSubmissionToMail();

        $footer = $this->GetHTMLFooterPart();



        //$message = $header."<div style='background:#fff; color:#000; font-size:18px; padding:10px; text-align:left;'><center><img src=\"http://seowithus.com/webmaster1/EB-service/10-march-2017/images/logo.png\"></center><p>$formsubmission</p><p><h1><center>Thank you for contacting us.<br /><br /></center></h1><center>We have received your Form Submission our Staff will be contacting you within 24 hours.<br /><br /><strong>Have a great day ahead!</strong></center></p></div>".$footer;

		$message = $header;

		$message .='<tr>

			<td style="padding:10px 20px; " valign="top">

			<table border="0" cellpadding="0" cellspacing="0" width="100%">

				<tbody>

					<tr>

						<td height="20" style="background-color:#ECECEC;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#000;padding:7px 0 6px 13px;text-transform:uppercase; border:1px solid #ECECEC; border-bottom:0px;">Detail:</td>

					</tr>

					<tr>

						<td bgcolor="#fff" style="padding:10px 10px; border:1px solid #ECECEC;">

						<table border="0" cellpadding="0" cellspacing="0" width="100%">

							<tbody>

								<tr>

									<td valign="top" width="80%">

									<table cellpadding="0" cellspacing="0" width="100%">

										<tbody>';

		$message .= $formsubmission;

		$message .= '</tbody></table><tr><td style="width:100%"><h1>Thank you for contacting us.</h1><br /><br />We have received your Form Submission our Staff will be contacting you within 24 hours.<br /><br /><strong>Have a great day ahead!</strong></tr></td>';

		

		$message .='</tbody>

									</table>

									</td>

								</tr>

							</tbody>

						</table>

						</td>

					</tr>

					<tr>

						<td>&nbsp;</td>

					</tr>

					<tr>

						<td bgcolor="#FFFFFF" height="5">&nbsp;</td>

					</tr>

					

				 

			</td>

		</tr>';

		$message .= $footer;

		

        return $message;

    }



    function AttachFiles()

    {

        foreach($this->fileupload_fields as $upld_field)

        {

            $field_name = $upld_field["name"];

            if(!$this->IsFileUploaded($field_name))

            {

                continue;

            }

            

            $filename =basename($_FILES[$field_name]['name']);



            $this->mailer->AddAttachment($_FILES[$field_name]["tmp_name"],$filename);

        }

    }



    function GetFromAddress()

    {

        if(!empty($this->from_address))

        {

            return $this->from_address;

        }



        $host = $_SERVER['SERVER_NAME'];



        $from ="@$host";

        return $from;

    }



    function Validate()

    {

        $ret = true;

        //security validations

        //if(empty($_POST[$this->GetFormIDInputName()]) ||

          //$_POST[$this->GetFormIDInputName()] != $this->GetFormIDInputValue() )

        //{

            //The proper error is not given intentionally

            //$this->add_error("Automated submission prevention: case 1 failed");

            //$ret = false;

        //}



        //This is a hidden input field. Humans won't fill this field.

        if(!empty($_POST[$this->GetSpamTrapInputName()]) )

        {

            //The proper error is not given intentionally

            $this->add_error("Automated submission prevention: case 2 failed");

            $ret = false;

        }



        //name validations

        if(empty($_POST['name']))

        {

            $this->add_error("Please provide your name");

            $ret = false;

        }

        else

        if(strlen($_POST['name'])>50)

        {

            $this->add_error("Name is too big!");

            $ret = false;

        }

		

		//phone validations

        if(empty($_POST['phone']))

        {

            $this->add_error("Please provide your phone no.");

            $ret = false;

        }

        else

        if(strlen($_POST['phone'])>15)

        {

            $this->add_error("Digit Exceeds");

            $ret = false;

        }

        else

        if(!$this->validate_phone($_POST['phone']))

        {

            $this->add_error("Please provide a valid phone no.");

            $ret = false;

        }



        //email validations

        if(empty($_POST['email']))

        {

            $this->add_error("Please provide your email address");

            $ret = false;

        }

        else

        if(strlen($_POST['email'])>50)

        {

            $this->add_error("Email address is too big!");

            $ret = false;

        }

        else

        if(!$this->validate_email($_POST['email']))

        {

            $this->add_error("Please provide a valid email address");

            $ret = false;

        }



        //message validaions

        if(strlen($_POST['message'])>2048)

        {

            $this->add_error("Message is too big!");

            $ret = false;

        }



        //captcha validaions

        if(isset($this->captcha_handler))

        {

            if(!$this->captcha_handler->Validate())

            {

                $this->add_error($this->captcha_handler->GetError());

                $ret = false;

            }

        }

		 

		

		//Google recaptcha validations

		if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))

		{

			//your site secret key

			$secret = '6Ldeaw0UAAAAAEy3oP4uYsQNVYfAFg3Lb_aPkmPC';

			//get verify response data

			$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);

			$responseData = json_decode($verifyResponse);

			if(!isset($responseData->success))

			{

				$this->add_error("Robot verification failed, please try again.");

				$ret = false;

			}

		}

	    else

		{

			$this->add_error("Please confirm you are not a robot.");

            $ret = false;

        }

		

		//Terms and condition check

		

        return $ret;

    }



    function ValidateFileType($field_name,$valid_filetypes)

    {

        $ret=true;

        $info = pathinfo($_FILES[$field_name]['name']);

        $extn = $info['extension'];

        $extn = strtolower($extn);



        $arr_valid_filetypes= explode(',',$valid_filetypes);

        if(!in_array($extn,$arr_valid_filetypes))

        {

            $this->add_error("Valid file types are: $valid_filetypes");

            $ret=false;

        }

        return $ret;

    }



    function ValidateFileSize($field_name,$max_size)

    {

        $size_of_uploaded_file =

                $_FILES[$field_name]["size"]/1024;//size in KBs

        if($size_of_uploaded_file > $max_size)

        {

            $this->add_error("The file is too big. File size should be less than $max_size KB");

            return false;

        }

        return true;

    }



    function IsFileUploaded($field_name)

    {

        if(empty($_FILES[$field_name]['name']))

        {

            return false;

        }

        if(!is_uploaded_file($_FILES[$field_name]['tmp_name']))

        {

            return false;

        }

        return true;

    }

    function ValidateFileUploads()

    {

        $ret=true;

        foreach($this->fileupload_fields as $upld_field)

        {

            $field_name = $upld_field["name"];



            $valid_filetypes = $upld_field["file_types"];

            

            if(!$this->IsFileUploaded($field_name))

            {

                continue;

            }



            if($_FILES[$field_name]["error"] != 0)

            {

                $this->add_error("Error in file upload; Error code:".$_FILES[$field_name]["error"]);

                $ret=false;

            }



            if(!empty($valid_filetypes) &&

             !$this->ValidateFileType($field_name,$valid_filetypes))

            {

                $ret=false;

            }



            if(!empty($upld_field["maxsize"]) &&

            $upld_field["maxsize"]>0)

            {

                if(!$this->ValidateFileSize($field_name,$upld_field["maxsize"]))

                {

                    $ret=false;

                }

            }



        }

        return $ret;

    }



    function StripSlashes($str)

    {

        if(get_magic_quotes_gpc())

        {

            $str = stripslashes($str);

        }

        return $str;

    }

    /*

    Sanitize() function removes any potential threat from the

    data submitted. Prevents email injections or any other hacker attempts.

    if $remove_nl is true, newline chracters are removed from the input.

    */

    function Sanitize($str,$remove_nl=true)

    {

        $str = $this->StripSlashes($str);



        if($remove_nl)

        {

            $injections = array('/(\n+)/i',

                '/(\r+)/i',

                '/(\t+)/i',

                '/(%0A+)/i',

                '/(%0D+)/i',

                '/(%08+)/i',

                '/(%09+)/i'

                );

            $str = preg_replace($injections,'',$str);

        }



        return $str;

    }



    /*Collects clean data from the $_POST array and keeps in internal variables.*/

    function CollectData()

    {

        $this->name = $this->Sanitize($_POST['name']);

        $this->email = $this->Sanitize($_POST['email']);



        /*newline is OK in the message.*/

        $this->message = $this->StripSlashes($_POST['message']);

    }



    function add_error($error)

    {

        array_push($this->errors,$error);

    }

    function validate_email($email)
    {
        return preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/", trim($email));
    }

	function validate_phone($phone)
    {
		//return preg_match("/^[-.0-9]$/", trim($phone));
		if(preg_match("/^[1-9][0-9]{0,15}$/", trim($phone)))
		{
		    return true;
		}
		else
		{
		    return false;
		}
    }



    function GetKey()

    {

        return $this->form_random_key.$_SERVER['SERVER_NAME'].$_SERVER['REMOTE_ADDR'];

    }



}



?>