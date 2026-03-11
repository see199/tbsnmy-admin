<?php

$colval  = ['position','nric','name_chinese','name_dharma','name_malay','phone_mobile','email'];
$colname = ['職位','NRIC','姓名','法號','國語名字','手機','電郵'];
$colstyle= ['120px','','120px','150px','','150',''];

?>
<script>
$(document).ready(function() {

    // Regular expression to match Malaysian NRIC format: xxxxxx-xx-xxxx
    var nricRegex = /^\d{6}-\d{2}-\d{4}$/;

    // Function to validate NRIC and make AJAX request
    function validateAndFetchData(nricValue,newIdName) {
        if (nricRegex.test(nricValue)) {
            $.ajax({
                url: '<?= base_url('admin/chapter/ajax_get_contact'); ?>',
                method: 'POST',
                data: { nric: nricValue },
                success: function(response) {
                    var contact = JSON.parse(response);
                    var newId = newIdName.split(/[^0-9]/g).filter(part => part !== '');
                    console.log(contact);

                    // Update Data
                    var loopField = ["name_chinese","name_dharma","name_malay","phone_mobile","email","contact_id","cm_id","position","chapter_id"];
                    $.each(loopField,function(index,fieldName){
                        var selector = 'input[name="contact_new['+newId+'][' + fieldName + ']"]';
                        $(selector).val(contact[fieldName]).prop('disabled',false);
                    });
                    $('input[name="contact_new['+newId+'][position]"]').prop('disabled',false);



                },
            });
        }
    }

    // Listen for changes in .nric input field
    $('.nric-input').on('keyup', function() {
        var currentNRIC = $(this).val();
        
        // Call validation and fetch data when input matches required length/format (optional)
        if (currentNRIC.length === 14 && currentNRIC.includes('-')) { 
            validateAndFetchData(currentNRIC,this.id);
        }
        
    });

});
</script>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= lang('col_title_chapter_member'); ?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">

            <?= form_open_multipart(base_url('admin/chapter/update_ajk_list'),[
                'class' => 'form-horizontal',
                'id' => 'upload_form'
            ]);?>
            <?= form_input([
                'type' => "hidden",
                'name' => "chapter_id",
                'value'=> $chapter['chapter_id'],
            ]); ?>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class='col-xs-12 h3 text-center form-inline'>
                                <?= ($chapter['membership_id'] ? $chapter['membership_id'] : '-'); ?> |
                                <?= ($chapter['tb_id'] ? $chapter['tb_id'] : '-'); ?> :
                                <?= $chapter['name_chinese']; ?>
                                <br /><?= $chapter['name_malay']; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">

                    
                    <div class='row row-data'>
                        <table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                            <thead><tr class='info'>
                                <td>No.</td>
                                <?php foreach($colname as $k => $v): ?>
                                <td <?= ($colstyle[$k]) ? "width=".$colstyle[$k] : ""; ?>><?= $v;?></td>
                                <?php endforeach; ?>
                                <td></td>
                                <td><i style="color:darkred" class="fa-solid fa-trash"></i></td>
                            </tr></thead>
                            <tbody><?php foreach($chapter_member as $i => $c): $cid = $c['contact_id'];?>
                                <tr>
                                    <td><?= $i+1; ?></td>
                                    <?php foreach($colval as $k => $v): ?>
                                    <?php if($v == 'nric') : ?>
                                        <td><?= $c[$v];?>
                                        <?= form_input([
                                            'type' => "hidden",
                                            'name' => "contact[{$cid}][contact_id]",
                                            'value'=> $cid,
                                        ]); ?>
                                        <?= form_input([
                                            'type' => "hidden",
                                            'name' => "contact[{$cid}][cm_id]",
                                            'value'=> $c['cm_id'],
                                        ]); ?>
                                        </td>
                                    <?php else: ?>
                                        <td><input type=text name="<?= "contact[{$cid}][{$v}]" ?>" value="<?= $c[$v];?>" class="form-control"/></td>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                    <td><a href="<?= base_url("admin/contact/details/".$c['contact_id']); ?>">View</a></td>
                                    <td><?= form_checkbox("contact[{$cid}][delete]",1); ?></td>
                                </tr>
                            <?php endforeach;?></tbody>
                        </table>

                        <div style="padding-left:15px;color:seagreen"><h4><i class="fa-solid fa-user-plus"></i> 添加新理事成員 <small style='color:red'> * 需先填寫 NRIC</small></h4></div>
                        <table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                            <thead><tr class='info'>
                                <td>No.</td>
                                <?php foreach($colname as $k => $v): ?>
                                <td <?= ($colstyle[$k]) ? "width=".$colstyle[$k] : ""; ?>><?= $v;?></td>
                                <?php endforeach; ?>
                            </tr></thead>
                            <tbody><?php for($i=1;$i<=7;$i++):?>
                                <tr>
                                    <td><?= $i; ?></td>
                                    <?php foreach($colval as $k => $v): ?>
                                    <?php if($v == 'nric') : ?>
                                        <td>
                                        <?= form_input([
                                            'type'  => "text",
                                            'name'  => "contact_new[{$i}][nric]",
                                            'id'    => "nric_new_{$i}",
                                            'class' => 'form-control nric-input',
                                            'style' => 'width:130px'
                                        ]); ?>
                                        <?= form_input([
                                            'type' => "hidden",
                                            'name' => "contact_new[{$i}][contact_id]",
                                        ]); ?>
                                        <?= form_input([
                                            'type' => "hidden",
                                            'name' => "contact_new[{$i}][cm_id]",
                                        ]); ?>
                                        <?= form_input([
                                            'type' => "hidden",
                                            'name' => "contact_new[{$i}][chapter_id]",
                                        ]); ?>
                                        </td>
                                    <?php else: ?>
                                        <td><input type=text name="<?= "contact_new[{$i}][{$v}]" ?>" value="" class="form-control" disabled/></td>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endfor;?></tbody>
                        </table>
                    </div>

                    <div class='row'>
                        <div class='col-xs-12'>
                            <div class='form-group text-right' style="padding-right: 10px;">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa-solid fa-floppy-disk"></i> Update
                                </button>
                                <button type="reset" class="btn btn-danger">
                                    <i class="fa fa-times"></i> Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
    
    <!-- /.row -->
</div>