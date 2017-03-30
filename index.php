<?php
ob_start();
include ('config.php');

      // case start for upload & insert
      define ("MAX_SIZE","9000"); 
      function getExtension($str)
      {
               $i = strrpos($str,".");
               if (!$i) { return ""; }
               $l = strlen($str) - $i;
               $ext = substr($str,$i+1,$l);
               return $ext;
      }

      $valid_formats = array("jpg", "png", "gif", "bmp","jpeg","mp4","pdf");
      if(isset($_POST['case_form'])) 
      {
          $client_save = $_POST['client'];
          $client_explode = explode('|', $client_save);
          $client_capp = $_POST['capp'];
          $client_tpolicy = $_POST['tpolicy'];
          $client_camt = $_POST['camt'];
          $client_desc = $_POST['desc'];

          if ($valid_formats) {
            $caseqry2 = mysqli_query($connection , "INSERT INTO crm_case(`case_cname`, `case_capp`, `case_tpolicy`, `case_camt`, `case_desc`, `case_status`, `case_cid`, `case_mid`) VALUES ('$client_explode[0]','$client_capp','$client_tpolicy','$client_camt','$client_desc','0','$client_explode[1]','".$_SESSION['user_id']."')") or die(mysqli_error());
            
                if ($caseqry2) {
                    $msg = "Success";
                    $msg1 = base64_encode($msg);

                  } else {
                    $msg = "Failed";
                    $msg1 = base64_encode($msg);
                  }
          }
          $lastid_case = mysqli_insert_id($connection);

          	print_r($_POST);
          	print_r($_FILES);
          	exit;

          // start file case file uploads
          $uploaddir = "../uploadcase/"; //a directory inside
          foreach ($_FILES['photos']['name'] as $name => $value)
          {	
              $filename = stripslashes($_FILES['photos']['name'][$name]);
              $casefile_titleexplod = stripslashes($_POST['filenam'][$name]);
              $size=filesize($_FILES['photos']['tmp_name'][$name]);
              //get the extension of the file in a lower case format
                $ext = getExtension($filename);
                $ext = strtolower($ext);  
               if(in_array($ext,$valid_formats))
               {
                     if ($size < (MAX_SIZE*1024))
                     {
                       $image_name=time().$filename;
                       //echo "<img src='".$uploaddir.$image_name."' class='imgList'>";
                       $newname=$uploaddir.$image_name;
                       if (move_uploaded_file($_FILES['photos']['tmp_name'][$name], $newname)) 
                       { 
                       echo "INSERT INTO crm_case_file(`case_id`,`casefile_name`,`casefile_title`,`casefile_mid`,`casefile_cid`,`casefile_status`) VALUES('$lastid_case','$image_name','$casefile_titleexplod','".$_SESSION['user_id']."','$client_explode[1]','0')";

                        mysqli_query($connection , "INSERT INTO crm_case_file(`case_id`,`casefile_name`,`casefile_title`,`casefile_mid`,`casefile_cid`,`casefile_status`) VALUES('$lastid_case','$image_name','$casefile_titleexplod','".$_SESSION['user_id']."','$client_explode[1]','0')");
                       }
                       else
                        {
                        echo '<span class="imgList">You have exceeded the size limit! so moving unsuccessful! </span>';
                        }
                    }
                    else
                    {
                    echo '<span class="imgList">You have exceeded the size limit!</span>';
                    }
                 
                }
                else
                { 
                echo '<span class="imgList">Unknown extension!</span>';   
                }
                 
           }

           // header("Location:caseconsultations.php?msg=".$msg1);

      }
   // case end
?>

 <form action="" method="post" enctype="multipart/form-data" style="clear:both" onsubmit="LoadData()" id="form_sample_2" novalidate="novalidate">

              <div class="form-group col-md-12 col-xs-12">
                <label class="control-label">Description</label>
                <textarea class="form-control" name="desc" placeholder="Case Description"></textarea>

              </div>

              <div class="form-group col-xs-12">
                <input type="hidden" name="nameid" value="">
                          <label for="picture">* Upload Files For Client</label>
                          <input required="" class="form-control" style="height:auto;" name="photos[]" id="uploadfile" multiple="true" type="file">
                         
                          <div class="col-xs-12">
                         <div col-md-6 id="filelist" style="display:none;"> </div>
                         <div col-md-6 id="fileinputtag"> </div>
                         <div col-md-6 id="list"> </div>
                         </div>


                      <script>
                      function handleFileSelect(evt) {
                          var files = evt.target.files; // FileList object
                          // Loop through the FileList and render image files as thumbnails.
                          for (var i = 0, f; f = files[i]; i++) {
                            // Only process image files.
                            var reader = new FileReader();
                            // Closure to capture the file information.
                            reader.onload = (function(theFile) {
                              return function(e) {
                                //Render thumbnail.
                                var span = document.createElement('span');
                                span.innerHTML = ['<p>',escape(theFile.name),'</p><input type="text" name="filenam[]" value="">'].join('');
                                document.getElementById('list').insertBefore(span, null);
                              };
                            })(f);
                            // Read in the image file as a data URL.
                            reader.readAsDataURL(f);
                          }
                        }
                        document.getElementById('uploadfile').addEventListener('change', handleFileSelect, false);*/
                        </script>
              </div>
              
              <div class="inner-content col-md-12 text-center">
                <div class='actions'>
                  <input type="submit" class="btn btn-success" value="SUBMIT" name="case_form" />
                  </div>
              </div>
              <div class="col-md-12">&nbsp;</div>
          </form>
