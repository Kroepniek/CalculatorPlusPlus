<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"> 
    <meta name="description" content="Rekenmachine" />
    <title>Calculator++</title>
    <link rel="Stylesheet" type="text/css" href="style.css"/>

<?php

    session_start();

    $Op; //Operator
    $GetalA; //Eerste getal
    $GetalB; //Tweede getal
    $answer = 0; //Antwoord, uitkomst
    $His = NULL; //Geschiedenis van berekeningen
    

    //Als knop 'Reken' is ingedrukt...
    if (isset($_POST['Reken'])) {
        $Op = $_POST['Operator'];
        $GetalA = $_POST['NumberA'];
        if($Op != "Square")
        {
            $GetalB = $_POST['NumberB'];
        }
        else $GetalB = 0;

        //Als beide getalen en operator bestaan voer een functie uit
        if ($Op == "Square" && !empty($GetalA) && is_numeric($GetalA))
        {
            $answer = Rek($GetalA, $GetalB, $Op);
            $His = Store($GetalA, $GetalB, $Op, $answer, $_POST['History']);
        }
        else if ($Op == "ConversionToMi" || $Op == "ConversionToKm")
        {
            if (!empty($GetalA) && is_numeric($GetalA))
            {
                $answer = Convert($GetalA, $Op);
                if($answer == "error")
                {
                    $answer = 0;
                    unset($GetalA);
                    unset($GetalB);
                    unset($Op);
                } else
                {
                    $His = Store($GetalA, $GetalB, $Op, $answer, $_POST['History']);
                }
            }
        }
        else if ($Op != "Select" && $Op != "Square" && is_numeric($GetalB) && is_numeric($GetalA))
        {
            if (!empty($GetalA) && !is_null($GetalA) || $GetalA == 0)
            {
                if (!empty($GetalB) && !is_null($GetalB) || $GetalB == 0)
                {
                    $answer = Rek($GetalA, $GetalB, $Op);
                    if($answer == "error")
                    {
                        $answer = 0;
                        unset($GetalA);
                        unset($GetalB);
                        unset($Op);
                    } else
                    {
                        $His = Store($GetalA, $GetalB, $Op, $answer, $_POST['History']);
                    }
                }
            }
        }
    }
    else 
    {
        unset($GetalA);
        unset($GetalB);
    }

    //Als knop 'Ans' is ingedrukt...
    if (isset($_POST['AnsButton'])) {
        $Op = $_POST['Operator'];
        $GetalA = $_POST['NumberA'];
        $GetalB = $_POST['NumberB'];

        if (is_numeric($GetalA))
        {
            if ($Op == "Square" && !empty($GetalA))
            {
                $GetalA = Rek($GetalA, $GetalB, $Op);
            }
            else if ($Op == "ConversionToMi" || $Op == "ConversionToKm")
            {
                if (!empty($GetalA))
                {
                    $GetalA = substr(Convert($GetalA, $Op), 0, -3);
                }
            }
            else if ($Op != "Select" && $Op != "Square" && is_numeric($GetalB))
            {
                if (!empty($GetalA) && !is_null($GetalA) || $GetalA == 0)
                {
                    if (!empty($GetalB) && !is_null($GetalB) || $GetalB == 0)
                    {
                        $GetalA = Rek($GetalA, $GetalB, $Op);
                    }
                }
            }
        }

        unset($GetalB);
        unset($Op);
    }

    //Als knop 'Reset' is ingedrukt...
    if (isset($_POST['Reset'])) {
        Res();
    }

    //Functie om te berekenen. ($gA is eerste getal, $gB is tweede getal, $op is operator)
    function Rek($gA, $gB, $op)
    {
        switch($op)
        {
            case "Add":                         //Als operator '+' is
                $answer = $gA + $gB;
                break;
            case "Sub":                         //Als operator '-' is
                $answer = $gA - $gB;
                break;
            case "Multi":                       //Als operator '*' is
                $answer = $gA * $gB;
                break;
            case "Div":                         //Als operator '/' is
                if($gB == 0)                    //Als deler '0' is
                {
                    $answer = "error";
                    break;
                }
                $answer = $gA / $gB;
                break;
            case "Power":                       //Als operator 'Power' is
                $answer = pow($gA, $gB);
                break;
            case "Square":                      //Als operator 'Square root' is
                $answer = sqrt($gA);
                break;
        }
        $answer = $answer;
        return $answer;
    }

    //Functie die berekent mi naar km en km naar mi
    function Convert($gA, $Op)
    {
        $ans;
        if ($Op == "ConversionToMi")
        {
            $ans = $gA * 0.62137;
            $ans = $ans." mi";
        }
        else if ($Op == "ConversionToKm")
        {
            $ans = $gA * 1.60934;
            $ans = $ans." km";
        }
        return $ans;
    }

    //Functie om berekeningen toevoegen aan geschiedenis
    function Store($gA, $gB, $op, $ans, $his)
    {
        $changed;
        
        switch($op)
        {
            case "Add":
                $changed = $his."# ".$gA." + ".$gB." = ".$ans."&#13;&#10;";
                break;
            case "Sub":
                $changed = $his."# ".$gA." - ".$gB." = ".$ans."&#13;&#10;";
                break;
            case "Multi":
                $changed = $his."# ".$gA." * ".$gB." = ".$ans."&#13;&#10;";
                break;
            case "Div":
                $changed = $his."# ".$gA." / ".$gB." = ".$ans."&#13;&#10;";
                break;
            case "Power":
                $changed = $his."# ".$gA."&sup".$gB." = ".$ans."&#13;&#10;";
                break;
            case "Square":
                $changed = $his."# √".$gA." = ".$ans."&#13;&#10;";
                break;
            case "ConversionToMi":
                $changed = $his."# ".$gA." = ".$ans."&#13;&#10;";
                break;
            case "ConversionToKm":
                $changed = $his."# ".$gA." = ".$ans."&#13;&#10;";
                break;
        }
        return $changed;
    }

    //Functie om te reseten
    function Res()
    {
        unset($Op);
        unset($GetalA);
        unset($GetalB);
        $answer = 0;
    }
