<script type="text/javascript" language="javascript" class="init">
    function updateAttendance(chapter_id,cm_id,value){
        //alert(chapter_id+" -- "+cm_id+" -- "+value);

        var data = {
            chapter_id : chapter_id,
            cm_id : cm_id,
            value : value,
            year : '<?=$year;?>'
        };
        $.ajax({
            type:"POST",
            url: "<?= base_url('admin/agm/replace_agm_attendance'); ?>/",
            data:data
        }).done(function(res) {

            console.log(res);
        });
    }

    function changeYear(value){
        window.location = '<?= base_url('admin/agm/year');?>/'+value;
    }
</script>

<div id="page-wrapper">

    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-center form-inline">
                <h1>會員大會出席表 <?= form_dropdown('',$years,$year,'class="form-control" id="selectYear" onchange="changeYear(this.value)" style="font-size:1em;height:1.4em;"');?></h1>
                <br />出席道場： <?= $total['chapter']; ?>
                <br />出席人數： <?= $total['chapter_member']; ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-10 col-lg-offset-1">
                <br />
                <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr class="info">
                            <td><b>會員編號</b></td>
                            <td><b>道場</b></td>
                            <td><b>代表 1</b></td>
                            <td><b>代表 2</b></td>
                            <td><b>代表 3</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($chapter as $chapter_id => $c): ?>
                            <tr>
                                <td><?= $c['membership_id'];?></td>
                                <td><?= $c['name_chinese'];?></td>
                                <td><?= form_dropdown('',$c['ajk'],(@$c['agm']['cm_id_1'])?"id_".@$c['agm']['cm_id_1']:"","class='form-control' onChange='updateAttendance(".$c['chapter_id'].",1,this.value)'");?></td>
                                <td><?= form_dropdown('',$c['ajk'],(@$c['agm']['cm_id_2'])?"id_".@$c['agm']['cm_id_2']:"","class='form-control' onChange='updateAttendance(".$c['chapter_id'].",2,this.value)'");?></td>
                                <td><?= form_dropdown('',$c['ajk'],(@$c['agm']['cm_id_3'])?"id_".@$c['agm']['cm_id_3']:"","class='form-control' onChange='updateAttendance(".$c['chapter_id'].",3,this.value)'");?></td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- /.row -->
</div>