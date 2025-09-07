<?php
$html = "
<style>
body {
    font-family: 'timesnewroman';
    font-size: 15pt;
    line-height: 1.8;
    padding: 10px;
}
.hindi {
    font-family: 'devlys010';
    font-size: 17pt;
}
.logo {
    position: absolute;
    top: 20px;
    left: 30px;
}
.header {
    text-align: center;
    margin-bottom: 0px;
    line-height: 0.5;
}
.section {
    font-size: 17pt;
    
    font-style: italic;
    margin-top: 0px;
    text-align: center;
}
.footer {
    text-align: center;
    margin-top: 80px;
}
.qr {
    display: block;
    margin: 10px auto;
    width: 100px;
}

  .row {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
  }

  .column {
    flex: 1;
    min-width: 250px; /* Adjust as needed */
    padding: 10px;
    box-sizing: border-box;
  }

  
  .text-center {
    text-align: center;
  }

  .text-right {
    text-align: right;
  }
</style>

<div class='header'>
    <h2 style='font-size: 38pt;'>North-Eastern Hill University</h2>
    <div class='hindi' style='font-size: 38pt;'>iwoksZŸkj ioZrh; fo'ofo|ky;</div>
    <img src='$logoPath' width='140px' class='logo'>   
    <h3><u>CERTIFICATE / <span class='hindi'>izek.ki=</span></u></h3>

    </div>



<div class='section'>
<h2>UG Degree Certificate</h2>
    <p>This is to certify that <br><b>$name</b><br> bearing Roll No. <b>$roll</b>, Registration No. <b>$regno</b><br>
     has been examined and found qualified for the Degree of <br><b>$degree</b><br> of this University in the year <b>$gradyear<b><br>
      with CGPA <b>$cgpa</b> and was placed in Grade <b>$grade</b>.</p>
</div>

<div class='section hindi'>
    izekf.kr fd;k tkrk gS fd <br><b>$name</b><br> vuqØekad <b>$roll</b> iathdj.k la[;k <b>$regno</b> <br>
    dks ijh{k.kksijkUr bl fo’ofo|ky; ds o'kZ <b>$gradyear</b> e <br> <b>($degree)</b> <br> dh mikf/k ds fy, ;ksX; ik;k x;k<br>
    rFkk mUgsa <b>$cgpa</b> lh-th-ih-,- ds lkFk xzsM <b>$grade</b> iznku fd;k x;kA
</div>

<div class='footer'>

<div style='position: absolute; bottom: 0px; width: 100%;'>
    <p style='text-align: center; font-style: italic; margin-bottom: 0px;'>
        Given under the seal of the University.<br>
        <span class='hindi'>fo“ofo|ky; dh eqgj ds varxZr iznÙk</span>
    </p>

    <table style='width: 100%; border-collapse: collapse; margin-top: 0px;'>
        <tr>
            <td style='width: 33%; text-align: left; vertical-align: bottom;'>
                Shillong / <span class='hindi'>f'kykax</span><br>
                Date / <span class='hindi'>fnukad:</span><br>
                Serial No. / $serial_no
            </td>
            <td style='width: 34%; text-align: center; vertical-align: top;'>
                <div class='qr'>$qrHTML
                <p><b>Scan to verify</b></p>
                </div>
                
            </td>
            <td style='width: 33%; text-align: right; vertical-align: bottom;'>
                <div style='margin-top: 20px;'>
                    Vice–Chancellor <br> <span class='hindi'>dqyifr</span>
                </div>
            </td>
        </tr>
    </table>
</div>

</div>
";

?>