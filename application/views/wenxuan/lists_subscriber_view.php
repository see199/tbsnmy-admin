<script>
function load_box(me){

    if(me == 'new'){

        $('#form_packages').hide();

        $('#myModalLabel').html("添加新個人資料");
        $('#btn_modal_update_r').show();
        $('#btn_modal_update').hide();

        $('#wenxuan_id').val('');
        $('#wenxuan_name').val('');
        $('#wenxuan_contact').val('');
        $('#wenxuan_email').val('');
        $('#address1').val('');
        $('#address2').val('');
        $('#city').val('');
        $('#postcode').val('');
        $('#state').val('');
        $('#country').val('');
        $('#tbnews_free').val('');
        $('#tbnews_paid').val('');
        $('#randeng_free').val('');
        $('#randeng_paid').val('');
        $('#remarks').val('');
        $('#update_date').html('');
        $('#update_by').html('');

    }else{

        $('#form_packages').show();

        subscriber = jQuery.parseJSON(me);
        $('#myModalLabel').html("查看 / 更改資料");
        $('#btn_modal_update_r').show();
        $('#btn_modal_update').show();

        $('#wenxuan_id').val(subscriber.wenxuan_id);
        $('#wenxuan_name').val(subscriber.wenxuan_name);
        $('#wenxuan_name_receiver').val(subscriber.wenxuan_name_receiver);
        $('#wenxuan_contact').val(subscriber.wenxuan_contact);
        $('#wenxuan_email').val(subscriber.wenxuan_email);
        $('#address1').val(subscriber.wenxuan_address1);
        $('#address2').val(subscriber.wenxuan_address2);
        $('#city').val(subscriber.wenxuan_city);
        $('#postcode').val(subscriber.wenxuan_postcode);
        $('#state').val(subscriber.wenxuan_state);
        $('#country').val(subscriber.wenxuan_country);
        $('#tbnews_free').val(subscriber.tbnews_free);
        $('#tbnews_paid').val(subscriber.tbnews_paid);
        $('#randeng_free').val(subscriber.randeng_free);
        $('#randeng_paid').val(subscriber.randeng_paid);
        $('#remarks').val(subscriber.remarks);
        $('#update_date').html(subscriber.update_date);
        $('#update_by').html(subscriber.update_by);

        $.ajax({
            type:"POST",
            url: "<?= base_url('wenxuan/lists/ajax_generate_package_tbody'); ?>",
            data:{package:subscriber.package,wenxuan_contact:subscriber.wenxuan_contact}
        }).done(function(data) {
            var res = jQuery.parseJSON(data);
            $('#package_tbody').html(res.html);
        });
    }
}

function post_data(refresh_page){

    var data = {
        wenxuan_id       : $('#wenxuan_id').val(),
        wenxuan_name     : $('#wenxuan_name').val(),
        wenxuan_name_receiver : $('#wenxuan_name_receiver').val(),
        wenxuan_contact  : $('#wenxuan_contact').val(),
        wenxuan_email    : $('#wenxuan_email').val(),
        wenxuan_address1 : $('#address1').val(),
        wenxuan_address2 : $('#address2').val(),
        wenxuan_city     : $('#city').val(),
        wenxuan_postcode : $('#postcode').val(),
        wenxuan_state    : $('#state').val(),
        wenxuan_country  : $('#country').val(),
        tbnews_free      : $('#tbnews_free').val(),
        tbnews_paid      : $('#tbnews_paid').val(),
        randeng_free     : $('#randeng_free').val(),
        randeng_paid     : $('#randeng_paid').val(),
        remarks          : $('#remarks').val(),
    };

    $("#form_packages").each(function(){
        $(this).find(':input').each(function(){
            data['package_'+$(this).attr("id")] = $(this).val();
        });
        $(this).find(':input[type=checkbox]').each(function(){
            data['package_'+$(this).attr("id")] = $(this).prop('checked');
        });
    });
    
    $.ajax({
        type:"POST",
        url: "<?= base_url('wenxuan/lists/ajax_contact_update'); ?>",
        data:data
    }).done(function(data) {
        var res = jQuery.parseJSON(data);
        if(refresh_page == 1)
        setTimeout(function(){ location.reload();},2000);
    });
    
}

function delete_data(){
    
    if(!confirm("確認要刪除嗎？")) return;
    $.ajax({
        type:"POST",
        url: "<?= base_url('wenxuan/lists/ajax_delete_contact'); ?>",
        data:{wenxuan_id : $('#wenxuan_id').val()}
    }).done(function(data) {
        var res = jQuery.parseJSON(data);
        setTimeout(function(){ location.reload();},200);
    });

}

</script>

