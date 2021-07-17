<?php defined('INFOX') or die('No direct access allowed.'); ?>
<?php include('yasi_quill.php'); ?>
<style>
div > div[data-tooltip="Pop-out"] {
    display:none !important;
}
</style>
<script type="text/babel">
    <?php include('common_react.php'); ?>
  
class ContentManager extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            content_loading: true,
            content_editor: false,
            content_type_array: [{
                name: 'Assignments',
                value: 'assignments',
                icon: ''
            },
            {
                name: 'Exams',
                value: 'exams',
                icon: ''
            }, {
                name: 'Topics',
                value: 'topics',
                icon: ''
            }],
            content_type: 'topics',
            data_courses: [],
            input_course_select: '',
            data_subjects: [],
            data_content: [],
            //EDITOR
            editor_subject: '',
            editor_method: '',
            editor_data : null,
            //Filter
            subject_filter:''
        }
        this.load_data = this.load_data.bind(this);
        this.CourseonChangeHandler = this.CourseonChangeHandler.bind(this);
        this.ContentonChangeHandler = this.ContentonChangeHandler.bind(this);
        this.fetch_data = this.fetch_data.bind(this);
        //EDITOR
        this.open_editor = this.open_editor.bind(this);
        this.close_editor = this.close_editor.bind(this);
        this.editor_subject_handle = this.editor_subject_handle.bind(this);
        this.editor_submit = this.editor_submit.bind(this);
        this.SubjectFilterHandler = this.SubjectFilterHandler.bind(this);
        this.content_action = this.content_action.bind(this);
    }
    CourseonChangeHandler(e) {
        this.setState({
            input_course_select: e.target.value,
            subject_filter : '',
            content_loading: true
        })
        this.fetch_data(e.target.value);
    }
    
    SubjectFilterHandler(e) {
        this.setState({
            subject_filter:e.target.value,
            editor_subject:e.target.value
        })
    }
    
    ContentonChangeHandler(content_type) {
        this.setState({
            content_type: content_type,
            content_loading: true
        })
        this.fetch_data(this.state.input_course_select, content_type);
    }
    load_data = () => {
        axios.get('/contents').then((response) => {
            this.setState({
                data_courses: response.data.courses,
                content_loading: false
            })
        });
    }
    fetch_data = (course_id, content_type = this.state.content_type) => {
        if (course_id !== '' && content_type !== '') {
            axios.get(`/content/${course_id}/${content_type}`).then((response) => {
                this.setState({
                    data_subjects: response.data.subjects,
                    data_content: response.data.content,
                    content_loading: false
                })
            }).catch((err) => {
                this.setState({
                    content_loading: false
                })
            });
        } else {
            this.setState({
                content_loading: false
            })
        }
    }
    open_editor = (method="add",data=null) => {
        this.setState({
            content_editor: true,
            editor_method: method,
            'editor_data':data
        })
    }
    close_editor = () => {
        this.setState({
            content_editor: false,
            editor_method: '',
            editor_subject: '',
        })
    }
    editor_subject_handle(e) {
        this.setState({
            editor_subject: e.target.value,
        });
    }
    editor_submit = (e) => {
        e.preventDefault();
        //$('#employeeModal').hide();
        var data = $('#editor_form').serializeObject();
        axios.post('/contents?action=' + this.state.editor_method + '-' + this.state.content_type, data).then((response) => {
            if(this.state.editor_method === 'add'){
            const data_content = this.state.data_content;
            data_content.push(response.data)
            this.setState({
                data_content: data_content
            })
            alertify.success('Data Added');
            this.close_editor();
            }
            $('#editor_form')[0].reset();
            if(this.state.editor_method === 'edit'){
                alertify.success('Edit Saved');
                this.close_editor();
                this.fetch_data(this.state.input_course_select);
            }
        }).catch((error) => {
            /// $('#employeeModal').show();
        })
    }
    componentDidMount() {
        this.load_data(this.state.editor_method);
    }
    content_action = (id,course,action) => {
        axios.post(`/content?action=${action}-${this.state.content_type}`,{id:id}).then((response)=>{
            this.setState({
            input_course_select: course,
            content_loading: true
        })
        this.fetch_data(course);
        })
    }
    render() {
        return (
            <div>
                {this.state.content_editor === true && this.state.editor_method !== '' ?
                    <div>
                        <div className="box">
                            <div className="box-header with-border">
                                <h3 className="box-title text-capitalize">{this.state.editor_method} {this.state.content_type}</h3>

                                <div className="box-tools pull-right">
                                    <button type="button" className="btn btn-box-tool" onClick={() => {
                                        this.close_editor();
                                    }}>
                                        <i className="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div className="box-body">

                                {
                                    this.state.data_subjects.length > 0 && this.state.editor_method === 'add' ?
                                        <div className="mb-10">
                                            <select className="form-control" value={this.state.editor_subject} onChange={this.editor_subject_handle.bind(this)}>
                                                <option value={''} selelected>Choose Subject</option>
                                                {this.state.data_subjects.map(subject => {
                                                    return (
                                                        <option value={subject.u_id}>{subject.u_name}({subject.u_code})</option>
                                                    )
                                                })}
                                            </select>
                                        </div> : null
                                }
                                {this.state.editor_method === 'add' ?
                                <form id="editor_form" onSubmit={this.editor_submit}>
                                    <div className="row">
                                        <div className="col-md-6">
                                            <div className="form-group">
                                                <label>Title</label>
                                                <input type="text" className="form-control input-sm" placeholder="Example : Chapter 1 Introduction" required name="title" />
                                            </div>
                                            <div className="form-group">
                                                <label>Description</label>
                                                <textarea className="form-control" rows="3" placeholder="Eg : Aim - Structure" required name="desc"></textarea>
                                            </div>
                                            <div className="form-group">
                                                <div className="radio">
                                                    <label lassName="mx-1 text-bold">Status : </label>
                                                    <label className="mx-1">
                                                        <input type="radio" name="published" require value="0" checked />Draft</label>
                                                    <label className="mx-1">
                                                        <input type="radio" name="published" required value="1" />Publish</label>
                                                </div>
                                            </div>
                                            <div className="form-group">
                                                <div className="radio">
                                                    <label lassName="mx-1 text-bold">After Publish Visibility : </label>
                                                    <label className="mx-1">
                                                        <input type="radio" name="visible" required value="0" checked />Yes </label>
                                                    <label className="mx-1">
                                                        <input type="radio" name="visible" value="1" required />No</label>
                                                </div>

                                            </div>
                                        </div>
                                        <div className="col-md-6">
                                            <div className="form-group">
                                                <label>Published On</label>
                                                <input type="datetime-local" className="form-control" required name="pub_date" />
                                            </div>
                                            <div className="form-group">
                                                <label>Visible Up To</label>
                                                <input type="datetime-local" className="form-control" required name="exp_date" />
                                            </div>
                                            <input type="text" className="form-control input-sm non-input"  placeholder="yasi_quill-file-blob-url" id="yasi_quill-file-blob-url" name="blobdata" required />
                                            <input  type="text" className="form-control input-sm non-input" readOnly placeholder="course-id" required name="course_id" value={this.state.input_course_select} />
                                            <input type="text"  className="form-control input-sm non-input" placeholder="subject-id" required name="subject_id" value={this.state.editor_subject} />
                                        </div>
                                    </div>
                                    <div className="col-xs-12">
                                        <button className="btn btn-success text-capitalize" type="submit">{this.state.editor_method}</button>   
                                    </div>
                                </form>
                                :null}
                                {this.state.editor_method === 'edit' ?
                                <form id="editor_form" onSubmit={this.editor_submit}>
                                <input type="hidden" value={this.state.editor_data.u_id} name="u_id" />
                                    <div className="row">
                                        <div className="col-md-6">
                                            <div className="form-group">
                                                <label>Edit Title</label>
                                                <input type="text" className="form-control input-sm" placeholder="Example : Chapter 1 Introduction" required name="title" defaultValue={this.state.editor_data.u_title} />
                                            </div>
                                            <div className="form-group">
                                                <label>Description</label>
                                                <textarea className="form-control" rows="3" placeholder="Eg : Aim - Structure" required name="desc">{this.state.editor_data.u_desc}</textarea>
                                            </div>
                                            <div className="form-group">
                                            {this.state.editor_data.expiry === 0 ?
                                                <div className="radio">
                                                    <label lassName="mx-1 text-bold">After Publish Visibility : </label>
                                                    <label className="mx-1">
                                                        <input type="radio" name="visible" required value="0" checked />Yes </label>
                                                    <label className="mx-1">
                                                        <input type="radio" name="visible" value="1" required />No</label>
                                                </div>
                                                :
                                                <div className="radio">
                                                    <label lassName="mx-1 text-bold">After Publish Visibility : </label>
                                                    <label className="mx-1">
                                                        <input type="radio" name="visible" required value="0"  />Yes </label>
                                                    <label className="mx-1">
                                                        <input type="radio" name="visible" value="1" required checked />No</label>
                                                </div>
                                            }
                                            </div>
                                        </div>
                                        <div className="col-md-6">
                                            <div className="form-group">
                                                <label>Published On</label>
                                                <input type="datetime-local" className="form-control" required name="pub_date" defaultValue={new Date((new Date(this.state.editor_data.pub_date ).getTime() - new Date(this.state.editor_data.pub_date ).getTimezoneOffset() * 60000)).toISOString().slice(0,16)} />
                                            </div>
                                            <div className="form-group">
                                                <label>Visible Up To</label>
                                                <input type="datetime-local" className="form-control" required name="exp_date" defaultValue={ new Date((new Date(this.state.editor_data.exp_date).getTime() - new Date(this.state.editor_data.exp_date).getTimezoneOffset() * 60000)).toISOString().slice(0,16)} />
                                            </div>
                                            <input type="text" className="form-control input-sm non-input"  placeholder="yasi_quill-file-blob-url" id="yasi_quill-file-blob-url" name="blobdata" required  defaultValue={this.state.editor_data.u_blob}  />
                                        </div>
                                    </div>
                                    <div className="col-xs-12">
                                        <button className="btn btn-success text-capitalize" type="submit">Save {this.state.editor_method}</button>   
                                    </div>
                                </form>
                                :null}
                            </div>
                            <div className="box-footer">
                            </div>
                        </div>
                        <QuillEditor />
                    </div>
                    : <div>
                        {this.state.content_loading === true ? <div className="col-xs-12 mt-10 mb-10 text-center"><div className="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div> :
                            <div className="row">
                                <div className="col-md-3">
                                    {
                                        this.state.data_courses.length > 0 ?
                                            <div className="form-group">
                                                <select className="form-control" value={this.state.input_course_select} onChange={this.CourseonChangeHandler.bind(this)}>
                                                    <option value={''} selelected> Choose a Course</option>
                                                    {this.state.data_courses.map(course => {
                                                        return (
                                                            <option value={course.u_id}>{course.u_name}({course.u_code})</option>
                                                        )
                                                    })}
                                                </select>
                                            </div>
                                            :
                                            <div className="form-group">
                                                <select className="form-control">
                                                    <option value={''} selelected> Please Add Course first </option>
                                                </select>
                                            </div>
                                    }
                                    {this.state.input_course_select === '' ? null :
                                        <div className="box box-solid">
                                            <div className="box-header with-border">
                                                <h3 className="box-title">Content Type</h3>

                                                <div className="box-tools">
                                                    <button type="button" className="btn btn-box-tool" data-widget="collapse"><i className="fa fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div className="box-body no-padding">
                                                <ul className="nav nav-pills nav-stacked">
                                                    {this.state.content_type_array.map(content_type => {
                                                        if (this.state.content_type === content_type.value) {
                                                            return (
                                                                <li className="active"><a><i className={content_type.icon}></i>{content_type.name}</a></li>
                                                            )
                                                        }
                                                        else {
                                                            return (
                                                                <li><a onClick={() => { this.ContentonChangeHandler(content_type.value); }}><i className={content_type.icon}></i>{content_type.name}</a></li>
                                                            )
                                                        }
                                                    })}
                                                </ul>
                                            </div>
                                        </div>
                                    }
                                    {this.state.input_course_select !== '' ?
                                        <div>
                                            <div className="box box-solid">
                                                <div className="box-header with-border">
                                                    <h3 className="box-title">Subject Filter</h3>
                                                    <div className="box-tools">
                                                        <button type="button" className="btn btn-box-tool" data-widget="collapse"><i className="fa fa-minus"></i>
                                                        </button>
                                                    </div>
                                                </div>
    
                                                <div className="box-body no-padding">
                                                <div className="form-group">
                                                    <select className="form-control" value={this.state.subject_filter} onChange={this.SubjectFilterHandler.bind(this)}>
                                                        <option value={''} selelected>All</option>
                                                        {this.state.data_subjects.map(subject => {
                                                            return (
                                                                <option value={subject.u_id}>{subject.u_name}({subject.u_code})</option>
                                                            )
                                                        })}
                                                    </select>
                                                </div>
                                                
                                                </div>

                                            </div> 
                                            
                                            </div>: null
                                    }

                                </div>
                                {this.state.input_course_select !== '' ?
                                    <div className="col-md-9">
                                        <div className="box box-primary">
                                            <div className="box-header with-border">

                                                <h3 className="box-title text-bold text-uppercase">{this.state.content_type}</h3>

                                                <div className="box-tools pull-right">
                                                    <div className="has-feedback">
                                                        <input type="text" className="form-control input-sm text-capitalize" placeholder={"Search  " + this.state.content_type} />
                                                        <span className="glyphicon glyphicon-search form-control-feedback"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="box-body no-padding">

                                                {this.state.data_content.length > 0 ?
                                                    <div className="table-responsive mailbox-messages">
                                                        <table className="table table-hover table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Title &amp; Description</th>
                                                                    <th>Status</th>

                                                                    <th>Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                {this.state.data_content.filter(
                                                                    data_set => data_set.subject_id === this.state.subject_filter || !this.state.subject_filter
                                                                ).map(data_set => {
                                                                    return (
                                                                        <tr>
                                                                            <td>
                                                                                {data_set.u_title}<br />
                                                                                <p style={{overflow:'hidden',textOverflow: 'ellipsis',whiteSpace: 'nowrap',width:'350px',height:'auto'}}>{data_set.u_desc}</p></td>
                                                                            <td style={{ fontSize: '80%' }}>
                                                                                <div>
                                                                                    {data_set.published === 0 ? <label>DRAFT</label> : <span>
                                                                                        {new Date(data_set.pub_date) > new Date() ? <span>
                                                                                            Publish on : {new Date(data_set.pub_date).toString()}
                                                                                        </span> : <span>Published :  {new Date(data_set.pub_date).toString()}</span>}
                                                                                    </span>}
                                                                                </div>
                                                                                <div>
                                                                                    {data_set.expiry === 0 ?
                                                                                        <div>Visible</div>
                                                                                        : <span>
                                                                                            {new Date(data_set.exp_date).toString()}
                                                                                        </span>}
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <button className="btn btn-xs btn-info mx-1" onClick={()=>{
                                                                                    this.open_editor('edit',data_set);
                                                                                }}><i className="fa fa-pencil"></i></button>
                                                                                <button className="btn btn-xs btn-danger mx-1" onClick={()=>{
                                                                                    if(window.confirm("Do you want to delete?")){this.content_action(data_set.u_id,data_set.course_id,'delete')}
                                                                                }}><i className="fa fa-trash"></i></button>
                                                                                {data_set.published === 0 ? <button onClick={()=>{
                                                                                    this.content_action(data_set.u_id,data_set.course_id,'publish')
                                                                                }} className="btn btn-xs btn-success mx-1">Publish</button> :
                                                                                <button className="btn btn-xs btn-warning mx-1" onClick={()=>{
                                                                                    this.content_action(data_set.u_id,data_set.course_id,'unpublish')
                                                                                }}>Un Publish</button>}
                                                                            </td>
                                                                        </tr>
                                                                    )
                                                                })
                                                                }
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    : <div className="error-page">
                                                        <div className="error-content">
                                                            <h3><i className="fa fa-warning text-yellow"></i> No Data!</h3>
                                                            <p>
                                                            </p>
                                                        </div>
                                                    </div>}
                                            </div>
                                            <div className="box-footer no-padding text-center">
                                            {this.state.data_subjects.length>0 && this.state.data_subjects !== null ?
                                                <div className="text-center mt-5 mb-5">
                                                    <button className="btn btn-xs btn-primary mx-1 text-capitalize" onClick={() => {
                                                        this.open_editor();
                                                    }}><i className="fa fa-plus"></i> Add New {this.state.content_type}</button>
                                                </div>
                                                :<h5 className="text-center"><i className="fa fa-warning text-yellow"></i>Please Add Subject first!</h5>}
                                            </div>
                                        </div>
                                    </div>
                                    : null}
                            </div>}
                    </div>}
            </div>)
    }
}
function App() {
    //ContentManager
    return (
        <div>
            <section className="content-header">
                <h1>Content Manager<small></small></h1>
                <ol className="breadcrumb">
                    <li><a className="text-capitalize"><i className="fa fa-dashboard"></i>Content Manager</a></li>
                </ol>
            </section>
            <br />
            <section className="content container-fluid">
                <ContentManager />

            </section>
        </div>
    )
}
ReactDOM.render(<App />, rootElement);
</script>
<!-----
<div className="box box-solid">
                                                <div className="box-header with-border">
                                                    <h3 className="box-title">Filter</h3>
                                                    <div className="box-tools">
                                                        <button type="button" className="btn btn-box-tool" data-widget="collapse"><i className="fa fa-minus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div className="box-body no-padding">
                                                    <ul className="nav nav-pills nav-stacked">
                                                        <li className="active"><a><i className="fa fa-check text-blue"></i> All</a></li>
                                                        <li><a><i className="fa fa-file text-blue"></i> Draft</a></li>
                                                        <li><a><i className="fa fa-newspaper-o text-green"></i> Published</a></li>
                                                        <li><a><i className="fa fa-trash text-light-red"></i> Trash</a></li>
                                                    </ul>
                                                </div>

                                            </div>
                                        ---->