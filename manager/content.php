<?php defined('INFOX') or die('No direct access allowed.'); ?>
<?php include('yasi_quill.php'); ?>
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
            input_subject_select: '',
            data_content: []

        }
        this.load_data = this.load_data.bind(this);
        this.CourseonChangeHandler = this.CourseonChangeHandler.bind(this);
        this.ContentonChangeHandler = this.ContentonChangeHandler.bind(this);
        this.fetch_data = this.fetch_data.bind(this);
        //EDITOR
        this.open_editor = this.open_editor.bind(this);
        this.close_editor = this.close_editor.bind(this);
    }
    CourseonChangeHandler(e) {
        this.setState({
            input_course_select: e.target.value,
            content_loading: true
        })
        this.fetch_data(e.target.value);
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
    open_editor = () => {
        this.setState({
            content_editor: true
        })
    }
    close_editor = () => {
        this.setState({
            content_editor: false
        })
    }
    componentDidMount() {
        this.load_data();
    }
    render() {
        return (
            <div>
                {this.state.content_editor === true ?
                    <div>
                        <div className="box">
                            <div className="box-header with-border">
                                <h3 className="box-title text-capitalize">Add new {this.state.content_type}</h3>

                                <div className="box-tools pull-right">
                                    <button type="button" className="btn btn-box-tool" onClick={() => {
                                        this.close_editor();
                                    }}>
                                        <i className="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div className="box-body">
                                <div className="row">
                                    <div className="col-md-6">
                                        <div className="form-group">
                                            <label>Title</label>
                                            <input className="form-control input-sm" placeholder="Example : Chapter 1 Introduction" />
                                        </div>
                                        <div className="form-group">
                                            <label>Description</label>
                                            <textarea className="form-control" rows="3" placeholder="Eg : Aim - Structure"></textarea>
                                        </div>

                                    </div>
                                    <div className="col-md-6">
                                        <div className="form-group">
                                            <div className="radio">
                                                <label>
                                                    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked />
                     Draft
                    </label>
                                            </div>
                                            <div className="radio">
                                                <label>
                                                    <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2" />
                      Publish
                    </label>
                                            </div>
                                        </div>
                                        <div className="form-group">
                                            <label>Published On</label>
                                            <input type="datetime-local" className="form-control" defaultValue={() => { return new Date().toJSON().slice(0, 19) }} />
                                        </div>
                                        <div className="form-group">
                                            <label>Visible Up To</label>
                                            <input type="datetime-local" className="form-control" />
                                        </div>
                                        <div className="form-group">
                                            <label>Visible in Classroom</label>
                                            <div className="radio">
                                                <label>
                                                    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked />
                     No
                    </label>
                                            </div>
                                            <div className="radio">
                                                <label>
                                                    <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2" />
                      Yes
                    </label>
                                            </div>

                                        </div>
                                        <input className="form-control input-sm" readOnly placeholder="yasi_quill-file-blob-url" id="yasi_quill-file-blob-url"/>
                                    </div>
                                </div>
                            </div>
                            <div className="box-footer">
                            </div>
                        </div>
                        <QuillEditor/>
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
                                    {
                                        this.state.data_content.length > 0 ?
                                            <div className="box box-solid">
                                                <div className="box-header with-border">
                                                    <h3 className="box-title">Filter</h3>
                                                    <div className="box-tools">
                                                        <button type="button" className="btn btn-box-tool" data-widget="collapse"><i className="fa fa-minus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div className="form-group">
                                                    <select className="form-control" value={this.state.input_subject_select}>
                                                        <option value={''} selelected> Choose a Course</option>
                                                        {this.state.data_courses.map(course => {
                                                            return (
                                                                <option value={course.u_id}>{course.u_name}({course.u_code})</option>
                                                            )
                                                        })}
                                                    </select>
                                                </div>
                                                <div className="box-body no-padding">
                                                    <ul className="nav nav-pills nav-stacked">
                                                        <li className="active"><a><i className="fa fa-check text-blue"></i> All</a></li>
                                                        <li><a><i className="fa fa-file text-blue"></i> Draft</a></li>
                                                        <li><a><i className="fa fa-newspaper-o text-green"></i> Published</a></li>
                                                        <li><a><i className="fa fa-trash text-light-red"></i> Trash</a></li>
                                                    </ul>
                                                </div>

                                            </div> : null
                                    }

                                </div>
                                {this.state.input_course_select !== '' ?
                                    <div className="col-md-9">
                                        <div className="box box-primary">
                                            <div className="box-header with-border">
                                                <h3 className="box-title text-capitalize">{this.state.content_type}</h3>

                                                <div className="box-tools pull-right">
                                                    <div className="has-feedback">
                                                        <input type="text" className="form-control input-sm text-capitalize" placeholder={"Search  " + this.state.content_type} />
                                                        <span className="glyphicon glyphicon-search form-control-feedback"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="box-body no-padding">
                                                <div className="mailbox-controls">

                                                    <button type="button" className="btn btn-default btn-sm checkbox-toggle"><i className="fa fa-square-o"></i>
                                                    </button>
                                                    <button className="btn btn-sm btn-primary mx-1" onClick={() => {
                                                        this.open_editor();
                                                    }}><i className="fa fa-plus"></i></button>
                                                    <div className="btn-group">
                                                        <button type="button" className="btn btn-default btn-sm"><i className="fa fa-trash-o"></i></button>
                                                        <button type="button" className="btn btn-default btn-sm"><i className="fa fa-reply"></i></button>
                                                        <button type="button" className="btn btn-default btn-sm"><i className="fa fa-share"></i></button>
                                                    </div>

                                                    <button type="button" className="btn btn-default btn-sm"><i className="fa fa-refresh"></i></button>
                                                    <div className="pull-right">
                                                        1-50/200
              <div className="btn-group">
                                                            <button type="button" className="btn btn-default btn-sm"><i className="fa fa-chevron-left"></i></button>
                                                            <button type="button" className="btn btn-default btn-sm"><i className="fa fa-chevron-right"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                {this.state.data_content.length > 0 ?
                                                    <div className="table-responsive mailbox-messages">
                                                        <table className="table table-hover table-striped">
                                                            <tbody>
                                                                <tr>
                                                                    <td><input type="checkbox" /></td>
                                                                    <td className="mailbox-star"><a><i className="fa fa-star text-yellow"></i></a></td>
                                                                    <td className="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                                                                    <td className="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                </td>
                                                                    <td className="mailbox-attachment"></td>
                                                                    <td className="mailbox-date">5 mins ago</td>
                                                                </tr>
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
                                            <div className="box-footer no-padding">
                                                <div className="mailbox-controls">
                                                    <button type="button" className="btn btn-default btn-sm checkbox-toggle"><i className="fa fa-square-o"></i>
                                                    </button>
                                                    <div className="btn-group">
                                                        <button type="button" className="btn btn-default btn-sm"><i className="fa fa-trash-o"></i></button>
                                                        <button type="button" className="btn btn-default btn-sm"><i className="fa fa-reply"></i></button>
                                                        <button type="button" className="btn btn-default btn-sm"><i className="fa fa-share"></i></button>
                                                    </div>

                                                    <button type="button" className="btn btn-default btn-sm"><i className="fa fa-refresh"></i></button>
                                                    <div className="pull-right">
                                                        1-50/200
              <div className="btn-group">
                                                            <button type="button" className="btn btn-default btn-sm"><i className="fa fa-chevron-left"></i></button>
                                                            <button type="button" className="btn btn-default btn-sm"><i className="fa fa-chevron-right"></i></button>
                                                        </div>

                                                    </div>

                                                </div>
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