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
        this.setState(
            {
                selected_course: e.target.value
            }
        )
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
                                        <table>
                                            <tbody>
                                                {this.state.user_temp_list.map((user) => {
                                                    return (
                                                        <tr>

                                                        </tr>
                                                    )
                                                })}
                                            </tbody>
                                        </table>
                                    </div>
                                    :
                                    <h2> No Users List</h2>}
                                <UserImporter course={this.state.selected_course} />
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
        axios.post(`/users?course=${this.props.course}`, data);
    }
    render() {
        return (
            <div>
                <input onChange={this.onFileChange.bind(this)} type="file" />
                <form onSubmit={this.saveImported} id="imported_data">
                    <table>
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
                                                <input type="text" className="form-control" required value={id} name="id" size={2} />
                                            </td>
                                            <td>
                                                <input type="text" className="form-control" required value={data[0]} name="adno" />
                                            </td>
                                            <td><input type="text" className="form-control" required value={data[1]} name="name" />
                                            </td>
                                            <td><input type="text" className="form-control" required value={data[2]} name="house" />
                                            </td>
                                             <td><input type="text" className="form-control" required value={data[3]} name="place" />
                                            </td>
                                            <td><input type="text" className="form-control" required value={data[4]} name="contact" />
                                            </td>
                                            <td><input type="text" className="form-control" required value={data[5]} name="fname" />
                                            </td>
                                            <td><input type="text" className="form-control" required value={data[6]} name="fmob" />
                                            </td>
                                            <td><input type="text" className="form-control" required value={data[7]} name="mname" />
                                            </td>
                                            <td><input type="text" className="form-control" required value={data[8]} name="mmob" />
                                            </td>
                                            <td><input type="text" className="form-control" required value={data[9]} name="dob" />
                                            </td>
                                            <td><input type="text" className="form-control" required value={data[10]} name="gender" />
                                            </td>
                                            <td><input type="text" className="form-control" required value={data[11]} name="class" />
                                            </td>
                                            <td><input type="text" className="form-control" required value={data[12]} name="div" />
                                            </td>
                                           </tr>
                                    )
                                }
                            })}
                        </tbody>
                    </table>
                    <button tye="submit">Save</button>
                </form>
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