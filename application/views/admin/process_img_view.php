<script>

function rename(){
    var request = $.ajax({
        url: "process_img/rename_img",
        method: "POST",
        data: {
            'ori_name': $('#ori_name').val(),
            'new_name': $('#new_name').val(),
        }
    });

    request.done(function(msg){
        $("#status").html(msg);
    });
}

function generate_thumb(){
    var request = $.ajax({
        url: "process_img/generate_thumb",
        method: "POST",
        data: {
            'new_name': $('#new_name').val()
        }
    });

    request.done(function(msg){
        $("#thumb_name").val(msg);
        $("#thumb_name").focus().select();
    });
}
</script>


<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Image Process</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">

            <div class="panel panel-primary">
                
                <div class="panel-footer">
                    <div class='row'>
                        <div class='col-xs-3'>images/[filename]</div>
                        <div class='col-xs-5'>images/stories/[type]/[year]/[month]</div>
                        <div class='col-xs-4'>get thumbnail</div>
                    </div>
                    <div class='row'>
                        <div class='col-xs-3'>
                            <input type='text' id='ori_name' placeholder='original file name' />
                        </div>
                        <div class='col-xs-5'>
                            <input type='text' id='new_name' placeholder='Eg: fahui_20131025_boyeh.jpg' /> <a href='#' onclick='rename()'>Move</a><div id='status'></div>
                        </div>
                        <div class='col-xs-4'>
                            <input type='text' id='thumb_name' /> <a href='#' onclick='generate_thumb()'>Generate</a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>


    
    <!-- /.row -->
</div>