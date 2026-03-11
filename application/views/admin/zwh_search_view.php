<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1" style="max-width: 80%; margin-left: auto; margin-right: auto; float: none;">
            <h1 class="page-header"><?= $menu_title; ?></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 col-lg-offset-1" style="max-width: 80%; margin-left: auto; margin-right: auto; float: none;">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Content Search & View
                </div>
                <div class="panel-body">
                    <form class="form-inline" onsubmit="return false;">
                        <div class="form-group">
                            <label for="search_id">ID:</label>
                            <input type="text" class="form-control" id="search_id" placeholder="Enter ID (e.g. 3770)">
                        </div>
                        
                        <div class="form-group" style="margin-left: 20px;">
                            <label class="radio-inline">
                                <input type="radio" name="content_type" value="guidem" checked> 師尊開示
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="content_type" value="news"> 新聞
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="content_type" value="eventnotice"> 華光
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="content_type" value="dynamics"> 互動
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="content_type" value="message"> 最新訊息
                            </label>
                        </div>

                        <button type="button" class="btn btn-primary" onclick="loadContent()" style="margin-left: 20px;">Load Content</button>
                    </form>
                    <div style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px;">
                        <p class="text-muted">
                            <i class="fa fa-info-circle"></i> <b>使用說明：</b>本工具用於快速定位並編輯「真佛宗官方網站 (ch.tbsn.org)」的各類內容。輸入項目 ID 並選擇類別後，按下 Enter 或點擊按鈕即可直接開啟編輯頁面。
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function loadContent() {
    var id = document.getElementById('search_id').value.trim();
    if (!id) {
        alert('Please enter an ID');
        return;
    }

    var type = document.querySelector('input[name="content_type"]:checked').value;
    var url = '';

    switch(type) {
        case 'guidem':
            url = 'https://ch.tbsn.org/control/guidem/edit_page_html/guidem_' + id + '_ch.html';
            break;
        case 'news':
            url = 'https://ch.tbsn.org/control/news/edit/' + id + '.html';
            break;
        case 'eventnotice':
            url = 'https://ch.tbsn.org/control/eventnotice/edit_page_html/eventnotice_' + id + '_ch.html';
            break;
        case 'dynamics':
            url = 'https://ch.tbsn.org/control/dynamics/edit_page_html/dynamics_' + id + '_ch.html';
            break;
        case 'message':
            url = 'https://ch.tbsn.org/control/message/edit/' + id + '.html';
            break;
    }

    if (url) {
        window.open(url, '_blank');
    }
}

// Add event listener for Enter key in input
document.getElementById('search_id').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        loadContent();
    }
});
</script>
