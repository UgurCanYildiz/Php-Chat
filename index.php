<?php 
require_once("baglan.php");

/* Kullanıcı Kontrol */
if(isset($_POST['giris'])){
    $kullaniciadi = $_POST['kullaniciadi'];
    $sifre = $_POST['sifre'];

    if(!$kullaniciadi or !$sifre ){
        echo "Boş alan Bırakmayınız";
    }else{

        $select = " SELECT * FROM user WHERE kullaniciadi = '$kullaniciadi' && sifre = '$sifre' ";
        $result = mysqli_query($conn, $select);
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_array($result);
           
            $_SESSION['oturum'] = true;
            $_SESSION['uyeid'] = $row['id'];
            $_SESSION['kullaniciadi'] = $row['kullaniciadi'];
            echo "BAŞŞARILI GİRİŞ BEKLEYİN";
            header('refresh:2;url=https://mysitedawaw.000webhostapp.com/index.php');


        }
         else{
            echo "Kullanıcı adı veya sifre hatalı";
        }


    }

    echo $kullaniciadi . " ---- " . $sifre ."----" . $_SESSION['uyeid'];
}


?>

<?php if(!isset($_SESSION['oturum'])){ ?>
<!--SİTE FORMU-->
<form action="" method="post">
<input type="text" name="kullaniciadi" placeholder="Kullanıcı adı"><br>
<input type="password" name="sifre" placeholder="Sifre"><br>
<button type="submit" name="giris">Giriş Yap</button>

</form>


<?php }else{

    $id = $_SESSION['uyeid'];
    $select = " SELECT count(*) as toplam FROM mesaj WHERE gonderilenid = '$id' AND durum = 2";
    $result = mysqli_query($conn, $select);
    $row = mysqli_fetch_array($result);
    
    ?>
    HOŞGELDİNİZ : <?php echo $_SESSION['kullaniciadi'];?><br>
    <a href="index.php?gelenmesajlar">Yeni mesajlar (<?php echo $row['toplam']; ?>)</a>
    <a href="">ÇIKIŞ YAP</a>
<hr>

<?php 
    if(isset($_POST['mesajgonder'])){
        $id = $_SESSION["uyeid"];
        $uyebilgisi = $_POST['uyeler'];
        $mesaj = $_POST['mesaj'];
        $tarih = date('Y-m-d H-i-s');

        if (!$uyebilgisi  or !$mesaj) {
            echo "Boş bırakkmayınız .";
        }
        else{
            $insert = "INSERT INTO mesaj(gonderenid, gonderilenid, metin, durum, tarih) VALUES('$id','$uyebilgisi','$mesaj',2,'$tarih')";
            mysqli_query($conn, $insert);
            if($insert){
                echo "başarılı";
            }
            else{
                echo "hata";
            }


        }
        



    }
?> 





<form action="" method="post">
    <select name="uyeler" >

    <?php 
    $id = $_SESSION['uyeid'];

    $select = " SELECT * FROM user WHERE uyeid != '$id'";
    $result = mysqli_query($conn, $select);
    
    
    while($row = mysqli_fetch_array($result)){
    if(mysqli_num_rows($result) > 0 ){
            echo '<option value="'.$row['uyeid'].'">'.$row['kullaniciadi'].'</option>';
    }
}
    ?>

    </select>


    <br>

<textarea name="mesaj"  cols="30" rows="10"></textarea><br>
<button type="submit" name="mesajgonder" >Gönder</button>








</form>








<?php 
    if(isset($_GET['gelenmesajlar'])){
        echo "<hr/>";
        $id = $_SESSION['uyeid'];
        
        $select  = mysqli_query($conn, "SELECT * FROM mesaj INNER JOIN user ON user.uyeid = mesaj.gonderenid WHERE gonderilenid = $id ");
?>
<table>
                        <thead>
                            <tr>
                                <th>Kullanıcı Adı </th>
                                <th>Tarih</th>
                                <th>Durum</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <?php
                        while ($row = mysqli_fetch_assoc($select)) {
                            if ($row["gonderilenid"] == "$id") {
                                # code...
                        ?>
                            <tr>

                                <td><?php echo $row["kullaniciadi"]; ?></td>
                                <td><?php echo $row["tarih"]; ?></td>
                                <td><?php echo $row["durum"] == 1 ? 'OKUNDU' :'Okunmadı' ?></td>
                                <td><?php echo $row["tarih"]; ?></td>
                                <td><?php echo $row["id"]; ?></td>

                                <td><a href="index.php?mesajoku&id=<?php echo $row['id'];?>">MESAJI OKU</a></td>
                                

                            </tr>


                        <?php
                            }
                        };

                        ?>
                    </table>




  <?php }
        
    ?>          
        




        <?php 
        
                        if(isset($_GET['mesajoku'])){
                            echo "<hr/>";
                            $gon = $_SESSION['uyeid'];
                            $kid = $_GET['id'];
                            
                            if (!$id) {
                                header('Location:index.php'); 
                            }
                            else{

                                $select  = mysqli_query($conn, "SELECT * FROM mesaj INNER JOIN user ON user.uyeid = mesaj.gonderenid WHERE gonderilenid = $id  AND gonderilenid = '$gon'");
                                
                                if(mysqli_num_rows($select) > 0 ){
                                    $row = mysqli_fetch_array($select);

                                    $durum = 1;
                                    $a= ("UPDATE mesaj SET durum = '1' WHERE id  = '$kid'");
                                    $upload = mysqli_query($conn , $a);
                                    if($upload){
                                        
                                        echo "bAŞARILIII";
                                    }



                                    echo 'Gonderen Adı : ' .$row['kullaniciadi'] ;
                                    echo "<br/>";
                                    echo "Tarih : " .$row['tarih'];
                                    echo "<br/>";
                                    echo "Mesaj : " .$row['metin'] . $row['durum'];
                                }


                               



                                
                            else{
                                header('Location:index.php');
                            }
                            

                            }

                        }
        ?>




    <?php }?>