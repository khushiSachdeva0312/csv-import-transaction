{include file=$tplVar.header}

{if $tplVar['error']}
    <div class="errorbox"><strong><span class="title">Error</span></strong><br>{$tplVar['error']}</div>
{/if}
{if $tplVar['success']}
    <div class="successbox"><strong><span class="title">Success</span></strong><br>{$tplVar['success']}</div>
{/if}
<div class="csv_page container">
    <div class="row">
        <div class="col-4">
            <div class="csv_file_upload">
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" value="true">
                    <div class="form-group">
                        <label>Choose CSV File:</label>
                        <input type="file" name="csvfile" id="fileToUpload" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Get Transactions" class="btn btn-primary" name="submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>