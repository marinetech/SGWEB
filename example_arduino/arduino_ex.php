<?php
$print = array(" ","SCL","3V3","SDA","RST","AREF","3V3","GND","5V","13","GND","12","GND","11","Vin","10",0,"9","A0","8","A1",0,"A2","7","A3","6","A4","5","A5","4",0,"3",0,"2",0,"Tx",0,"Rx");
$controllable = array("13","12","11","10","9","8","7","6","5","4","3","2","A5","A4","A3","A2","A1","A0");
$pwm = array("3","4","5","6","7","8","9","10","11");
$input = array("A0","A1","A2","A3","A4","A5");
require 'SGWEB/arduino.php'
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Arduino example</title>

    <link rel="stylesheet" href="gpio.css" media="screen" title="no title" charset="utf-8">

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins and SGGWEB) -->
    <script src="js/jquery.js"></script>
    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <script src="js/bootstrap.js"></script>
    <!-- SGWEB JS -->
    <script src="SGWEB/arduino.js"></script>

    <script type="text/javascript">
    function setPinModeOut(pin){
      var mode = "m" + pin;
      var set1 = "h" + pin;
      var set0 = "l" + pin;
      var read = "r" + pin;
      var value = "v" + pin;
      var pwm = "p" + pin;
      SendCommand(function(ret){
      console.log(ret);
        $("#" + value).text("");
        $("#" + mode).text("OUT");
        $("#" + set1).removeAttr('disabled');
        $("#" + set0).removeAttr('disabled');
        $("#" + read).attr('disabled','disabled');
        $("#" + pwm).attr('disabled','disabled');
      },"o",pin,"o");
    };

    function setPinModeIn(pin){
      var mode = "m" + pin;
      var set1 = "h" + pin;
      var set0 = "l" + pin;
      var read = "r" + pin;
      SendCommand(function(ret){
      console.log(ret);
      $("#" + mode).text("IN");
      $("#" + set1).attr('disabled','disabled');
      $("#" + set0).attr('disabled','disabled');
      $("#" + read).removeAttr('disabled');
      },"i",pin,"i");
    };

    function set1(pin){
      var string = "v" + pin;
      console.log(string);
      SendCommand(function(ret){
      console.log(ret);
      },"o",pin,"1",0);
    };

    function set0(pin){
      var string = "v" + pin;
      console.log(string);
      SendCommand(function(ret){
      console.log(ret);
      },"o",pin,"0");
    };

    function setPWM(pin){
      var mode = "m" + pin;
      var set1 = "h" + pin;
      var set0 = "l" + pin;
      var read = "r" + pin;
      var value = "v" + pin;
      var pwm = $('#pwm' + pin).val();
      if ( $('#pwm' + pin).val() < 0 || $('#pwm' + pin).val() > 255){
        alert("PWM value must be 0 to 255.");
      }
      else{
        SendCommand(function(ret){
          console.log(ret);
          $("#" + value).text("");
          $("#" + mode).text("PWM");
          $("#" + read).attr('disabled','disabled');
          $("#" + set1).attr('disabled','disabled');
          $("#" + set0).attr('disabled','disabled');
        },"p",pin,pwm);
      }
    };

    function readValue(pin,mode){
      var string = "v" + pin;
      console.log(pin);
      console.log(string);
      SendCommand(function(ret){
      console.log(ret);
        $("#" + string).text(ret);
      },"i",pin,mode);
    };

    </script>
    </head>

    <body>
      <div class="container-fluid">
        <div class="row">
          <div class="col-xs-12">
            <h1 class="text-center">Arduino Pinout control interface</h1>
          </div>
        </div>
          <div class="row spacer"></div>
          <?php
            $i=0;
            foreach ($print as $key => $pin) {
              //var_dump($pin);
              if ($i%2==0) {
                echo '<div class="row">';
              }
              if ($pin!=NULL&&$i%2==0) {
                echo '<div class="col-xs-6">';
                if(in_array($pin,$controllable)){
                  if(in_array($pin, $input)==FALSE){
                    echo '<button type="button" onClick="setPinModeOut('.$pin.')" id="out'.$pin.'" class="btn btn-default btn-sm">Set to OUT</button>
                      <button type="button" onClick="setPinModeIn('.$pin.')" id="in'.$pin.'" class="btn btn-default btn-sm">Set IN</button>
                      <button type="button" onClick="set1('.$pin.')" id="h'.$pin.'" disabled="disabled" class="btn btn-default btn-sm">Set 1</button>
                      <button type="button" onClick="set0('.$pin.')" id="l'.$pin.'" disabled="disabled" class="btn btn-default btn-sm">Set 0</button>
                      <button type="button" onClick="readValue('.$pin.',\'d\') id="r'.$pin.'" disabled="disabled" class="btn btn-default btn-sm">Read Value</button>';
                  }
                  if(in_array($pin, $input)==TRUE){
                    echo '<button type="button" onClick="readValue(\''.$pin.'\',\'a\')" id="r'.$pin.'" class="btn btn-default btn-sm">Read Value</button>';
                  }
                }
                if(in_array($pin, $pwm)){
                  echo '<button type="button" onClick="setPWM('.$pin.',p'.$pin.'.value())" id="p'.$pin.'" class="btn btn-default btn-sm">Set PWM</button>&nbsp;<input id="pwm'.$pin.'" size="3" maxlength="3" type="text">';
                }
                echo '
                  <div class="gpio pull-right">
                      <p>
                      <span id="'.$pin.'">'.$pin.'</span><br />
                      <span id="m'.$pin.'"></span><br />
                      <span id="v'.$pin.'"><br /></span>
                      </p>
                  </div>
                </div>';
              }
              else if($pin!=NULL&&$i%2!=0){
                echo '<div class="col-xs-6">
                  <div class="gpio">
                      <p>
                      <span id="'.$pin.'">'.$pin.'</span><br />
                      <span id="m'.$pin.'"></span><br />
                      <span id="v'.$pin.'"><br /></span>
                      </p>
                  </div>
                  <div class="pull-right" style="margin-top:-70px;">';

                  if(in_array($pin,$controllable)){
                    if(in_array($pin, $input)==FALSE){
                      echo '<button type="button" onClick="setPinModeOut('.$pin.')" id="out'.$pin.'" class="btn btn-default btn-sm">Set to OUT</button>
                        <button type="button" onClick="setPinModeIn('.$pin.')" id="in'.$pin.'" class="btn btn-default btn-sm">Set IN</button>
                        <button type="button" onClick="set1('.$pin.')" id="h'.$pin.'" disabled="disabled" class="btn btn-default btn-sm">Set 1</button>
                        <button type="button" onClick="set0('.$pin.')" id="l'.$pin.'" disabled="disabled" class="btn btn-default btn-sm">Set 0</button>
                        <button type="button" onClick="readValue('.$pin.',\'d\')" id="r'.$pin.'" disabled="disabled" class="btn btn-default btn-sm">Read Value</button>';
                    }
                    if(in_array($pin, $input)==TRUE){
                      echo '<button type="button" onClick="readValue(\''.$pin.'\',\'a\')" id="r'.$pin.'" class="btn btn-default btn-sm">Read Value</button>';
                    }
                  }
                  if(in_array($pin, $pwm)){
                    echo '<button type="button" onClick="setPWM('.$pin.')" id="p'.$pin.'" class="btn btn-default btn-sm">Set PWM</button>&nbsp;<input id="pwm'.$pin.'" size="3" maxlength="3" type="text">';
                  }
                  echo'
                </div>
                </div>

                ';
                }
              else{
                echo '<div class="col-xs-6"></div>';
              }
              if ($i%2!=0) {
                echo '</div><div class="spacers"></div>';
              }
              $i++;
            }
           ?>
          <div class="spacer"></div>
        </div>
    </body>
</html>
