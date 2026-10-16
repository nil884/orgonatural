         <?
         $transid= $_POST['txnid'];
          $amount= sprintf("%.2f", $_POST['amount']);
          $firstname= $_POST['firstname'];
          $email= $_POST['email'];
          $phone= $_POST['phone'];
          $productinfo=substr($_POST['productinfo'],0,20) ;
          $surl=$_POST['surl'];
           $furl=$_POST['furl'];
          $udf1=$_POST['udf1'];
          $udf3 = $_POST['udf3']

        ?>

                <form method="POST" action="../easebuzz.php?api_name=initiate_payment" id="myForm" style="display:none">

                                <input id="txnid" class="txnid" name="txnid" value="<?=$transid; ?>" placeholder="T31Q6JT8HB">

                                <input id="amount" class="amount" name="amount" value="<?=$amount; ?>" placeholder="125.25">

                                <input id="firstname" class="firstname" name="firstname" value="<?=$firstname; ?>" placeholder="Easebuzz Pvt. Ltd.">

                                <input id="email" class="email" name="email" value="<?=$email; ?>" placeholder="initiate.payment@easebuzz.in">

                                <input id="phone" class="phone" name="phone" value="<?=$phone; ?>"  placeholder="0123456789">

                                <input id="productinfo" class="productinfo" name="productinfo" value="Product Purchase" placeholder="">

                                <input id="surl" class="surl" name="surl" value="<?=$surl; ?>" placeholder="http://localhost:3000/response.php">

                                <input id="furl" class="furl" name="furl" value="<?=$furl; ?>" placeholder="http://localhost:3000/response.php">

                                <input id="udf1" class="udf1" name="udf1" value="<?=$udf1; ?>" placeholder="User description1">

                                <input id="udf2" class="udf2" name="udf2" value="Easebuzz" placeholder="User description2">

                                <input id="udf3" class="udf3" name="udf3" value="<?php  echo $udf3; ?>" placeholder="User description3">

                                <input id="udf4" class="udf4" name="udf4" value="" placeholder="User description4">

                                <input id="udf5" class="udf5" name="udf5" value="" placeholder="User description5">

                                <input id="address1" class="address1" name="address1" value=""  placeholder="#250, Main 5th cross,">


                                <input id="address2" class="address2" name="address2" value=""  placeholder="Saket nagar, Pune">

                                <input id="city" class="city" name="city" value="" placeholder="Pune">

                                <input id="state" class="state" name="state" value="" placeholder="Maharashtra">

                                <input id="country" class="country" name="country" value="" placeholder="India">

                                <input id="zipcode" class="zipcode" name="zipcode" value="" placeholder="123456">

                            <button type="submit">SUBMIT</button>

                </form>

            <script>
                document.getElementById("myForm").submit();
            </script>