<div id="page-wrapper">

    <!-- Statistic Chart -->
    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <canvas id="myChart"></canvas>
            </div>
        </div>
        <!-- Statistic Library at https://www.chartjs.org/ -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
          const ctx = document.getElementById('myChart');

          var bgcolor = ["#B30000","#1A53FF","#5AD45A"];

          new Chart(ctx, {
            type: 'bar',
            data: {
              labels: <?= json_encode($stats['date']); ?>,
              datasets: [<?php $k=0;foreach($stats['package_id'] as $package_id => $package_total): ?>
              {
                label: '<?= $package[$package_id]['package_name'].' (RM '.$package[$package_id]['package_amount'].')'; ?>',
                data: <?= json_encode($package_total); ?>,
                backgroundColor: bgcolor[<?= $k%3; ?>]
              },
              <?php $k++;endforeach; ?>]
            },
            options:{
                plugins:{title:{
                    display: true,
                    text: "功德主統計"
                }},
                scales:{
                    x:{stacked: true},
                    y:{stacked: true}
                }
            }
          });
        </script>
    </div>


    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <h1>文宣功德主名單（ <?=$year;?> 年）</h1>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-right">
                <a class="btn btn-info" href="<?= base_url('wenxuan/lists/export_csv_unsent_package/'.$year); ?>"><i class="fa fa-download" aria-hidden="true"></i> Export CSV (EasyParcel)</a>

                <a class="btn btn-info" href="<?= base_url('wenxuan/lists/export_csv_blessing/'.$year); ?>"><i class="fa fa-download" aria-hidden="true"></i> Export CSV (報名法會)</a>
            </div>
            <br /><br />
        </div>
    </div>


    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2">

                
                <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr class="info">
                            <td rowspan=2><b>名字<br /><small>** 點擊可查看 / 更新資料 **</small></b></td>
                            <td rowspan=2><b>登記日期</b></td>
                            <td rowspan=2><b>聯絡</b></td>
                            <td colspan=<?=sizeof($total);?>><b>功德主方案</b></td>
                            <td rowspan=2><b>已發贈品</b></td>
                            <td rowspan=2><b>完成付款</b></td>
                            <td rowspan=2><b>報-訂</b></td>
                            <td rowspan=2><b>報-送</b></td>
                            <td rowspan=2><b>燃-訂</b></td>
                            <td rowspan=2><b>燃-送</b></td>
                            <td rowspan=3><b>一次/分期</b></td>
                            <td rowspan=3><b><?=$year;?>年<br />報名表格</b></td>
                        </tr>
                        <tr class="info">
                            <?php foreach($total as $package_id => $t):?>
                            <td><b><?= $package[$package_id]['package_name'].'<br />(RM '.$package[$package_id]['package_amount'].')'; ?></b></td>
                            <?php endforeach;?>
                        </tr>
                        <tr class="success">
                            <td colspan=3><b>總數</b></td>
                            <?php foreach($total as $package_id => $t):?>
                            <td><b><?= $t; ?></b></td>
                            <?php endforeach;?>
                            <td><b><?= $gift_sent; ?></b></td>
                            <td><b><?= $payment_done; ?></b></td>
                            <?php $tbtotal = array();foreach($list as $c):
                                @$tbtotal['tbnews_free'] += $c['tbnews_free'];
                                @$tbtotal['tbnews_paid'] += $c['tbnews_paid'];
                                @$tbtotal['randeng_free'] += $c['randeng_free'];
                                @$tbtotal['randeng_paid'] += $c['randeng_paid'];
                            endforeach;?>
                            <td><b><?= $tbtotal['tbnews_free']; ?></b></td>
                            <td><b><?= $tbtotal['tbnews_paid']; ?></b></td>
                            <td><b><?= $tbtotal['randeng_free']; ?></b></td>
                            <td><b><?= $tbtotal['randeng_paid']; ?></b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $email_list = array('email' => array(),'name' => array()); ?>
                        <?php foreach($list as $c): ?>
                            <?php 
                                // For Checking Purpose
                                if(!filter_var(rtrim($c['wenxuan_email']),FILTER_VALIDATE_EMAIL)) $email_list['name'][$c['wenxuan_email']][] = rtrim($c['wenxuan_name']); 
                                else
                                    $email_list['email'][$c['wenxuan_email']] = rtrim($c['wenxuan_email']);

                            ?>
                            <!-- Highlight if Payment not Fully Paid and Gift Sent -->
                            <?php if(!$c['package'][$year]['payment_done'] && !$c['package'][$year]['fullpayment'] && $c['package'][$year]['gift_taken']): ?><tr class='danger'>
                            <!-- Highlight if Payment Fully Paid and Gift not yet Sent -->
                            <?php elseif($c['package'][$year]['payment_done'] && $c['package'][$year]['fullpayment'] && !$c['package'][$year]['gift_taken']): ?><tr class='success'>
                            <?php else: ?><tr>
                            <?php endif; ?>
                                <td><textarea style="display:none;"><?= json_encode($c); ?></textarea>
                                    <a href='javascript:void(0)' onclick="load_box($(this).prev().val())" data-toggle="modal" data-target="#myModal"><?= $c['wenxuan_name']; ?></a></td>
                                <td><?= $c['package'][$year]['create_date']; ?></td>
                                <td><?= $c['wenxuan_contact']; ?></td>
                                <?php foreach($total as $package_id => $t):?>
                                <td><b><?= ($package_id == $c['package'][$year]['package_id']) ? '<i class="fa fa-check" aria-hidden="true"></i>' : ""; ?></b></td>
                                <?php endforeach;?>
                                <td><b><?= $c['package'][$year]['gift_taken'] ? 'Sent' : ""; ?></b></td>
                                <td><b><?= $c['package'][$year]['payment_done'] ? '<i class="fa fa-check" aria-hidden="true"></i>' : "" ?></b></td>
                                <td><?= $c['tbnews_free'];?></td>
                                <td><?= $c['tbnews_paid'];?></td>
                                <td><?= $c['randeng_free'];?></td>
                                <td><?= $c['randeng_paid'];?></td>
                                <td><b><?= $c['package'][$year]['fullpayment'] ? '一次付清' : "分期付款" ?></b></td>
                                <td><?php foreach($c['package'] as $pyear => $p):?>
                                        <?php $form_full_url = ($pyear == $year ) ? $form_url.$p['md5_id'] : "" ?>
                                        <a href="<?= $form_full_url; ?>" target="_blank">view</a>
                                    <?php endforeach; ?>
                                </td>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 form-group">
                <h2>Email List -- <small>to copy to <a href="https://app.mailjet.com/contacts/lists/edit/YL" target="eml">MailJet subscription</a></small></h2>
                <?php if($google_email == 'see199@gmail.com') print_pre($email_list['name']);?>
                <textarea class="form-control w-100" rows="5"><?php echo implode("\n",$email_list['email']); ?></textarea>
                <br /><br />
            </div>
        </div>
    </div>
    
    <!-- /.row -->
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body" id="myModalBody">
                <div class="text-right"><small><b>Last update:</b> <span id="update_date"></span> by <span id="update_by"></span></small></div>
                <table class="table display">
                    <tr><td>聯絡人</td><td><input class="form-control" id="wenxuan_name"/></td></tr>
                    <tr><td>英文名字</td><td><input class="form-control" id="wenxuan_name_receiver"/></td></tr>
                    <tr><td>聯絡號碼</td><td><input class="form-control" id="wenxuan_contact"/></td></tr>
                    <tr><td>電郵</td><td><input class="form-control" id="wenxuan_email"/></td></tr>
                    <tr><td>地址</td><td>
                        <input type="hidden" id="wenxuan_id"/>
                        <input type="hidden" id="chapter_id"/>
                        <input class="form-control" id="address1" placeholder="Address 1"/>
                        <input class="form-control" id="address2" placeholder="Address 2"/>
                        <input class="form-control" id="city" placeholder="City"/>
                        <input class="form-control" id="postcode" placeholder="Postcode"/>
                        <input class="form-control" id="state" placeholder="State"/>
                        <input class="form-control" id="country" placeholder="Country"/>
                    </td></tr>
                    <tr><td>備註</td><td>
                        <textarea rows=5 class="form-control" id="remarks" placeholder="Remarks"></textarea>
                    </td></tr>
                </table>
                <table class="table display form-inline">
                    <tr><td>
                        真佛報 贈送: <input onClick="this.select();" class="form-control" id="tbnews_free" style="width:50px;"/>
                        訂閱: <input onClick="this.select();" class="form-control" id="tbnews_paid" style="width:50px;"/>
                    </td><td>
                        燃燈 贈送: <input onClick="this.select();" class="form-control" id="randeng_free" style="width:50px;"/>
                        訂閱: <input class="form-control" id="randeng_paid" style="width:50px;"/></tr>
                </table>
                
                <table class="table display table-bordered" id="form_packages">
                    <thead>
                        <tr class='success text-center'><td colspan=4><b>功德主方案</b></td></tr>
                        <tr class='success'><td style='width:25%'><b>年份 & 方案</b></td><td style='width:15%'><b>有效日期</b></td><td class='md-col-6'><b>備註</b></td><td style='width:10%'><b>Status</b></td></tr>
                    </thead>
                    <tbody id="package_tbody">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn_modal_update" class="btn btn-success" onclick="post_data(0);" data-dismiss="modal"><i class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></i> Update</button>
                <button type="button" id="btn_modal_update_r" class="btn btn-success" onclick="post_data(1);" data-dismiss="modal"><i class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></i> Update & Refresh</button>
                <button type="button" id="btn_modal_cancel" class="btn btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove" aria-hidden="true"></i> Cancel</button>
                <button type="button" id="btn_modal_update" class="btn btn-danger" onclick="delete_data();" data-dismiss="modal"><i class="glyphicon glyphicon-remove" aria-hidden="true"></i> DELETE!</button>
            </div>
        </div>
    </div>
</div>