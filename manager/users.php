<?php defined('INFOX') or die('No direct access allowed.');?>
<script src="/cdn/papaparse.js"></script>
<script type="text/babel">
    <?php include('common_react.php'); ?>
    class UserManager extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            loading: true,
            loaded: false,
            course_list: [],
            selected_course: '',
            user_temp_list: [],
        }
        this.load_data = this.load_data.bind(this);
        this.onCourseChange = this.onCourseChange.bind(this);
    }
    load_data = () => {
        axios.get('/users').then((response) => {
            this.setState({
                course_list: response.data,
                loading: false,
                loaded: true,
            })
        })
    }
    componentDidMount() {
        this.load_data();
    }
    onCourseChange = (e) => {
        let course_id = e.target.value;
        axios.get(`/user?course=${course_id}`).then((response)=>{
        this.setState({
                selected_course: course_id,
                user_temp_list: response.data,
            })
        });
    }
    render() {
        return (
            <div>
                {this.state.loading === false && this.state.loaded === true ?
                    <div>
                        {this.state.course_list.length > 0 ?
                            <div>
                                <select className="form-control" onChange={this.onCourseChange.bind(this)} value={this.state.selected_course}>
                                    <option slected value="">Choose Course</option>
                                    {this.state.course_list.map(
                                        (course) => {
                                            return (
                                                <option value={course.u_id}>{course.u_name}</option>
                                            )
                                        }
                                    )}
                                </select>
                                {this.state.user_temp_list.length > 0 ?
                                    <div>
                                        <table  class="table table-hover">
                                        <thead>
                            <tr>
                                <th>SINo.</th>
                                <th>Username</th>
                                <th>Password</th>
                                <td> Actions</td>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Whatsapp No</th>
                                <td>Settings</td>
                            </tr>
                        </thead>
                                            <tbody>
                                                {this.state.user_temp_list.map((user,id) => {
                                                    return (
                                        <tr>
                                            <td>
                                                <input type="text" className="form-control" required defaultValue={id} name="id" size={2} />
                                            </td>
                                            <td>
                                                <input type="text" className="form-control" required defaultValue={user.username} name="adno" />
                                            </td>
                                            <td>
                                                <input type="text" className="form-control" required defaultValue={user.password} name="adno" />
                                            </td>
                                            <td></td>
                                            <td><input type="text" className="form-control" required defaultValue={user.u_name} name="name" />
                                            </td>
                                            <td><input type="text" className="form-control" required defaultValue={user.u_address} name="house" />
                                            </td>
                                            
                                            <td><input type="text" className="form-control" required defaultValue={user.u_contact} name="contact" />
                                            </td>
                                            <td></td>
                                           </tr>
                                    )
                                                })}
                                            </tbody>
                                        </table>
                                    </div>
                                    :
                                    <h2> No Users List</h2>}
                                    {this.state.selected_course !== '' ? <div><UserImporter course={this.state.selected_course}/></div>:null}
                            </div> : <h2>Please Add Course First</h2>}
                    </div>
                    : null}
            </div>
        )
    }
}
class UserImporter extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            csv_list: []
        }
        this.onFileChange = this.onFileChange.bind(this)
        this.saveImported = this.saveImported.bind(this)
    }
    onFileChange = (e) => {
        const [file] = e.target.files
        window.daya = []
        if (file) {
            var promise = new Promise(function (resolve, reject) {
                window.Papa.parse(file, {
                    complete: function (results) {
                        window.daya = results.data
                        resolve(true);
                    }
                })
            })
            promise.then(bool => {
                this.setState({
                    csv_list: window.daya
                })
            })
        }
    }
    saveImported = (e) => {
        e.preventDefault();
        let data = $("#imported_data").serializeObject();
        axios.post(`/users?course=${this.props.course}`, data).then((response)=>{
            this.setState({
            csv_list: []
        });
        alertify.success('Ok: ' + response.data);
        $('#csv_ip').val('')
        })
    }
    render() {
        return (
            <div>
            <a href="/example_user_import.csv">Example</a>
                <input id="csv_ip" onChange={this.onFileChange.bind(this)} type="file" />
                {this.state.csv_list.length>0 ?
                    <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Imported CSV</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <form onSubmit={this.saveImported} id="imported_data">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>SINo.</th>
                                <th>AdNo.</th>
                                <th>Name</th>
                                <th>House Name</th>
                                <th>Place</th>
                                <th>Whatsapp No</th>
                                <th>Father`s Name</th>
                                <th>Conatct</th>
                                <th>Mothers`s Name</th>
                                <th>Conatct</th>
                                <th>Date of Birth</th>
                                <th>Gender</th>
                                <th>Class</th>
                                <th>Div</th>
                            </tr>
                        </thead>
                        <tbody>
                            {this.state.csv_list.map((data, id) => {
                                if (id > 0) {
                                    return (
                                        <tr>
                                            <td>
                                                <input type="text" className="form-control" required defaultValue={id} name="id" size={2} />
                                            </td>
                                            <td>
                                                <input type="text" className="form-control" required defaultValue={data[0]} name="adno" />
                                            </td>
                                            <td><input type="text" className="form-control" required defaultValue={data[1]} name="name" />
                                            </td>
                                            <td><input type="text" className="form-control" required defaultValue={data[2]} name="house" />
                                            </td>
                                             <td><input type="text" className="form-control" required defaultValue={data[3]} name="place" />
                                            </td>
                                            <td><input type="text" className="form-control" required defaultValue={data[4]} name="contact" />
                                            </td>
                                            <td><input type="text" className="form-control" required defaultValue={data[5]} name="fname" />
                                            </td>
                                            <td><input type="text" className="form-control" required defaultValue={data[6]} name="fmob" />
                                            </td>
                                            <td><input type="text" className="form-control" required defaultValue={data[7]} name="mname" />
                                            </td>
                                            <td><input type="text" className="form-control" required defaultValue={data[8]} name="mmob" />
                                            </td>
                                            <td><input type="text" className="form-control" required defaultValue={data[9]} name="dob" />
                                            </td>
                                            <td><input type="text" className="form-control" required defaultValue={data[10]} name="gender" />
                                            </td>
                                            <td><input type="text" className="form-control" required defaultValue={data[11]} name="class" />
                                            </td>
                                            <td><input type="text" className="form-control" required defaultValue={data[12]} name="div" />
                                            </td>
                                           </tr>
                                    )
                                }
                            })}
                        </tbody>
                    </table>
                    <button className="btn btn-success mx-30" tye="submit">Save</button>
                </form>
                </div>
          </div>
        </div>
      </div>: null}
            </div>
        )
    }
}
function App() {
    return (
        <div>
            <section className="content-header">
                <h1>Users/Students<small></small></h1>
                <ol className="breadcrumb">
                    <li><a href="#" className="text-capitalize"><i className="fa fa-dashboard"></i>Admin</a></li>
                    <li className="active">Cources/class/management</li>
                </ol>
            </section>
            <br />
            <section className="content container-fluid">
                <UserManager />
            </section>
        </div>
    )
}
ReactDOM.render(<App />, rootElement);
</script>