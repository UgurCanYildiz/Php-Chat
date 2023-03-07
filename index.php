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
            $_SESSION['uyeid'] = $row['uyeid'];
            $_SESSION['kullaniciadi'] = $row['kullaniciadi'];
            echo "BAŞŞARILI GİRİŞ BEKLEYİN";
            header('refresh:2;url=index.php');


        }
         else{
            echo "<div class='hata'>
            Kullanıcı adı veya sifre hatalı
        </div>";
        }


    }



    
    
}



?>

<?php 
    if(isset($_GET["cikis"])){


        echo    "<div class='hata'>
            Çıkış Yapılıyor.
        </div>";;
        session_destroy();
        header('refresh:2;url=index.php');

    }

?>


<!-- HTML KODLARI--->



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHAT</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    



<?php if(!isset($_SESSION['oturum'])){ ?>
<!--SİTE FORMU-->



<div class="form__group field">
<form action="" method="post">
<input type="text" name="kullaniciadi" class="form__field" placeholder="Kullanıcı Adı :"><br>
<input type="password" class="form__field" name="sifre" placeholder="Sifre : "><br>


<button type="submit" name="giris" class="btn">Giriş Yap</button>

</form>

</div>










<?php }else{

    $id = $_SESSION['uyeid'];
    $select = " SELECT count(*) as toplam FROM mesaj WHERE gonderilenid = '$id' AND durum = 2";
    $result = mysqli_query($conn, $select);
    $row = mysqli_fetch_array($result);
    
    ?>
    <div class="navbar">
        <div class="navbar-isim">

        <i class="fa fa-user"></i>
 <?php echo $_SESSION['kullaniciadi'];?>

        </div>
    <div class="navbar-bildirim">
    <a href="index.php?gelenmesajlar">
    <span class="material-symbols-outlined">
mail
</span>    
    
    Yeni mesajlar (<?php echo $row['toplam']; ?>)</a>
    <a href="index.php?cikis">
    <span class="material-symbols-outlined">
logout
</span>     
    ÇIKIŞ YAP</a>

    </div>
    </div>
    
    <?php 
    if(isset($_POST['mesajgonder'])){
        $id = $_SESSION["uyeid"];
        $uyebilgisi = $_POST['uyeler'];
        $mesaj = $_POST['mesaj'];
        $tarih = date('Y-m-d H-i-s');

        if (!$uyebilgisi  or !$mesaj) {
            echo    "<div class='hata'>
            Boş Bırakmayınız.
        </div>";
        
        header('refresh:2;url=index.php');

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



<div class="main-screen">
    <h3>CHAT</h3>

<div class="center-cizgi">

</div>


<label for="" class="msj-write">Mesaj Gönder</label>

<div class="write">


    <form action="" method="post">
   

   <select name="uyeler">
   <option value="" selected disabled hidden>Kişiler</option>
<?php 
$id = $_SESSION['uyeid'];

$select = " SELECT * FROM user WHERE uyeid != '$id'";
$result = mysqli_query($conn, $select);


while($row = mysqli_fetch_array($result)){
if(mysqli_num_rows($result) > 0 ){
        echo '<option value="'.$row['uyeid'].'" >'.$row['kullaniciadi'].'</option>';
}
}
?>

</select>


    <br>

<textarea name="mesaj"  cols="30" rows="10" placeholder="Metin Yazmak için"></textarea><br>
<button type="submit" name="mesajgonder" class="btn-gonder" >Gönder</button>

</form>
</div>


<?php 
    if(isset($_POST['add'])){
        $kullaniciadi = $_POST['kullaniciadi'];
        $sifre = $_POST['sifre'];

        if (!$kullaniciadi or !$sifre) {
            echo    "<div class='hata'>
            Boş Bırakmayınız.
        </div>";

        }else
        {

        
            $insert = "INSERT INTO user(kullaniciadi,sifre) VALUES('$kullaniciadi','$sifre')";
            $upload = mysqli_query($conn , $insert);
            if ($result) {
                echo    "<div class='hata'>
                Eklendi.
            </div>";
            header('refresh:1;url=index.php');

            }

    }
}



    



?>



<label for="" class="add-baslik">Üye Ekle</label>
<div class="add">
<div class="add-form">
    <form action="" method="post">
    <input type="text" name="kullaniciadi" class="add-input" placeholder="Kullanıcı Adı :"><br>
    <input type="password" class="add-input" name="sifre" placeholder="Sifre : "><br>
    <button type="submit" class="btn-ekle" name="add">Ekle</button>
        
    </form>
</div>

</div>





<?php 
    if(isset($_GET['gelenmesajlar'])){
        echo "<hr/>";
        $id = $_SESSION['uyeid'];
        
        $select  = mysqli_query($conn, "SELECT * FROM mesaj INNER JOIN user ON user.uyeid = mesaj.gonderenid WHERE gonderilenid = $id ");
?>

</div>
<div class="msj-screen">
<div class="msj-read">
    

<table>
                        <thead>
                            <tr>
                                <th>Kullanıcı Adı </th>
                                <th>&nbsp;</th>
                                <th>Tarih</th>
                                <th>&nbsp;</th>

                                <th>Durum</th>
                                <th>&nbsp;</th>

                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <?php
                        while ($row = mysqli_fetch_assoc($select)) {
                            if ($row["gonderilenid"] == "$id") {
                                # code...
                        ?>
                            <tr">

                                <td><?php echo $row["kullaniciadi"]; ?></td>
                                <td>&nbsp;</td>

                                    <td><?php echo $row["tarih"]; ?></td>
                                <td>&nbsp;</td>

                                <td><?php echo $row["durum"] == 1 ? 'OKUNDU' :'Okunmadı' ?></td>

                                <td>&nbsp;</td>
                                <td class="b"><a href="index.php?mesajoku&id=<?php echo $row['id'];?> ">MESAJI OKU</a></td>
                                

                            </tr>


                        <?php
                            }
                        };

                        ?>

                    </table>



                        <?php 
                        
                        if (isset($_GET['exit'])) {
                            header('Location:index.php');
                        }
                        
                        
                        
                        ?>


                    <button class="btn-exit" name="geri"><a href="index.php?exit" style="color:black;padding:0.4rem;">Geri</a></button>

</div>

</div>



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













</body>
</html>


















