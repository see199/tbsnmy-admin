<div class="container mt-5">
    <h2>會員大會 QR 簽到</h2>
    <div class="alert alert-success alert-dismissible fade in" role="alert" id='msgBox'>
      <strong>最新簽到：</strong><span id='msgBoxMsg'></span>
    </div>
    <input type="text" id="qrdata" class="form-control" placeholder="QR Scanner">
    <input type="hidden" id="reg_name" />

    <hr />
    <!-- Div container for the iframe with mt-5 -->
    <div class="mt-5">
        <iframe src="<?= base_url('agm/stats'); ?>" style="width: 100%; height: 300px; border: none;" scrolling="no" ></iframe>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="userModalLabel">會員資料</h3>
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
                  </tbody>
              </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="signInBtn"><i class="fa-solid fa-pen-to-square"></i> 簽到</button>
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
                            $('#userModal').modal('show');
                            $('#errMsg').text(data.error);
                            $('#errBox').show();

                        } else {
                            $('#userName').text(data.name_chinese);
                            $('#userNric').text(data.nric);
                            $('#userPosition').text(data.position);
                            $('#userChapter').text(data.first_name);
                            $('#reg_name').val(data.name_chinese);
                            $('#userModal').modal('show');
                            $('#errMsg').text('');
                            $('#errBox').hide();
                        }
                    }
                });
            }
        }, 300); // Wait for 0.3 seconds
    });

    $('#signInBtn').on('click', function() {
        const qrdata = $('#qrdata').val();
        $.ajax({
            url: 'ajax_log_attendance',
            type: 'POST',
            dataType: 'json',
            data: { qrdata: qrdata },
            success: function(response) {
                $('#qrdata').val(''); // Reset input box
                $('#userModal').modal('hide'); // Close modal
                $('#qrdata').focus(); // Focus input box
                $('#msgBoxMsg').text($('#reg_name').val());
                $('#reg_name').val('');
            }
        });
    });

    $('#cancelBtn').on('click', function() {
        $('#qrdata').val(''); // Reset input box
        $('#userModal').modal('hide'); // Close modal
        $('#qrdata').focus(); // Focus input box
        $('#reg_name').val('');
    });
});
</script>