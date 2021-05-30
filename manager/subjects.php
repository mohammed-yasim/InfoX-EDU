<style>
.readonly{pointer-events: none;}
</style>
<?php defined('INFOX') or die('No direct access allowed.'); ?>
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
        this.onChangeHandler_auto_code = this.onChangeHandler_auto_code.bind(this);
        this.add_subject_submit = this.add_subject_submit.bind(this);
        this.load_table = this.load_table.bind(this);
    }
    onChangeHandler(e) {
        this.load_table(e.target.value);
        this.setState({
            input_course_select: e.target.value
        })
    }
    onChangeHandler_auto_code(e) {
        this.setState({
            auto_subject: e.target.value.toUpperCase().replace(/\s+/g, '-')
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
            })
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
                                                <th>Code</th>
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
                                                            <td>
                                                                {subject.shared === 0 ? <span>{subject.u_code}</span> : <span className="label label-danger">{subject.u_code}</span>}
                                                            </td>
                                                            <td>{subject.u_name} <br />{subject.u_desc}</td>
                                                            <td>{this.state.data_employees.filter(item => item.u_id === subject.employee_id).map(employee => { return (employee.u_name) })}</td>
                                                            <td>
                                                                <button><i className="fa fa-trash"></i></button>
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
                            {this.state.data_courses.length > 0 ?
                                <form id="add_new_subject" onSubmit={this.add_subject_submit}>
                                    <div className="row">
                                        <div className="col-md-4 mt-5">
                                            <input name="course_id" value={this.state.input_course_select} required type="text" className="form-control input-sm readonly" placeholder="course_id" />
                                        </div>
                                        <div className="col-md-4 mt-5">
                                            <input type="text" className="form-control input-sm readonly" name="code" required value={this.state.auto_subject} placeholder="Subject-code" />
                                        </div>
                                    </div>
                                    <div className="row">
                                        <div className="col-md-4 mt-5">
                                            <input type="text" className="form-control" required name="name" onChange={this.onChangeHandler_auto_code.bind(this)} placeholder="Subject Name" />
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