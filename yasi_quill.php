<?php defined('INFOX') or die('No direct access allowed.'); ?>
<link href="/cdn/quill/quill.snow.css" rel="stylesheet" />
<link rel="stylesheet" href="/cdn/quill/katex.min.css" />
<script src="/cdn/quill/katex.min.js"></script>
<script src="/cdn/quill/quill.js"></script>
<script>
$(document).ready(function() {
    var base_url = "<?php echo (INFOX_CONSOLE_URL); ?>";
    window.base_url = base_url;
    $.ajaxSetup({
        headers: {
            'Authorization': '<?php echo (INFOX_TOKEN); ?>'
        }
    });
    $.ajaxSetup({
        beforeSend: function(xhr, options) {
            options.url = base_url + options.url;
        }
    });
});
window.yasi_quill_compute = function(){
    const size =new TextEncoder().encode(JSON.stringify(window.yasi_quill.getContents())).length
    const kiloBytes = size / 1024;
    const megaBytes = kiloBytes / 1024;
    return (kiloBytes.toFixed(2))
}
</script>
<style>
.ql-editor {
    min-height: 60vh;
}
</style>
<script type="text/babel">
    <?php include('common_react.php'); ?>
    class QuillEditor extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            cloud: []
        }
        this.upload = this.upload.bind(this);
        this.read_data = this.read_data.bind(this);
        this.cloud_data = this.cloud_data.bind(this);
    }
    componentDidMount() {
        var toolbarOptions = [
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            [{ 'font': [] }],
            ['bold', 'italic', 'underline', 'strike'],// toggled buttons
            [{ 'color': [] }, { 'background': [] }],// dropdown with defaults from theme
            [{ 'align': [] }],
            ['blockquote', 'code-block'],
            //[{ 'header': 1 }, { 'header': 2 }],// custom button values
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            [{ 'script': 'sub' }, { 'script': 'super' }],// superscript/subscript
            [{ 'indent': '-1' }, { 'indent': '+1' }],// outdent/indent
            [{ 'direction': 'rtl' }],// text direction

            ['link', 'image', 'video', 'formula'],
            ['clean'],// remove formatting button
            [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
        ];
        window.yasi_Delta = Quill.import('delta');
        window.yasi_quill = new Quill('#yasi_quill-editor-container', {
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'Start writing Articles',
            theme: 'snow'
        });
        window.yasi_quill.on('text-change', function (delta) {
            $('#yasi_quill-filesize').text(window.window.yasi_quill_compute());
        });
        this.read_data();
    }

    upload = () => {
        var quill_data = JSON.stringify(window.yasi_quill.getContents())
        var datas = quill_data.replaceAll(window.base_url + '/demo', 'dvideoicontentyprovidera');
        var property = new Blob([datas], { type: "application/json" });
        var form_data = new FormData();
        var date_data = new Date().toJSON()
        form_data.append("file", property, date_data);
        $.ajax({
            url: '/upload',
            method: 'POST',
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                console.log(data);
                $('#yasi_quill-msg').html(`Data uploaded : ${data}`);
                $('#yasi_quill-file-blob-url').val(data);
            },
            error: function (err) {
                $('#yasi_quill-msg').html(err);
            }
        });
    }
    read_data = () => {
        $.getJSON('/file/' + $('#yasi_quill-file-blob-url').val(), function (data) {
            let server_data = JSON.stringify(data.ops);
            let quill_data = server_data.replaceAll('dvideoicontentyprovidera', window.base_url + '/demo')
            window.yasi_quill.setContents(JSON.parse(quill_data));
        });
    }
    cloud_data = () => {
      axios.get('/cloudstorage').then((response)=>{
            this.setState({ cloud: response.data });
        });
    }
    render() {
        return (
            <div className="box box-danger">
                <div className="box-header with-border">
                    <div className="row mb-10">
                        <div className="col-md-6">
                            <label className="label label-primary">File Size <span id="yasi_quill-filesize">0.00</span>KB</label>
                        </div>
                        <div className="col-md-6">
                            <div id="yasi_quill-msg"></div>
                            <div className="pull-right">
                                <button className="btn btn-success mx-1 btn-md" onClick={() => { this.upload(); }}> <i className="fa fa-cloud-upload"></i> Upload</button>
                                <button className="btn btn-primary mx-1 btn-md" onClick={() => { this.read_data(); }}><i className="fa fa-cloud-download"></i> Read</button>
                            </div>
                        </div>

                    </div>
                </div>
                <div className="box-body">
                    <div id="yasi_quill-editor-container" style={{ height: '60vh' }}>
                    </div>

                </div>
                <div className="box-footer">
                    <button className="btn btn-info mx-10 mb-5 btn-xs" onClick={()=>{ this.cloud_data();}} type="button" data-toggle="modal" data-target="#cloudStorageModal"> <i className="fa fa-cloud-upload"></i> Cloud Storage</button>
                    <button onClick={() => {
                        var quill = window.yasi_quill;
                        var index = quill.getLength();
                        quill.insertEmbed(index, 'image', 'https://data.apkshub.com/60/com.whatsapp/2.20.194.8/icon.png');
                        //quill.setSelection(index, index+1);
                        //quill.theme.tooltip.edit('link', 'https://data.apkshub.com/60/com.whatsapp/2.20.194.8/icon.png');
                        //quill.theme.tooltip.save();
                        quill.format('align', 'center');
                    }} className="btn btn-xs btn-success  mb-5 mx-1"><i className="fa fa-whatsapp"></i> Insert Whatsapp</button>
                    <div className="pull-right text-black text-xs">&copy; mohammed-yasim</div>
                </div>
                <div className="modal fade" id="cloudStorageModal" tabindex="-1" role="dialog">
                    <div className="modal-dialog modal-lg" role="document">
                        <div className="modal-content">
                            <div className="modal-header">
                                <button type="button" className="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 className="modal-title" id="myModalLabel">Modal title</h4>
                            </div>
                            <div className="modal-body">
                                {this.state.cloud.length > 0 ? <div className="row">
                                    {this.state.cloud.map(media => {
                                        return (
                                            <div className="col-xs-12 col-sm-6 col-md-4 col-lg-3 text-center" style={{marginBottom:'10px'}}>
                                                <a data-toggle="tooltip" title={new Date(media.createdAt).toString()}>
                                                    <iframe src={`https://drive.google.com/file/d/${media.blob}/preview`} style={{ width: '100%' }}></iframe>
                                                    {media.name}
                                                    </a>
                                                    <button onClick={() => {
                        var quill = window.yasi_quill;
                        var index = quill.getLength();
                        quill.insertEmbed(index, 'video', `https://drive.google.com/file/d/${media.blob}/preview`,'silent');
                        quill.setSelection(index, index + 1);
                        quill.format('align', 'center');
                        $('#cloudStorageModal').modal('hide')
                    }} className="btn btn-sm btn-info">insert</button>
                                            </div>
                                        )
                                    })}
                                </div> : null}
                            </div>
                            <div className="modal-footer">
                                <button type="button" className="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}
</script>