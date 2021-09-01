<style>
  /* The flip box container - set the width and height to whatever you want. We have added the border property to demonstrate that the flip itself goes out of the box on hover (remove perspective if you don't want the 3D effect */
  .flip-box {
    background-color: transparent;
    width: 160px;
    height: 160px;
    border: 1px solid #f1f1f1;
    perspective: 1000px; /* Remove this if you don't want the 3D effect */
    padding: 0;
    display: inline-block;
  }

  /* This container is needed to position the front and back side */
  .flip-box-inner {
    position: relative;
    width: 100%;
    height: 100%;
    text-align: center;
    transition: transform 0.8s;
    transform-style: preserve-3d;
  }

  /* Do an horizontal flip when you move the mouse over the flip box container */
  .flip-box:hover .flip-box-inner {
    transform: rotateY(180deg);
  }

  /* Position the front and back side */
  .flip-box-front, .flip-box-back {
    position: absolute;
    width: 100%;
    height: 100%;
    -webkit-backface-visibility: hidden; /* Safari */
    backface-visibility: hidden;
  }

  /* Style the front side (fallback if image is missing) */
  .flip-box-front {
    background-color: #bbb;
    color: black;
  }

  /* Style the back side */
  .flip-box-back {
    background-color: grey;
    color: white;
    transform: rotateY(180deg);
  }

  .flip-box-back>a,a:hover {
    color: white;
    text-decoration: none;
  }
</style>


<script>
  
  function load_box(id){
    var q = jQuery.parseJSON($('#json_'+id).val());

    $('#myModalLabel').html('問題 ' + id + '： ' + q.Q);
    $('#btn_modal_update').show();
    
    $('#q_1').html(q.A1);
    $('#q_2').html(q.A2);
    $('#q_3').html(q.A3);
    $('#q_no').val(id);

    $('.ans').prop('checked', false);
  }

  function answer(){

    var id  = $('#q_no').val();
    var ans = $('input[name="ans"]:checked').val();
    var q   = jQuery.parseJSON($('#json_'+id).val());

    if(ans == q.AC){
      $('#msg').show().removeClass('alert-success alert-danger').addClass('alert-success').html('恭喜！答對了！');
      $('#img_'+id).attr('src','<?= base_url("asset/images/game/"); ?>S'+id+'.jpg');
      $('#correct_checker_'+id).val(1);
    }else{
      $('#msg').show().removeClass('alert-success alert-danger').addClass('alert-danger').html('哎呀，這個不對哦！');
      $('#img_'+id).attr('src','<?= base_url("asset/images/game/"); ?>B'+id+'.jpg');
      $('#correct_checker_'+id).val(0);
    }

    var total_correct = 0;
    $('.correct_checker').each(function(i, obj) {
      total_correct += parseInt(obj.value);
    });

    if(total_correct >= 6){
      $('#msg').show().removeClass('alert-success alert-danger').addClass('alert-success').html('恭喜！全部都答對！請填上您的資料。');
      $('#submit_details').css("display", "block");
    }
    //console.log("Q "+id + " ans: "+ans+" Total Correct: "+total_correct);
    //$('#congrat_image').html("Congrat Images");
  }

  function post_details(){
    var data = {
      name : $('#name').val(),
      email : $('#email').val(),
      phone : $('#phone').val(),
    };
    $.ajax({
        type:"POST",
        url: "<?= base_url('game/submit'); ?>/",
        data:data
    }).done(function(res) {
      $('#msg').show().removeClass('alert-success alert-danger').addClass('alert-success').html('成功呈交資料！歡迎分享給朋友們一起參與~');
      $('#submit_details').css("display", "none");
      $('#share').css("display", "block");
      $('#congrat_image').html("Congrat Images");
    })
  }
</script>

<div id="page-wrapper" style="padding:0;">
  <div class="row">&nbsp;</div>
  <div class="row"><div id="congrat_image" class="col-xs-10 col-xs-offset-1 col-lg-8 col-lg-offset-2">
    <?php foreach($game as $k => $row): ?>
    <div class="row" style="text-align: center;">
      <?php foreach($row as $k2 => $g): ?>
      <div class="flip-box">

        <div class="flip-box-inner">
          <div class="flip-box-front">
            <img id="img_<?= $g['no'];?>" src="<?= base_url("asset/images/game/".$g['img']); ?>" width=160px>
          </div>
          <a href='javascript:void(0)' onclick="load_box(<?= $g['no'];?>)" data-toggle="modal" data-target="#myModal"><div class="flip-box-back">
            <h3>問題<br /><?= $g['no'];?></h3>
            <input id='json_<?=$g['no'];?>' type=hidden value='<?=$g['json'];?>'>
            <input class='correct_checker' id='correct_checker_<?=$g['no'];?>' value="0" type=hidden>
          </div></a>
        </div>

      </div>
      <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
  </div></div>

  <div class="row">&nbsp;</div>
  <div class="row"><div class="col-xs-10 col-xs-offset-1 col-lg-8 col-lg-offset-2"><div id="msg" class="alert" style="display: none;"></div></div></div>

  <div class="row" id="share" style="display: none;"><div class="col-xs-10 col-xs-offset-1 col-lg-8 col-lg-offset-2" style="text-align: center;">
    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= base_url(uri_string()); ?>" target="_blank">
      <img src="<?= base_url('asset/images/game/fb-share.png'); ?>" alt="">
    </a>
  </div></div>

  

  <div class="row">&nbsp;</div>
  <div class="row" id="submit_details" style="display: none;">
    <div class="col-xs-10 col-xs-offset-1 col-lg-8 col-lg-offset-2"><form>
      <div class="form-group">
        <label for="name">姓名</label>
        <input type="text" class="form-control" id="name" placeholder="姓名">
      </div>
      <div class="form-group">
        <label for="email">電郵/Email</label>
        <input type="email" class="form-control" id="email" placeholder="請填寫您的電郵">
      </div>
      <div class="form-group">
        <label for="phone">電話</label>
        <input type="text" class="form-control" id="phone" placeholder="請填寫您的電話號碼">
      </div>
      <button type="button" class="btn btn-primary" onclick="post_details();">Submit</button>
    </form></div>
  </div>
  <div class="row">&nbsp;</div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body" id="myModalBody">
              選擇
              <br /><input type="radio" id="A1" class='ans' name="ans" value="A"> <label for="A1"> <span id="q_1"></span></label>
              <br /><input type="radio" id="A2" class='ans' name="ans" value="B"> <label for="A2"> <span id="q_2"></span></label>
              <br /><input type="radio" id="A3" class='ans' name="ans" value="C"> <label for="A3"> <span id="q_3"></span></label>
              <input type="hidden" id="q_no" value="" />
            </div>
            <div class="modal-footer">
                <button type="button" id="btn_modal_update" class="btn btn-success" onclick="answer();" data-dismiss="modal"> 回答</button>
            </div>
        </div>
    </div>
</div>