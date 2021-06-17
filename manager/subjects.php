<?php defined('INFOX') or die('No direct access allowed.'); ?>
<style>
.readonly{pointer-events: none;}
</style>
<link href="/cdn/plugins/easy/easy.css" rel="stylesheet"/>
<script src="/cdn/plugins/easy/easy.js"></script>
<script type="text/babel">
    <?php include('common_react.php'); ?>
    class SubjectManager extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            data_courses: [],
            data_employees: [],
            data_basic_loaded: false,
            data_subjects: [],
            input_course_select: '',
            auto_subject: '',
        }
        this.load_data = this.load_data.bind(this);
        this.onChangeHandler = this.onChangeHandler.bind(this);
        this.add_subject_submit = this.add_subject_submit.bind(this);
        this.delete_subject = this.delete_subject.bind(this);
        this.load_table = this.load_table.bind(this);
        this.reassign_subject = this.reassign_subject.bind(this);
    }
    onChangeHandler(e) {
        if(e.target.value !== ''){
        this.load_table(e.target.value);
        }
        this.setState({
            input_course_select: e.target.value,
            data_subjects:[]
        })
    }
    load_data = () => {
        axios.get('/subjects').then((response) => {
            this.setState({
                data_courses: response.data.courses,
                data_employees: response.data.employees,
                data_basic_loaded: true,
            })
        });
    }
    load_table = (course_id) => {
        axios.get('/subject?course=' + course_id).then((response) => {
            this.setState({
                data_subjects: response.data
            });
            try{
                
                let options = {	url: "/cdn/plugins/easy/subject.js"};
            $("#subject_autocomplete").easyAutocomplete(options);
            }catch(e){console.log(e)}
        })
            .catch((err) => {
                this.setState({
                    data_subjects: []
                })
            })
    }
    componentDidMount() {
        this.load_data();
    }
    add_subject_submit = (e) => {
        e.preventDefault();
        var data = $('#add_new_subject').serializeObject();
        $('#add_new_subject').hide();
        axios.post('subjects?action=add', data).then((response) => {
            const data_subjects = this.state.data_subjects;
            data_subjects.push(response.data)
            this.setState({
                data_subjects: data_subjects
            })
            $('#add_new_subject').show();
            $('#add_new_subject')[0].reset();
            alertify.success('Data Added');
        }).catch((error) => {
            $('#add_new_subject').show();
        })
    }
    delete_subject = (course,employee,subject) => {
        let data = {
            course : course,
            employee :employee,
            subject :subject
        }
        if (window.confirm("Do you want to Delete (Delete all data including Contents)")) {
            axios.post('subjects?action=del',data).then((response) => {
            alertify.success(response.data);
            this.load_table(course);
        });
    }
}
    reassign_subject = (e) => {
        e.preventDefault();
        var data = $('#form_reassign').serializeObject();
        if (window.confirm("Do you want change it?")) {
            axios.post('subjects?action=change',data).then((response) => {
                $('#form_reassign')[0].reset();
            alertify.success(response.data);
            this.load_table(this.state.input_course_select);
        });
    }
    }
    render() {
        return (
            <div className="row">
                <div className="col-xs-12">
                    <div className="box">
                        <div className="box-header">
                            <h3>
                                {
                                    this.state.data_courses.length > 0 ?

                                        <div className="form-group">
                                            <select className="form-control" value={this.state.input_course_select} onChange={this.onChangeHandler.bind(this)}>
                                                <option value={''} selelected> Choose a Course</option>
                                                {this.state.data_courses.map(course => {
                                                    return (
                                                        <option value={course.u_id}>{course.u_name}({course.u_code})</option>
                                                    )
                                                })}
                                            </select>
                                        </div>
                                        :
                                        <div>
                                            {this.state.data_basic_loaded == true ? <h3>NO_DATA PLEASE ADD FIRST</h3> : <h3>RELOAD</h3>}
                                        </div>
                                }
                            </h3>
                        </div>
                        <div className="box-body table-responsive no-padding">
                            {
                                this.state.data_subjects.length > 0 ?
                                    <table className="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Subject</th>
                                                <th>Employee</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {
                                                this.state.data_subjects.map((subject) => {
                                                    return (
                                                        <tr>
                                                        <td><b>{subject.u_name}</b> - {subject.u_desc}  {subject.shared === 0 ? <span className="pull-right">{subject.u_code}</span> : <span className="label label-danger pull-right">{subject.u_code}</span>} </td>
                                                            <td>{this.state.data_employees.filter(item => item.u_id === subject.employee_id).map(employee => { return (employee.u_name) })}</td>
                                                            <td>
                                                                <button onClick={()=>{
                                                                    this.delete_subject(subject.course_id,subject.employee_id,subject.u_id);
                                                                }}><i className="fa fa-trash"></i></button>
                                                            </td>
                                                        </tr>
                                                    )
                                                })
                                            }
                                        </tbody>
                                    </table>

                                    : null
                            }
                        </div>
                        <div className="box-footer">
                            {this.state.data_courses.length > 0 && this.state.input_course_select !== '' ?
                                <form id="add_new_subject" onSubmit={this.add_subject_submit}>
                                    <hr/>
                                            <input name="course_id" value={this.state.input_course_select} required type="hidden" className="form-control input-sm readonly" placeholder="course_id" />
                                  

                                    <div>
                                    <h4 className="text-bold">Add New Subject</h4>
                                    </div>
                                    <div className="row">
                                        <div className="col-md-4 mt-5">
                                            <input id="subject_autocomplete" type="text" className="form-control" required name="name" placeholder="Subject Name" />
                                        </div>
                                        <div className="col-md-4 mt-5">
                                            <select name="employee_id" required type="text" className="form-control">
                                                <option value={''} selelected> Choose an Employee</option>
                                                {this.state.data_employees.map(employee => {
                                                    return (
                                                        <option value={employee.u_id}>{employee.u_name} - [{employee.username}]</option>
                                                    )
                                                })}
                                            </select>
                                        </div>
                                        <div className="col-md-4 mt-5">
                                            <div className="radio">
                                                <span lassName="mx-1 text-bold">Shared</span>
                                                <label className="mx-1">
                                                    <input type="radio" name="shared" require value="0" checked />No</label>
                                                <label className="mx-1">
                                                    <input type="radio" name="shared" required value="1" />Yes</label>
                                            </div>
                                        </div>
                                        <div className="col-md-8 mt-5">
                                            <input type="text" className="form-control" name="desc" required placeholder="Description" />
                                        </div>
                                        <div className="col-md-2 mt-5">
                                            <button type="submit" className="btn btn-block btn-primary"><i className="fa fa-plus"></i> Add</button>
                                        </div>

                                    </div>
                                </form>
                                : null}
                                {this.state.data_subjects.length > 0 ? 
                                <div className="mb-10">
                                <hr/>
                                <h4 className="text-bold">Re-Assign Subject</h4>
                                <form className="row" id="form_reassign" onSubmit={this.reassign_subject}>
                                <div className="col-md-5">
                                <select name="subject" required type="text" className="form-control input-sm">
                                                <option value={''} selelected> Choose Subject</option>
                                                {this.state.data_subjects.map(subject => {
                                                    return (
                                                        <option value={subject.u_id}>{subject.u_name}  - [{subject.u_code}]</option>
                                                    )
                                                })}
                                            </select>
                                            </div>
                                            <div className="col-md-5">
                                <select name="employee" required type="text" className="form-control input-sm">
                                                <option value={''} selelected> Choose an Employee</option>
                                                {this.state.data_employees.map(employee => {
                                                    return (
                                                        <option value={employee.u_id}>{employee.u_name} - [{employee.username}]</option>
                                                    )
                                                })}
                                            </select>
                                            </div>
                                <div className="col-md-2">
                                <button className="btn btn-sm btn-primary">Re-Assign</button>
                                </div>
                                </form>
                                <hr/>
                                </div>
                                :null}

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
                <h1>Subject Manager<small></small></h1>
                <ol className="breadcrumb">
                    <li><a href="#" className="text-capitalize"><i className="fa fa-user"></i>Manager</a></li>
                    <li>Institution Management</li>
                    <li>Core</li>
                    <li className="active">Subject/Paper</li>
                </ol>
            </section>
            <br />
            <section className="content container-fluid">
                <SubjectManager />
            </section>
        </div>
    )
}
ReactDOM.render(<App />, rootElement);
</script>