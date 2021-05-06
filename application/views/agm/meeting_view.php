<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.1.1/list.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

<body>
<style>
    .meeting_pinyin, .meeting_fpinyin, .membership_id{
        display:none;
    }
    .name, .id{
        border:1px solid #CCC;
    }
    ul{
        margin:0;
        padding:0;
    }
</style>
<script type="text/javascript">
function update_attendance(total_attend ,type,id){
//    total_attend = (me.checked) ? 1 : 0;
    $.ajax({
    url: '<?= base_url('agm/update_attendance'); ?>',
    type: 'post',
    data: {type:type,id:id,status:total_attend },
    success: function( data, textStatus, jQxhr ){
        console.log(JSON.stringify( data ) );
    }
});
}
function empty_box(){
$('#search_box').val("");
}
</script>
<div id="hacker-list">
    <div class='row'>&nbsp;</div>
    <div class='row'><div class='col-sm-6 text-center'><a href='<?= base_url('agm/chapter'); ?>'>道場</a> | <a href='<?= base_url('agm/member'); ?>'>個人</a> | <a href='<?= base_url('agm/stats'); ?>'>統計</a> </div></div>
    <div class='row'><input class="search col-sm-6" id='search_box' onclick='empty_box();' /></div>
    <ul class="list">
        <?php $id_name = ($type == 'member') ? 'member_id' : 'chapter_id'; foreach($chapter as $d): ?>
        <li class='row' style='list-style:none;'>
            <div class='name col-sm-3'><?= $d['name_chinese']; ?></div>
            <div class='id col-sm-3'><?= $d['meeting_id']; ?>
            <input name='radio_<?= $d[$id_name]; ?>' type='radio' <?php if(isset($attendance[$type][$d[$id_name]])) if($attendance[$type][$d[$id_name]] == '0') echo "checked"; ?> onclick='update_attendance(0,"<?= $type; ?>","<?= $d[$id_name]; ?>")'>0
            <input name='radio_<?= $d[$id_name]; ?>' type='radio' <?php if(isset($attendance[$type][$d[$id_name]])) if($attendance[$type][$d[$id_name]] == '1') echo "checked"; ?> onclick='update_attendance(1,"<?= $type; ?>","<?= $d[$id_name]; ?>")'>1
            <?php if($type != 'member'): ?>
            <input name='radio_<?= $d[$id_name]; ?>' type='radio' <?php if(isset($attendance[$type][$d[$id_name]])) if($attendance[$type][$d[$id_name]] == '2') echo "checked"; ?> onclick='update_attendance(2,"<?= $type; ?>","<?= $d[$id_name]; ?>")'>2
            <input name='radio_<?= $d[$id_name]; ?>' type='radio' <?php if(isset($attendance[$type][$d[$id_name]])) if($attendance[$type][$d[$id_name]] == '3') echo "checked"; ?> onclick='update_attendance(3,"<?= $type; ?>","<?= $d[$id_name]; ?>")'>3
            <?php endif; ?>
            </div>

            <div class='meeting_pinyin'><?= $d['meeting_pinyin']; ?></div>
            <div class='meeting_fpinyin'><?= $d['meeting_fpinyin']; ?></div>
            <div class='membership_id'><?= $d['membership_id']; ?></div>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
<script type="text/javascript">var options = {
    valueNames: [ 'meeting_pinyin', 'meeting_fpinyin','membership_id' ]
};

var hackerList = new List('hacker-list', options);</script>
</body>
</html>