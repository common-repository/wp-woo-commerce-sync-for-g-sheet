<?php
class GSSP_FrontEnd{

	function gssp_adminPanelIndexPage($authCode="#",$generatedAuthCode,$token){
	
?>



<div class="row rowSaeKarna" >

  <div class="col-md-2">
        <label>Please Enter Your Auth Code:</label>    
  </div>

  <div class="col-md-6">
        <input type="text" style="width:100%" name="token" id="authCode" value="<?php echo $generatedAuthCode ?>">   
  </div>

  
  
</div>


<div class="row rowSaeKarna" style="text-align: right;margin-top: -20px" >
        <div class="col-md-8">
            <label style="margin-top: -17px;"><a href="<?php echo $authCode ?>">Click Here</a> to get your new auth code</label>  
        </div>
          
  </div>

<div class="row rowSaeKarna" style="margin-top:10px"> 

  <div class="col-md-8" style="display:flex;justify-content: flex-end;">
      <button  type="button" class="btn btn-primary mb-2"  id="accessTokenSubmit">Submit Auth Code</button>
  </div>
  
  
</div>





<script type="text/javascript">


jQuery('#accessTokenSubmit')
    .click(function () {
       
        var btn = jQuery(this)
        jQuery("#accessTokenSubmit").text('Loading');

       var data={
        'action': 'authCode',
        'authCode':jQuery("#authCode").val(),
        }
        jQuery.post(ajaxurl, data, function(response) {
            var responseabc=JSON.parse(response);

            if(responseabc.success){
                Swal.fire(
                  'Success!',
                  responseabc.message,
                  'success'
                );

                window.location.reload();

            }
            else{

                Swal.fire({
                  type: 'error',
                  title: 'Invalid Credentials',
                  text: responseabc.message
                });

            }
            jQuery("#accessTokenSubmit").text('Submit Auth Code');
        });  
});



</script>

<?php




	}



  function gssp_notAuthorized(){
?>

  <div class="row" style="margin-bottom: 10px;">
      <h3>Please Submit Your AuthCode To Proceed Further</h3>
  </div>


<?php
  }