?>

<script type="text/javascript">
    function SquareCheck()
    {
        if(document.getElementById('Operator').value == "Square")
        {
            document.getElementById('formElement').value = "";
            document.getElementById('formElement').disabled = true;
        } 
        else
        {
            document.getElementById('formElement').disabled = false;
        }

        WeDontDoThatHere();
    }

    function sleep(milliseconds) 
    {
        var start = new Date().getTime();
        for (var i = 0; i < 1e7; i++) 
        {
            if ((new Date().getTime() - start) > milliseconds)
            {
                break;
            }
        }
    }

    function WeDontDoThatHere()
    {
        if(document.getElementById('Operator').value == "Div" && document.getElementById('formElementB').value == "0")
        {
            document.getElementsByClassName('Error-message')[0].innerHTML = "Deler mag niet 0 zijn bij delen.";
            document.getElementById('Error').style.left = "100px";
            document.getElementById('formElementB').style.backgroundColor = "#ffb3b3";
            document.getElementById('formElementB').value = "";
            setTimeout(function()
            { 
                document.getElementById('Error').style.left = "-1000px";
                document.getElementById('formElementB').style.backgroundColor = "#ffffff";
            }, 4000);
        }
        else if(document.getElementById('Operator').value == "Conversion")
        {
            document.getElementById('formElementA').disabled = false;
            document.getElementById('formElementB').disabled = false;
            if(document.getElementById('formElementA').value != "")
            {
                document.getElementById('formElementB').value = "0";
                document.getElementById('formElementB').disabled = true;
            }
            else if(document.getElementById('formElementB').value != "")
            {
                document.getElementById('formElementA').value = "0";
                document.getElementById('formElementA').disabled = true;
            }
            else
            {
                document.getElementById('formElementA').value = "";
                document.getElementById('formElementB').value = "";
                document.getElementById('formElementA').disabled = false;
                document.getElementById('formElementB').disabled = false;
            }
        }
        else
        {
            document.getElementById('formElementA').disabled = false;
            document.getElementById('formElementB').disabled = false;
        }
    }

    var dec; //aantal decimals
    var toErase; //aantal decimals die weg moeten
    var num; //nummer van antwoord

    function IncreaseDecimals()
    {
        if (typeof dec == "undefined")
        {
            dec = GetDecimals();
            toErase = 0;
        }
        if (dec < 10) toErase++;
        SetDecimals(num);
    }

    function DecreaseDecimals()
    {
        if (typeof dec == "undefined")
        {
            dec = GetDecimals();
            toErase = 0;
        }
        if (dec > 0) toErase--;
        SetDecimals(num);
    }

    function GetDecimals()
    {
        num = document.getElementsByClassName('NumAns')[0].innerHTML;
        if (Math.floor(num) === num) return 0;
        return num.toString().split(".")[1].length || 0;
    }

    function SetDecimals(ans)
    {
        var decimals = ans.toString().split(".")[1];
        decimals = decimals.slice(0, toErase);
        var rounded = ans.toString().split(".")[0] + "." + decimals;
        rounded = parseFloat(rounded);
        document.getElementsByClassName('NumAns')[0].innerHTML = rounded;
    }

    window.onload = SquareCheck;
