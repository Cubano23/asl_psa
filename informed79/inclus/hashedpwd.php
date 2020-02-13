<?php



    function clean($str, $op) {
                  if ($op==1)
                          $str = @trim($str);
                  $str = stripslashes($str);
                  return mysql_real_escape_string($str);
    }


    function pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output = false)
    {
    $algorithm = strtolower($algorithm);
    if(!in_array($algorithm, hash_algos(), true))
        die('PBKDF2 ERROR: Invalid hash algorithm.');
    if($count <= 0 || $key_length <= 0)
        die('PBKDF2 ERROR: Invalid parameters.');

    $hash_length = strlen(hash($algorithm, "", true));
    $block_count = ceil($key_length / $hash_length);

    $output = "";
    for($i = 1; $i <= $block_count; $i++) {
        // $i encoded as 4 bytes, big endian.
        $last = $salt . pack("N", $i);
        // first iteration
        $last = $xorsum = hash_hmac($algorithm, $last, $password, true);
        // perform the other $count - 1 iterations
        for ($j = 1; $j < $count; $j++) {
            $xorsum ^= ($last = hash_hmac($algorithm, $last, $password, true));
        }
        $output .= $xorsum;
    }

    if($raw_output)
        return substr($output, 0, $key_length);
    else
        return bin2hex(substr($output, 0, $key_length));
}

    function getRandomString($length = 6) 
    {
        $validCharacters = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ0123456789";
        $validCharNumber = strlen($validCharacters);
        $result = "";
        for ($i = 0; $i < $length; $i++) 
        {
              $index = mt_rand(0, $validCharNumber - 1);
              $result .= $validCharacters[$index];
        }
        return $result;
    }


    function generateHashedPassword( $password, &$salt, & $count  )
    {
      $lcount = mt_rand ( 1 , 32 );
      $lsalt = getRandomString(32); 
      $hashed =pbkdf2("sha256", $password, $lsalt, $lcount, 32, false); 
      $salt = $lsalt;
      $count = $lcount;
      return $hashed;
    }

    function getHashedPassword( $password, $salt, $count  )
    {
      $hashed =pbkdf2("sha256", $password, $salt, $count, 32, false); 
      return $hashed;
    }
    
    function checkHashedPassword($con, $username, $pass, $appli, &$lasterror)
    {
      $err = true;
      $phase = 0;
      $lasterror = "OK";

      while( ( $phase<5 ) && ($err==true))
      {
          switch($phase)
          {
              case 0: break;
              case 1:
                break;
            case 2:
                $userid = clean($username, 1);
                $sql ="SELECT  hpassword, hpassword2, hpassword3, salt,count FROM hpasswords, habilitations WHERE  hpasswords.login='$userid'  and hpasswords.login=habilitations.login and habilitations.".$appli."=1"; 
                $result = mysql_query( $sql ,$con);
                if(mysql_num_rows($result) != 1) 
                {
                      $lasterror = "Bad login/password";
                      $err=  false;
                }
                break;
            case 3:                
                  list($hpassword, $hpassword2, $hpassword3, $salt, $count  ) = mysql_fetch_row($result); 
                  $hashed =  getHashedPassword( $pass, $salt, $count );
                  
                  if( ( $hashed != $hpassword) && ( $hashed != $hpassword2) && ( $hashed != $hpassword3))
                  {
                    $lasterror = "Bad login/Hashed password " . " ($pass) ". $hashed. " ". $hpassword. " ". $salt. " ". $count;
                    $err =  false;
                  }
                  break;
            default: break;                
          }
          $phase++;
      }
      
      return $err;
    }



      
?>