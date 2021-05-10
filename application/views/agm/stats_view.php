<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.1.1/list.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<body>
<script type="text/javascript">
function read_stats(){

    $.ajax({
        type: "GET",
        url: "<?= base_url('agm/ajax_stats'); ?>",
        async: true,
        cache: false,
        timout: 50000,

        success:function(data){
            var res = JSON.parse(data);
            $('#total_chapter').html(res.total_chapter);
            $('#total_chapter_member').html(res.total_chapter_member);
            $('#total_member').html(res.total_member);
            $('#total_people').html(res.total_member + res.total_chapter_member);

            $('#total_online_chapter').html(res.total_online_chapter);
            $('#total_online_chapter_member').html(res.total_online_chapter_member);
            $('#total_online_member').html(res.total_online_member);
            $('#total_online_people').html(res.total_online_member + res.total_online_chapter_member);

            $('#total_total_chapter').html(res.total_total_chapter);
            $('#total_total_chapter_member').html(res.total_total_chapter_member);
            $('#total_total_member').html(res.total_total_member);
            $('#total_total_people').html(res.total_total_member + res.total_total_chapter_member);
            $('#msg').html("");
            setTimeout(read_stats,1500);
        },

        error: function(XMLHttpRequest, textStatus, errorThrown){
            $('#msg').html(textStatus + " (" + errorThrown + ")");
            setTimeout(read_stats,10000); // Wait 15sec if error occurs
        }

    });

}
$(document).ready(function(){
    read_stats();
});
</script>
<div id="hacker-list">
    <div class='row'>&nbsp;</div>
    <div id="msg"></div>
    <div class='row'><div class='col-lg-6 col-lg-offset-1 col-sm-offset-1 col-sm-10 text-center'><a href='<?= base_url('agm/chapter'); ?>'>道場</a> | <a href='<?= base_url('agm/member'); ?>'>個人</a> | <a href='<?= base_url('agm/stats'); ?>'>統計</a> </div></div>
    <div class='row'><div class="box"><div class='col-lg-6 col-lg-offset-1 col-sm-offset-1 col-sm-10 text-center'>
    <table class='table table-bordered table-striped'>
    <thead><tr class="info"><th></th><th>現場</th><th>線上</th><th>總數</th></tr></thead>
    <tr><td>出席道場:</td><td id="total_chapter">0</td><td id="total_online_chapter"></td><td id="total_total_chapter"></td></tr>
    <tr><td>團體會員人數:</td><td id="total_chapter_member">0</td><td id="total_online_chapter_member"></td><td id="total_total_chapter_member"></td></tr>
    <tr><td>個人會員人數:</td><td id="total_member">0</td><td id="total_online_member"></td><td id="total_total_member"></td></tr>
    <tr><th>總數:</th><th id="total_people">0</th><th id="total_online_people"></th><th id="total_total_people"></th></tr>
    </table>
    </div></div>
    </div>
</div>
</body>
</html>