</script>

</head>
<body>
<div id="Error">
    <img src="error.jpg" height="296" width="446" style="border: 2px solid #fff; border-radius: 6px;">
    <p class="Error-message">#Error</p>
</div>
<div id="Calculator">
    <form autocomplete="off" action="calculator.php" method="POST" name="Form">
    <div class="Answer">
        <div style="float: left; width: 80%; height: 80%; margin-top: 14px;">
            <span class="NumAns" style="text-align: center; vertical-align: sub;"><?php echo $answer; ?></span>
        </div>
        <div style="float: right; width: 15%; height: 80%; margin-top: 14px;">
            <div class="buttonElement" onclick="IncreaseDecimals()" style="float: right; margin: 9px 8px 2px 8px; padding: 3px 8px;">⇧</div>
            <div class="buttonElement" onclick="DecreaseDecimals()" style="float: right; margin: 2px 8px 9px 8px; padding: 3px 8px; clear: both;">⇩</div>
        </div>
    </div>
        <div class="Forms">
            <select id="Operator" name="Operator" class="formElement" onChange="SquareCheck()">
                <option value="Select"hidden <?php if(!isset($Op)) echo "selected";?>>Kies operator...</option>
                <option value="Add"<?php if(isset($Op) && $Op == "Add") echo "selected";?>>+</option>
                <option value="Sub"<?php if(isset($Op) && $Op == "Sub") echo "selected";?>>-</option>
                <option value="Multi"<?php if(isset($Op) && $Op == "Multi") echo "selected";?>>*</option>
                <option value="Div"<?php if(isset($Op) && $Op == "Div") echo "selected";?>>/</option>
                <option value="Power"<?php if(isset($Op) && $Op == "Power") echo "selected";?>>Power</option>
                <option value="Square"<?php if(isset($Op) && $Op == "Square") echo "selected";?>>Square root</option>
                <option value="ConversionToMi"<?php if(isset($Op) && $Op == "ConversionToMi") echo "selected";?>>Km to Mi</option>
                <option value="ConversionToKm"<?php if(isset($Op) && $Op == "ConversionToKm") echo "selected";?>>Mi to Km</option>
            </select>
            <input type="submit" name="AnsButton" class="buttonElement" value="Ans" style="float: right; margin: 12px 35px 12px -16px; padding: 7px 14px;">
            <input type="text" name="NumberA" id="formElementA" placeholder="Eerste nummer" value="<?php echo isset($GetalA) ? $GetalA : '' ?>" onChange="WeDontDoThatHere();">
            <input type="text" name="NumberB" id="formElementB" placeholder="Tweede nummer" value="<?php echo isset($GetalB) ? $GetalB : '' ?>" onChange="WeDontDoThatHere();">
            <div class="Buttons">
                <input type="submit" name="Reken" class="buttonElement" value="Reken">
                <input type="submit" name="Reset" class="buttonElement" value="Reset">
            </div>
        </div>
        <div class="History">
            <textarea name="History" rows="6" cols="24" readonly style="border: 0px; resize: none; font-size: 20px; outline: none;"><?php echo $His; ?></textarea>
        </div>
    </form>
</div>
</body>
</html>