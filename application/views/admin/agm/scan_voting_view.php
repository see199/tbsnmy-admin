<div class="container mt-5">
    <h2>會員大會 QR 領票記錄</h2>
    <div class="alert alert-success alert-dismissible fade in" role="alert" id='msgBox'>
      <strong>最新領票：</strong><span id='msgBoxMsg'></span>
    </div>
    <input type="text" id="qrdata" class="form-control" placeholder="QR Scanner">
    <input type="hidden" id="reg_name" />

    <hr />
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">領票統計 (Voting Form Stats)</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr class="active">
                                <th class="text-center">個人會員 (Personal)</th>
                                <th class="text-center">團體會員 (Group)</th>
                                <th class="text-center">總數 (Total)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="statsPersonal" style="font-size: 24px; font-weight: bold;"><?= $stats['personal']; ?></td>
                                <td id="statsGroup" style="font-size: 24px; font-weight: bold;"><?= $stats['group']; ?></td>
                                <td id="statsTotal" style="font-size: 24px; font-weight: bold; color: blue;"><?= $stats['total']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="userModalLabel">領票會員資料</h3>
            </div>
            <div class="modal-body">
              
              <div class="alert alert-danger alert-dismissible fade in" role="alert" id='errBox'>
                <strong>Error!</strong><span id='errMsg'></span>
              </div>

                <table class="table" style="width: 80%; margin: 0 auto;">
                  <tbody>
                      <tr>
                          <th scope="row" width="30%">姓名/法號</th>
                          <td id="userName"></td>
                      </tr>
                      <tr>
                          <th scope="row">NRIC</th>
                          <td id="userNric"></td>
                      </tr>
                      <tr>
                          <th scope="row">職位</th>
                          <td id="userPosition"></td>
                      </tr>
                      <tr>
                          <th scope="row">會員編號/道場</th>
                          <td id="userChapter"></td>
                      </tr>
                      <tr id="votedRow" class="danger" style="display:none;">
                          <th scope="row">領票狀態</th>
                          <td id="votedStatus" style="color:red; font-weight:bold;">已領票 / 已投票</td>
                      </tr>
                      <tr id="voteTimeRow" style="display:none;">
                          <th scope="row">領票時間</th>
                          <td id="voteTime"></td>
                      </tr>
                  </tbody>
              </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmVoteBtn"><i class="fa-solid fa-check"></i> 確認領票</button>
                <button type="button" class="btn btn-secondary" id="cancelBtn">取消</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let timeout;
    $('#qrdata').focus();
    $('#errBox').hide();

    $('#qrdata').on('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            const qrdata = $('#qrdata').val();
            if (qrdata) {
                $.ajax({
                    url: 'ajax_scan_qr',
                    type: 'POST',
                    dataType: 'json',
                    data: { qrdata: qrdata },
                    success: function(data) {
                        if (data.error) {
                            $('#userName').text('-');
                            $('#userNric').text('-');
                            $('#userPosition').text('-');
                            $('#userChapter').text('-');
                            $('#reg_name').val('');
                            $('#votedRow').hide();
                            $('#voteTimeRow').hide();
                            $('#confirmVoteBtn').hide();
                            
                            $('#userModal').modal('show');
                            $('#errMsg').text(data.error);
                            $('#errBox').show();

                        } else {
                            $('#userName').text(data.name_chinese);
                            $('#userNric').text(data.nric);
                            $('#userPosition').text(data.position);
                            $('#userChapter').text(data.first_name);
                            $('#reg_name').val(data.name_chinese);
                            
                            if(data.voted == 1) {
                                $('#votedRow').show();
                                $('#votedStatus').text('已領票 / 已投票');
                                if(data.vote_time && data.vote_time != '0000-00-00 00:00:00') {
                                    $('#voteTimeRow').show();
                                    $('#voteTime').text(data.vote_time);
                                } else {
                                    $('#voteTimeRow').hide();
                                }
                                $('#confirmVoteBtn').hide();
                            } else {
                                $('#votedRow').hide();
                                $('#voteTimeRow').hide();
                                $('#confirmVoteBtn').show();
                            }

                            $('#userModal').modal('show');
                            $('#errMsg').text('');
                            $('#errBox').hide();
                        }
                    }
                });
            }
        }, 300); // Wait for 0.3 seconds
    });

    $('#confirmVoteBtn').on('click', function() {
        const qrdata = $('#qrdata').val();
        $.ajax({
            url: 'ajax_log_voting',
            type: 'POST',
            dataType: 'json',
            data: { qrdata: qrdata },
            success: function(response) {
                if(response.error) {
                    alert(response.error);
                } else {
                    $('#qrdata').val(''); // Reset input box
                    $('#userModal').modal('hide'); // Close modal
                    $('#qrdata').focus(); // Focus input box
                    $('#msgBoxMsg').text($('#reg_name').val() + ' - 成功領票');
                    $('#reg_name').val('');
                    updateStats();
                }
            }
        });
    });

    function updateStats() {
        $.ajax({
            url: 'ajax_get_voting_stats',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#statsPersonal').text(data.personal);
                $('#statsGroup').text(data.group);
                $('#statsTotal').text(data.total);
            }
        });
    }

    $('#cancelBtn').on('click', function() {
        $('#qrdata').val(''); // Reset input box
        $('#userModal').modal('hide'); // Close modal
        $('#qrdata').focus(); // Focus input box
        $('#reg_name').val('');
    });
});
</script>
