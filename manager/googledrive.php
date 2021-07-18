<?php defined('INFOX') or die('No direct access allowed.');?>
<script type="text/babel">
    <?php include('common_react.php'); ?>
    class VideoUploader extends React.Component {

constructor() {
    super();
    this.state = {
        selectedFile: '',
        videouploader: null
    }

    this.handleInputChange = this.handleInputChange.bind(this);
    this.submit = this.submit.bind(this);
}

handleInputChange(event) {
    const file = event.target.files[0];
    var pattern = "video.*";
    if (!file.type.match(pattern)) {
        alertify.error('Invalid format');
        $(event.target).val('')
        this.setState({
            selectedFile: '',
        });
        $('#img').hide();
        $('#img-label').show()
    } else {
        this.setState({
            selectedFile: event.target.files[0],
        });
        $('#img-label').hide();
        $('#img').show()
    }
}

submit = (e) => {
    e.preventDefault();
    const data = new FormData()
    const config = {
        onUploadProgress: (progressEvent) => {
            this.setState({ videouploader: progressEvent });
        }
    }
    console.log(this.state.selectedFile);
    data.append('file', this.state.selectedFile)
  try {
    navigator.wakeLock.request('screen');
  } catch (err) {
    console.log(`${err.name}, ${err.message}`);
  }
    axios.post('cloudstorage/video', data, config)
        .then(res => {
            this.setState({ videouploader: null });
            $(event.target).val('')
            window.open(`https://drive.google.com/file/d/${res.data.blob}/edit`);
            alertify.success('File Uploaded Please Refresh');
        }).catch((err) => {
            this.setState({ videouploader: null });
        })

}

render() {
    return (
        <div class="row">
                {this.state.videouploader === null ?
                    <form className="col-sm-6 col-md-4 col-lg-3 mb-20" onSubmit={this.submit}>
                        <div className="input-group input-group-sm text-center">
                            <input type="file" placeholder="Video Uploader" name="upload_file" accept="video/*" required onChange={this.handleInputChange} id="img" style={{ display: 'none' }} />
                            <label id="img-label" className="form-control input-sm bg-purple text-white" for="img">Choose Video</label>
                            <span className="input-group-btn">
                                <button type="submit" className="btn btn-sm btn-danger">upload</button>
                            </span>
                        </div>
                    </form> :
                    <div className="col-md-10  mb-20">
                        <div className="progress progress-xs">
                            <div className="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style={{ width: `${Math.floor((parseInt(this.state.videouploader.loaded) / parseInt(this.state.videouploader.total)) * 100)}%` }}>
                                <span className="sr-only">{this.state.videouploader.loaded}/{this.state.videouploader.total}% Complete</span>
                            </div>
                        </div>
                    </div>
                }
            </div>
    )
}
}
class FileManager extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            files: []
        }
        this.load_media = this.load_media.bind(this);
    }
    load_media = () => {
        axios.get("cloudstorage").then((media) => {
            this.setState({ files: media.data })
        })
    }
    componentDidMount() {
        this.load_media();
    }
    render() {
        return (
            <div className="box box-warning">
                <div className="box-header with-border">
                    <h3 className="box-title">File Manager</h3>
                    <div className="box-tools pull-right">
                        <button type="button" className="btn btn-box-tool" onClick={() => {
                            this.load_media();
                        }}><i className="fa fa-refresh"></i>
                        </button>
                    </div>
                </div>
                <div className="box-body">
                    <div className="row">
                        {this.state.files.map(media => {
                            return (
                                <div className="col-xs-12 col-sm-6 col-md-4 col-lg-3 text-center">
                                    <a data-toggle="tooltip" title={new Date(media.createdAt).toString()}>
                                    <h1>{media.type === 'cloud-video' ? <i className="fa fa-file-video-o"></i> : <span>{media.type === 'cloud-audio' ? <i className="fa fa-file-audio-o"></i> : <i className="fa fa-file"></i>}</span>}</h1>
                                    {media.name}</a>
                                        <p><a href={`https://drive.google.com/file/d/${media.blob}`} target="_blank" className="label label-sm label-success">view</a></p>
                                </div>
                            )
                        })}
                    </div>
                </div>
            </div>
        )
    }
}
class AudioRecorder extends React.Component {
    constructor() {
        super();
        this.state = {
            record_state: 'sleep',
            stream: null,
            blob: null,
            audio_uploader: null,
        }
        this.startRecording = this.startRecording.bind(this);
        this.stopRecording = this.stopRecording.bind(this);
        this.record = this.record.bind(this);
        this.recorder = null;
        this.audio_chunks = [];
        this.audio_blob = null
        this.audio_source = null
        this.audio_form_data = new FormData();
    }
    startRecording = () => {
        try {
            this.audio_form_data.delete('file');
        } catch (e) {
            console.log(e);
        }
        navigator.mediaDevices.getUserMedia({
            audio: true,
            video: false
        })
            .then(stream => {
                this.record(stream);
                this.recorder.start();
            })
            .catch(function (error) {
                alert(error);
            });
    }
    stopRecording = () => {
        this.recorder.stop();
        this.state.stream.getTracks().forEach(function (track) {
            if (track.readyState == 'live') {
                track.stop();
            }
        });
        this.setState({
            record_state: 'uploading',
            stream: null,
        });
    }
    upload = () => {
        const config = {
            onUploadProgress: (progressEvent) => {
                this.setState({ audio_uploader: progressEvent });
            }
        }
        axios.post('cloudstorage/audio', this.audio_form_data, config)
            .then(res => {
                this.setState({ record_state: 'sleep',blob:true });
                alertify.success('File Uploaded Please Refresh');
            }).catch((err) => {
                this.setState({ videouploader: null });
            })
    }
    record = (stream) => {
        this.setState({
            'stream': stream,
            record_state: 'recording',
            blob: null
        });
        this.recorder = new MediaRecorder(this.state.stream);
        this.recorder.ondataavailable = e => {
            this.audio_chunks.push(e.data);
            console.log(e);
            if (this.recorder.state == "inactive") {
                this.audio_blob = new Blob(this.audio_chunks, {
                    type: 'audio/wav'
                });
                this.audio_form_data.append("file", this.audio_blob, `${new Date().toString()}.wav`);
                this.audio_source = URL.createObjectURL(this.audio_blob);
                this.upload();
            }
        }
    }
    render() {
        return (
            <div className="row">
            <div className="col-sm-6 col-md-6 col-lg-4 mb-20">
                {this.state.record_state === 'sleep' ? <button onClick={() => {
                    this.startRecording();
                }} className="btn btn-danger btn-lg"><i className="fa fa-microphone"></i></button> : null}
                {this.state.record_state === 'recording' ? <button onClick={() => {
                    this.stopRecording();
                }} className="btn btn-danger btn-lg blink_me"><i className="fa fa-stop"></i></button> : null}
                {this.state.record_state === 'uploading' && this.state.audio_uploader !== null ? <div className="progress progress-xs">
                    <div className="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style={{ width: `${Math.floor((parseInt(this.state.audio_uploader.loaded) / parseInt(this.state.audio_uploader.total)) * 100)}%` }}>
                        <span className="sr-only">{this.state.audio_uploader.loaded}/{this.state.audio_uploader.total}% Complete</span>
                    </div>
                </div>
                    : null}
                {this.state.blob !== null ? <audio className="btn btn-info mx-1" controls><source src={this.audio_source}></source></audio> : null}
            </div>
            </div>
        )
    }
}
    function App() {
    return (
        <div>
            <section className="content-header">
                <h1>M<small>Optional description</small></h1>
                <ol className="breadcrumb">
                    <li><a href="#" className="text-capitalize"><i className="fa fa-dashboard"></i>Admin</a></li>
                    <li className="active">Cources/class/management</li>
                </ol>
            </section>
            <br/>
            <section className="content container-fluid">
           <VideoUploader/>
           <AudioRecorder/>
           <FileManager/>
            </section>
        </div>
    )
}
ReactDOM.render(<App />,rootElement);
</script>