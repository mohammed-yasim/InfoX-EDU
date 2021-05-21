<?php defined('INFOX') or die('No direct access allowed.');?>
<script type="text/babel">
    <?php include('common_react.php'); ?>
    class EmployeeManager extends React.Component{
        constructor(props){
            super(props);
            this.state = {
                data_employees:[],
                data_filter:''
            }
            this.load_data = this.load_data.bind(this);
            this.onChangeHandler = this.onChangeHandler.bind(this);
            this.add_new_employee = this.add_new_employee.bind(this);
        }
        componentDidMount(){
            this.load_data()
        }
        load_data = () =>{
            axios.get('/employees').then((response)=>{
                this.setState({
                    data_employees:response.data,
                    data_filter:''
                })
            })
        }
        onChangeHandler(e){
    this.setState({
        data_filter: e.target.value,
    })
  }
  add_new_employee = (e) => {
            e.preventDefault();
            $('#employeeModal').hide();
             var data = $('#add_new_employee').serializeObject();
             axios.post('employees?action=add',data).then((response)=>{
                 const data_employees = this.state.data_employees;
                 data_employees.push(response.data)
                 this.setState({
                     courses_data:data_employees
                 })
                 $('#add_new_employee')[0].reset();
                $('#employeeModal').show().modal('hide');
                 alertify.success('Data Added');
             }).catch((error)=>{
                $('#employeeModal').show();
             })
        }
        render()
        {
            return(
                <div>
                <div className="col-xs-12">
            <button className="btn btn-xs btn-primary"  type="button"  data-toggle="modal" data-target="#employeeModal"><i className="fa fa-plus"></i> New Employee</button>
            <button className="btn btn-xs btn-success mx-1" onClick={()=>{
                    this.load_data()
                }}>Refresh</button>
                <div className="input-group input-group-sm hidden-xs pull-right" style={{width:'150px'}}>
                    <input type="text" value={this.state.data_filter} onChange={this.onChangeHandler.bind(this)} className="form-control" placeholder="Name Search"/>
                </div>
        </div>
               {this.state.data_employees.length > 0 ?<div> 
                <div className="col-xs-12">
        <br/>
    <div className="box">
        <div className="box-body table-responsive no-padding">
            <table className="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Designation</th>
                        <th>Papers/Subjects</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                {this.state.data_employees
                    .filter(employee => this.state.input === '' || employee.u_name.toLowerCase().includes(this.state.data_filter.toLowerCase()))
                    .map(
                    (employee) => {
                        return(
                            <tr key={employee.u_id}>
                            <th>{employee.username}</th>
                            <td>{employee.u_name}<br/>
                            {employee.u_address}<br/>
                            {employee.u_email}-{employee.u_contact}<br/>
                            </td>
                            <td>{employee.u_designation}</td>
                            <td>
                            {employee.subjects.length > 0 ? 
                                employee.subjects.map(subject=>{
                                    return(
                                        <span class="label label-success">{subject.u_code} - {subject.u_name}</span>
                                    )
                                })
                            :<span>Not allocated</span>}
                            </td>
                            <td>
                            {employee.active===0?<span>
                            <button className="btn btn-xs mx-1 btn-danger mt-5">Activate</button>
                            <button className="btn btn-xs mx-1 btn-danger mt-5"><i class="fa fa-trash"></i></button>
                            </span>
                            :<button className="btn btn-xs mx-1 btn-warning mt-5">Deactivate</button>}
                            <button className="btn btn-xs mx-1 btn-warning mt-5">Change Password</button>
                            </td>
                            </tr>
                        )
                    }
                )}
                </tbody>
            </table>
        </div>
    </div>
</div>
                </div>
                :null}
                <div className="modal fade" id="employeeModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
        <div className="modal-dialog  modal-dialog-centered">
            <div className="modal-content">
                <div className="modal-header">
                    <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 className="modal-title" id="employeeModalLabel">Add New Employee!</h4>
                </div>
                <div className="modal-body">
                <form id="add_new_employee" onSubmit={this.add_new_employee}>

                <div className="form-group">
                <div className="input-group">
                <div className="input-group-btn">
                  <button type="button" className="btn bg-maroon disabled">Institution_Prefix</button>
                </div>
                <input className="form-control" type="text" name="username" pattern="[A-Z0-9]*" required placeholder="Username <UPPERCASE-NUMBERS>"/>
                </div>
                </div>
                <div className="form-group">
                <input className="form-control" type="text" name="password" required placeholder="Pasword <MIN-5>" pattern="{5,}"/>
                </div>
                <div className="form-group">
                <input className="form-control" type="text" name="name" required placeholder="Name" pattern="[A-Za-z ]*"/>
                </div>
                <div className="form-group">
                <input className="form-control" type="text" name="designation" required placeholder="Designation"/>
                </div>
                <div className="form-group">
                <input className="form-control" type="text" name="address" required placeholder="Address"/>
                </div>
                <div className="form-group">
                <input className="form-control" type="email" name="email" required placeholder="Email"/>
                </div>
                <div className="form-group">
                <input className="form-control" type="tel" pattern="[0-9]{10}" max-maxlength="10" name="contact" required placeholder="Contact"/>
                </div>
                <div className="form-group">
                <button type="submit" className="btn btn-success btn-block">Add</button>
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
                <h1>Employee Management</h1>
                <ol className="breadcrumb">
                    <li><a href="#" className="text-capitalize"><i className="fa fa-dashboard"></i>Manager</a></li>
                    <li className="active">employee/management</li>
                </ol>
            </section>
            <br/>
            <section className="content container-fluid">
            <EmployeeManager/>
            </section>
        </div>
    )
}
ReactDOM.render(<App />,rootElement);
</script>