  function gssp_getAllSpreadSheetNames($token,$selectedItem=False){


    $status=true;
    $google_spreadsheets_return = wp_remote_get("https://www.googleapis.com/drive/v3/files?q=mimeType%20%3D%20'application%2Fvnd.google-apps.spreadsheet'
         &access_token=".$token);
    $response=json_decode($google_spreadsheets_return['body']);
    $selectBox='<select id="documentID" style="width: 100%; max-width: none">';

    if(empty($response->files)){  
      return False;
    }
    $selectBox.= "<option  value=''></option> "    ;
    foreach ($response->files as $key => $value) {



      if($value->id == $selectedItem){
        $selectBox.= "<option selected value='". $value->id ."'>". $value->name ."</option> "    ;

      }
      else{
        $selectBox.= "<option value='". $value->id ."'>". $value->name ."</option> "    ;
      }
      
     
      
    }
    $selectBox.="</select>";

    if($google_spreadsheets_return['response']['code'] != 200){
    
      $selectBox = $google_spreadsheets_return['response']['message'];
      $status=false;
      
    }


?>


<div class="row rowSaeKarna">
            <div class="col-md-2">
                <label>Please Select Document:</label>
            </div>

            <div class="col-md-6">
                <?php echo $selectBox; ?>
            </div>
    </div>


<div class="row rowSaeKarna" style="margin-top:10px"> 

  <div class="col-md-8" style="display:flex;justify-content: flex-end;">
      <button type="button" class="btn btn-primary mb-2" id="selectDocumentBtn">Select Document</button>
  </div>
  
  
</div>



</br>





<script type="text/javascript">


jQuery('#selectDocumentBtn')
    .click(function () {
       
        var btn = jQuery(this)
        jQuery("#selectDocumentBtn").text('Loading');

       var data={
        'action': 'selectDocument',
        'documentID':jQuery("#documentID").val(),
        }
        jQuery.post(ajaxurl, data, function(response) {
            var responseabc=JSON.parse(response);

            if(responseabc.success){
                Swal.fire(
                  'Success!',
                  responseabc.message,
                  'success'
                );

            }
            else{

                Swal.fire({
                  type: 'error',
                  title: 'Invalid Credentials',
                  text: responseabc.message
                });

            }
            jQuery("#selectDocumentBtn").text('Select Document');
        });  
});



</script>




<?php

return True;
  }



  function gssp_selectHeadingsToShow($selectedItems,$remainingItems){


    



?>












<style type="text/css">
  .sidebar-nav .navbar .navbar-collapse {
    padding: 0;
    max-height: none;
  }
  .sidebar-nav .navbar ul {
    float: none;
    display: block;
  }
  .sidebar-nav .navbar li {
    float: none;
    display: block;
  }
  .sidebar-nav .navbar li span {
    padding-top: 12px;
    padding-bottom: 12px;
    color: #777;
    position: relative;
    display: block;
    padding: 10px 15px;
    text-decoration: none;
  }

.ballu li {
  width: auto !important;
   
    display: inline !important;
    float: left !important;
}

.ballu li span{
  display: inline-block;
    min-width: 10px;
    padding: 3px 7px;
    font-size: 12px;
    font-weight: 700;
    line-height: 1;
    color: white !important;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    background-color: #777;
    border-radius: 10px;
    margin-right: 2px;
    margin-top: 5px;
}

#options
{
  /*margin-top: 28px;*/
}

.btn-primary {
  color: #fff;
  background-color: #337ab7;
  border-color: #2e6da4;
}
.btn-primary:focus,
.btn-primary.focus {
  color: #fff;
  background-color: #286090;
  border-color: #122b40;
}
.btn-primary:hover {
  color: #fff;
  background-color: #286090;
  border-color: #204d74;
}
.btn-primary:active,
.btn-primary.active,
.open > .dropdown-toggle.btn-primary {
  color: #fff;
  background-color: #286090;
  border-color: #204d74;
}
.btn-primary:active:hover,
.btn-primary.active:hover,
.open > .dropdown-toggle.btn-primary:hover,
.btn-primary:active:focus,
.btn-primary.active:focus,
.open > .dropdown-toggle.btn-primary:focus,
.btn-primary:active.focus,
.btn-primary.active.focus,
.open > .dropdown-toggle.btn-primary.focus {
  color: #fff;
  background-color: #204d74;
  border-color: #122b40;
}
.btn-primary:active,
.btn-primary.active,
.open > .dropdown-toggle.btn-primary {
  background-image: none;
}
.btn-primary.disabled:hover,
.btn-primary[disabled]:hover,
fieldset[disabled] .btn-primary:hover,
.btn-primary.disabled:focus,
.btn-primary[disabled]:focus,
fieldset[disabled] .btn-primary:focus,
.btn-primary.disabled.focus,
.btn-primary[disabled].focus,
fieldset[disabled] .btn-primary.focus {
  background-color: #337ab7;
  border-color: #2e6da4;
}
.btn-primary .badge {
  color: #337ab7;
  background-color: #fff;
}

.collapse:not(.show){
  display: block;
}

a {
    color: #428BCA;
    text-decoration: none;
}

a:hover, a:focus {
    color: #2A6496;
    text-decoration: underline;
}


</style>


  <div class="row">


  <div class="col-md-9">
    <label style="margin-bottom: 8px">Please Drag And Drop Fields Below</label>
    <div id="selectedOptions" ondrop="drop(event)" ondragover="allowDrop(event)">
      <div class="sidebar-nav">
        <div class="navbar navbar-default" role="navigation">

          <div class="navbar-collapse collapse sidebar-navbar-collapse">
            <ul id="chotabox" class="nav navbar-nav ballu" style="height: 200px">
              <?php
            foreach ($selectedItems as $key => $value) {
               //echo '<button id="'.$value.'" data-value="'.$value.'" draggable="true" ondragstart="drag(event)">'.$value.'</button>';
               echo '<li style="cursor:pointer" id="'.$value.'" data-value="'.$value.'" draggable="true" ondragstart="drag(event)"><span>'.$value.'</span></li>';
             } 
            ?>
           
                
              <!-- <li class="active"><a href="#">Menu Item 1</a></li>
              <li><a href="#">Menu Item 2</a></li>

              <li><a href="#">Menu Item 4</a></li> -->
              
            </ul>
          </div>
          <!-- /.nav-collapse-->
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <label style="margin-bottom: 8px">List Of Fields Available</label>
   <div id="options" ondrop="drop(event)" ondragover="allowDrop(event)" style="height:200px; ">
      <div class="sidebar-nav">
        <div class="navbar navbar-default" role="navigation">

          <div class="navbar-collapse collapse sidebar-navbar-collapse">
            <ul id="ri" class="nav navbar-nav" style="height: 200px;overflow: scroll;">
              <!-- <li class="active"><a href="#">Menu Item 1</a></li>
              <li><a href="#">Menu Item 2</a></li>

              <li><a href="#">Menu Item 4</a></li> -->
              

              <?php
              foreach ($remainingItems as $key => $value) {
                 //echo '<button id="'.$value.'" data-value="'.$value.'" draggable="true" ondragstart="drag(event)">'.$value.'</button>';
                 echo '<li style="cursor:pointer" id="'.$value.'" data-value="'.$value.'" draggable="true" ondragstart="drag(event)"><span>'.$value.'</span></li>';
               } 
              ?>



            </ul>
          </div>
          <!-- /.nav-collapse-->
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row" style="margin-top:10px"> 

  <div class="col-md-8" style="display:flex;justify-content: flex-end;">
      <button type="button" class="btn btn-primary mb-2" id="saveSelectedOptions">Save options</button>
  </div>
  
  
</div>






<!--   <div class="row">




    <div class="col-md-6">
        <div id="options" ondrop="drop(event)" ondragover="allowDrop(event)" style="height:200px; background-color:green;">
            

            <div class="row">
              <div class="col-md-12" style="background-color: white">
                  <label>Hanbhai</label>
              </div>
            </div>

            <?php
            foreach ($remainingItems as $key => $value) {
               echo '<button id="'.$value.'" data-value="'.$value.'" draggable="true" ondragstart="drag(event)">'.$value.'</button>';
             } 
            ?>
        </div>   
    </div>


    <div class="col-md-6">
          <div id="selectedOptions" ondrop="drop(event)" ondragover="allowDrop(event)" style="height:200px; background-color:yellow; ">
            <?php
            foreach ($selectedItems as $key => $value) {
               echo '<button id="'.$value.'" data-value="'.$value.'" draggable="true" ondragstart="drag(event)">'.$value.'</button>';
             } 


            ?>
          </div>   

          <button id="saveSelectedOptions">Save options</button>
    </div>
  
  </div>




-->



<script type="text/javascript">
  






jQuery('#saveSelectedOptions')
    .click(function () {

        var selecteditems=[];
        jQuery("#selectedOptions li").each(function(i, obj) {
            //test

            console.log(jQuery(this).attr("data-value"));
            selecteditems.push(jQuery(this).attr("data-value"));
        });
        console.log(selecteditems);
       
        var btn = jQuery(this)
        jQuery("#saveSelectedOptions").text('Loading');

       var data={
        'action': 'userSelectedItems',
        'selectedOptions':selecteditems,
        }
        jQuery.post(ajaxurl, data, function(response) {
            var responseabc=JSON.parse(response);

            if(responseabc.success){
                Swal.fire(
                  'Success!',
                  responseabc.message,
                  'success'
                );

            }
            else{

                Swal.fire({
                  type: 'error',
                  title: 'Invalid Credentials',
                  text: responseabc.message
                });

            }
            jQuery("#saveSelectedOptions").text('Save Options');
        });  
});


  function drag(ev) {
  ev.dataTransfer.setData("text", ev.target.id);
}

function allowDrop(ev) {
  ev.preventDefault();
}

function drop(ev) {
  ev.preventDefault();
  console.log(ev)
  var data = ev.dataTransfer.getData("text");
  console.log(data);
  console.log();
  if(ev.target.id == "chotabox"){
    document.getElementById("chotabox").appendChild(document.getElementById(data));
  }
  else{
    document.getElementById("ri").appendChild(document.getElementById(data))
  }
  //ev.target.appendChild(document.getElementById(data));
  //document.getElementById("chotabox").appendChild(document.getElementById(data));
}
</script>


<!-- <div id="barayAbbu">


  
</div>
<div id="div1" ondrop="drop(event)" ondragover="allowDrop(event)" style="background-color: red;width: 100px;height: 100px;"></div>

<img id="drag1" src="https://images.unsplash.com/photo-1494253109108-2e30c049369b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&w=1000&q=80" draggable="true" ondragstart="drag(event)" width="336" height="69">

<img id="drag2" src="https://images.unsplash.com/photo-1494253109108-2e30c049369b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&w=1000&q=80" draggable="true" ondragstart="drag(event)" width="336" height="69">

<img id="drag3" src="https://images.unsplash.com/photo-1494253109108-2e30c049369b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&w=1000&q=80" draggable="true" ondragstart="drag(event)" width="336" height="69">


<img id="drag4" src="https://images.unsplash.com/photo-1494253109108-2e30c049369b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&w=1000&q=80" draggable="true" ondragstart="drag(event)" width="336" height="69"> -->


<?php
  }
}