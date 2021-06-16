<?php defined('INFOX') or die('No direct access allowed.');?>
<script type="text/babel">
    <?php include('common_react.php'); ?>
    
class CourseManager extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            courses_data: [],
            data_shared_subjects: [],
            auto_subject: ''
        }
        this.load_data = this.load_data.bind(this);
        this.add_new_course = this.add_new_course.bind(this);
        this.link_course = this.link_course.bind(this);
        this.unlink_course = this.unlink_course.bind(this);
    }
    componentDidMount() {
        this.load_data();
    }
    load_data = () => {
        axios.get('/courses').then((response) => {
            this.setState({
                courses_data: response.data.courses,
                data_shared_subjects: response.data.shared_subjects
            })
        })
    }
    onChangeHandler_auto_code(e) {
        this.setState({
            auto_subject: e.target.value.toUpperCase().replace(/\s+/g, '-')
        })
    }
    add_new_course = (e) => {
        e.preventDefault();
        $('#courseAddModal').hide();
        var data = $('#add_new_course').serializeObject();
        axios.post('courses?action=new', data).then((response) => {
            const current_courses = this.state.courses_data;
            current_courses.push(response.data)
            this.setState({
                courses_data: current_courses
            })
            $('#add_new_course')[0].reset();
            $('#courseAddModal').show().modal('hide');
            alertify.success('Data Added');
        }).catch((error) => {
            $('#courseAddModal').show();
        })
    }
    unlink_course = () => {
        var data = $('#course_linker').serializeObject();
        axios.post('/courses?action=unlink',data)
        .then((response)=>{
            this.load_data();
            $('#course_linker')[0].reset();
        })  
    }
    link_course = () => {
        var data = $('#course_linker').serializeObject();
        axios.post('/courses?action=link',data)
        .then((response)=>{
            window.location.reload();
            $('#course_linker')[0].reset();
        })
    }
    render() {
        return (<div>
            <div className="row row-eq-height">
                {this.state.courses_data.map(course => {
                    return (<div className="col-md-4">
                        <div className="box box-purple">
                            <div className="box-header">
                                <h3 className="box-title">{course.u_name}</h3>
                                <div className="box-tools pull-right">
                                    {course.active === 0 ? <button className="btn btn-xs btn-success mx-1">Active</button> : <button className="btn btn-xs btn-warning mx-1">Deactivate</button>}
                                    <button className="btn btn-xs btn-danger mx-1"><i className="fa fa-trash"></i></button>
                                </div>
                            </div>
                            <div className="box-body">
                                {course.u_code}
                                <p>{course.u_desc}</p>
                                <div style={{display: 'flex',flexFlow: 'row wrap'}}>
                                {course.subjects.map(subject => {
                                    if(subject.course_id !== course.u_id){
                                    return (<span class="label label-danger mx-1 mt-5">{subject.u_name} - {subject.u_code}</span>)
                                    }else{
                                    return (<span class="label label-info mx-1 mt-5">{subject.u_name}</span>)
                                    }
                                })}
                                </div>
                            </div>
                        </div>
                    </div>)
                })}
            </div>
            <button className="btn btn-sm btn-success mx-1" type="button" data-toggle="modal" data-target="#courseAddModal">Add New</button>
            <button className="btn btn-sm btn-success mx-1" onClick={() => {
                this.load_data()
            }}>Refresh</button>
            <div className="row mt-10">
            <div className="col-md-6">
                    <div className="box">
                        <div className="box-header">
                            <h5 className="text-bold">Shared Subjects Binder</h5>
                        </div>
                        <div className="box-body">
                        <form id="course_linker">
                            <div className="row text-right justify-content-center">
                                <div className="col-md-6">
                                    <select name="subject" required type="text" className="form-control" >
                                        <option value={''} selelected> Shared Subjects</option>
                                        {this.state.data_shared_subjects.map(shared_subject => {
                                            return (
                                                <option value={shared_subject.u_id}>{shared_subject.u_name} - [{shared_subject.u_code}]</option>
                                            )
                                        })}
                                    </select>
                                </div>
                                <div className="col-md-6">
                                    <select name="course" required type="text" className="form-control">
                                        <option value={''} selelected> Course</option>
                                        {this.state.courses_data.map(course => {
                                            return (
                                                <option value={course.u_id}>{course.u_name}({course.u_code})</option>
                                            )
                                        })}
                                    </select>
                                </div>
                                <div className="col-xs-12 mt-10">
                                    <a onClick={()=>{
                                        this.link_course()
                                    }} className="btn btn-info mx-1 btn-xs"><i className="fa fa-link"></i> Link</a>
                                    <a onClick={()=>{
                                        this.unlink_course()
                                    }} className="btn btn-danger mx-1 btn-xs"><i className="fa fa-link"></i> Unlink</a>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
            <div className="modal fade" id="courseAddModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="courseAddModalLabel" aria-hidden="true">
                <div className="modal-dialog  modal-dialog-centered">
                    <div className="modal-content">
                        <div className="modal-header">
                            <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 className="modal-title" id="courseAddModalLabel">Add new Course!</h4>
                        </div>
                        <div className="modal-body">
                            <form id="add_new_course" onSubmit={this.add_new_course}>
                                <div className="form-group">
                                    <input className="form-control" type="text" name="name" required placeholder="Course Name" onChange={this.onChangeHandler_auto_code.bind(this)} />
                                </div>
                                <div className="form-group">
                                    <input className="form-control" type="text" name="desc" required placeholder="Description" />
                                </div>
                                <div className="form-group">
                                    <input className="form-control" type="text" name="code" value={this.state.auto_subject} required pattern="[A-Z0-9-]*" readonly placeholder="Course code <UPPERCASE>" />
                                </div>
                                <div className="form-group">
                                    <button type="submit">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        )
    }
}
function App() {
    return (
        <div>
            <section className="content-header">
                <h1>Courses Manager <small>create/manage courses</small></h1>
                <ol className="breadcrumb">
                    <li><a href="#" className="text-capitalize"><i className="fa fa-dashboard"></i>Manager</a></li>
                    <li className="active">Cources/class/management</li>
                </ol>
            </section>
            <br />
            <section className="content container-fluid">
                <CourseManager />
            </section>
        </div>
    )
}
ReactDOM.render(<App />, rootElement);
</script>