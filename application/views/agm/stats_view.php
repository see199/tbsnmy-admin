<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

<body>
<script type="text/javascript">
function update_attendance(me,type,id){
    console.log(me.checked);
    $.ajax({
    url: '/agm/update_attendance',
    type: 'post',
    data: {type:type,id:id,status:me.checked},
    success: function( data, textStatus, jQxhr ){
        console.log(JSON.stringify( data ) );
    }
});
}
</script>
<div id="hacker-list">
    <div class='row'>&nbsp;</div>
    <div class='row'><div class='col-sm-6 text-center'><a href='<?= base_url('agm'); ?>'>道場</a> | <a href='<?= base_url('agm/member'); ?>'>個人</a> | <a href='<?= base_url('agm/stats'); ?>'>統計</a> </div></div>
    <div class='row'><div class='col-sm-5 text-center'>
    <table class='table table-bordered table-condensed'>
    <tr><td>Total Chapter:</td><td><?= $total_chapter; ?></td></tr>
    <tr><td>Total Chapter Member:</td><td><?= $total_chapter_member; ?></td></tr>
    <tr><td>Total Members:</td><td><?= $total_member; ?></td></tr>
    <tr><td><strong>Total People:</strong></td><td><strong><?= $total_member + $total_chapter_member; ?></strong></td></tr>
    </table>
    </div>
    </div>
</div>
</body>
</html>