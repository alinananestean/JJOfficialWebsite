<?php

  $m_to    = "oba@agsconsulting.org";
  $m_from  = str_replace(","," ",$_REQUEST['input-name']);
  $m_from  = str_replace(";","",$m_from);
  $m_back  = "http://www.jordanjansen.com.au/index_test.html";
  $m_error = array();

  if (count($_COOKIE))
    {
    foreach(array_keys($_COOKIE) as $value)
      {
      unset($_REQUEST[$value]);
      }
    }

  if (isset($_REQUEST['input-email']))
    {
    $_REQUEST['input-email'] = trim($_REQUEST['input-email']);
    if ( substr_count($_REQUEST['input-email'],"@") != 1 || stristr($_REQUEST['input-email']," ") || stristr($_REQUEST['input-email'],"\\") || stristr($_REQUEST['input-email'],":"))
      {
      $m_error[] = "Email address is invalid.";
      }
    else
      {
      $exploded_email = explode("@",$_REQUEST['input-email']);
      if (empty($exploded_email[0]) || strlen($exploded_email[0]) > 64 || empty($exploded_email[1]))
        {
        $m_error[] = "Email address is invalid.";
        }
      else
        {
        if (substr_count($exploded_email[1],".") == 0)
          {
          $m_error[] = "Email address is invalid.";
          }
        else
          {
          $exploded_domain = explode(".",$exploded_email[1]);
          if (in_array("",$exploded_domain))
            {
            $m_error[] = "Email address is invalid.";
            }
          else
            {
            foreach($exploded_domain as $value)
              {
              if(strlen($value) > 63 || !preg_match('/^[a-z0-9-]+$/i',$value))
                {
                $m_error[] = "Email address is invalid.";
                break;
                }
              }
            }
          }
        }
      }
    }

  if (!(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) && stristr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])))
    {
    $m_error[] = "Hosting Service does not allow use of this form.";
    }

  function blank_check($mpassed)
    {
    global $mflag;
    if (!is_array($mpassed))
      {
      if (!empty($mpassed))
        {
        $mflag = 1;
        }
      }
    else
      {
      foreach($mpassed as $value)
        {
        if ($mflag)
          {
          break;
          }
        blank_check($value);
        }
      }
    }

  blank_check($_REQUEST);

  if (!$mflag)
    {
    $m_error[] = "Form is blank.";
    }

  unset($mflag);

  if (count($m_error))
    {
    foreach($m_error as $value)
      {
      print "$value<br>";
      }
      exit;
    }

  function make_mail($m_make)
    {
    if (!isset($m_out))
      {
      $m_out = "";
      }
    if (!is_array($m_make))
      {
      $m_out = $m_make;
      }
    else
      {
      $m_out = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body>';
      foreach($m_make as $mkey => $value)
        {
        if (!empty($value))
          {
          if (!is_numeric($mkey))
            {
            $m_out .= "<p>" . str_replace("_"," ",ucfirst($mkey)) . ": " . make_mail($value) . "</p>";
            }
          else
            {
            $m_out .= "<p>" . make_mail($value) . "</p>";
            }
          }
        }
      $m_out .= '</body></html>';
      }
      return rtrim($m_out,", ");
    }

  $m_body = make_mail($_REQUEST);
  $m_body = stripslashes($m_body);

  $m_subj = $_REQUEST['input-subject'];
  $m_subj = stripslashes($m_subj);
  $m_subj = "=?UTF-8?B?".base64_encode($m_subj)."?=";

  $m_head  = "From: $m_from <$m_from>\r\n" .
             "MIME-Version: 1.0\r\n" .
             "Content-type: text/html; charset=UTF-8\r\n";
  $m_head .= "Reply-To: " . $_REQUEST['input-email'];

  mail($m_to,$m_subj,$m_body,$m_head);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//RO">

<html>

<head>
  <title>JordanJansen.com</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body>

  <div>
  <p style="text-align:center">
  <strong>Thank you! <?php if(isset($_REQUEST['input-name'])){ print stripslashes($_REQUEST['input-name']); } ?> </strong><br />
  <a href="<?php print $m_back; ?>">Click here</a>
  </p>

  </div>

</body>

</html>
