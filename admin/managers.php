<?php defined('INFOX') or die('No direct access allowed.');?>
<style>
    .row {
        margin-top: 10px;
    }
    [class^='col'],
    [class*=' col'] {
        margin-bottom: 10px;
    }
</style>
<script type="text/babel">
    <?php include('common_react.php'); ?>
class ManagersModule extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            managers_data: [],
            manager_roles: [],
            institution_list: [],
        };
        this.loaddata = this.loaddata.bind(this);
        this.action_manger = this.action_manger.bind(this);
    }
    componentDidMount() {
        this.loaddata();
    };
    loaddata = () => {
        axios.get('/managers').then((response) => {
            this.setState({
                managers_data: response.data.managers,
                manager_roles: response.data.roles,
                institution_list: response.data.institutions,
            });
        });
    }
    add_instition_manager = (event) => {
        event.preventDefault();
        const data = $("#add_instition_manager").serializeObject();
        console.log(data)
        axios.post('/managers?action=add', data).then((resposne) => {
            this.loaddata();
            $("#add_instition_manager")[0].reset();
        });
    }
    action_manger = (id, action) => {
        axios.post('/managers?action=' + action, { 'id': id }).then((response) => {
            this.loaddata();
        })
    }
    render() {
        return (
            <div>
                <div className="box">
                    <div className="box-header with-border">
                        <h3 className="box-title">Add New Manager</h3>
                    </div>
                    <div className="box-body">
                        <form className="form" id="add_instition_manager" onSubmit={this.add_instition_manager}>
                        
                        <div className="row"> 
                        <div className="col-md-3">
                            <select className="form-control " id="institution_select" required name="id">
                                <option value="" selected disabled readonly>institution_select</option>
                                {this.state.institution_list.map(inst => {
                                    return (<option value={inst.u_id}>{inst.u_name}</option>)
                                })}
                            </select>
                            </div>
                            <div className="col-md-3">
                            <select className="form-control " id="institution_role" required name="type">
                                <option value="" selected disabled readonly>institution_role</option>
                                {this.state.manager_roles.map(inst => {
                                    return (<option value={inst}>{inst}</option>)
                                })}
                            </select>
                            </div>
                            <div className="col-md-3">
                            <input type="email" className="form-control" placeholder="Email" required name="username" />
                            </div>
                            <div className="col-md-3">
                            <input type="text" className="form-control " placeholder="Name" required name="name" />
                            </div>
                            </div>
                            <div className="row"> 
                            <div className="col-md-3">
                            <input type="text" className="form-control " placeholder="Designation" required name="designation" />
                            </div>
                            <div className="col-md-3">
                            <input type="tel" className="form-control " placeholder="Contact" required onkeypress="return isNumberKey(event)" maxlength="10" pattern="[0-9]{10}" name="contact" />
                            </div>
                            <div className="col-md-6">
                            <input type="text" className="form-control " placeholder="Address" required name="address" />
                            </div>
                            </div>
                            <div className="row"> 
                            <div className="col-md-12">
                            <button type="submit" className="btn btn-primary mb-2">Submit</button>
                            </div>
                            </div>
                        </form>
                    </div>
                </div>

                <br />
                <div className="box">
                    <div className="box-header with-border">
                        <h3 className="box-title">All Managers</h3>
                    </div>
                    <div className="box-body table-responsive no-padding">
                        <table className="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Institution</th>
                                    <th>Designation</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {this.state.managers_data.map((manager) => {
                                    return (
                                        <tr>
                                            <td>{manager.u_name}</td>
                                            <td>{manager.institution.u_name}</td>
                                            <td>{manager.u_designation}</td>
                                            <td>{manager.username}</td>
                                            <td>{manager.u_type}</td>
                                            <td>{manager.suspended === 1 ? <div>SUSPENDED</div> : <div>{manager.active === 0 ? <button className="btn btn-xs mx-1 btn-success" onClick={() => { this.action_manger(manager.u_id, 'activate') }}>Activate</button> : <button className="btn btn-xs mx-1 btn-warning" onClick={() => { this.action_manger(manager.u_id, 'deactivate') }}>Deactivate</button>}<button className="btn btn-xs mx-1 btn-danger" onClick={() => { this.action_manger(manager.u_id, 'suspend') }}>Suspend</button></div>}</td>
                                        </tr>
                                    )
                                })}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        );
    }
}
// Create a function to wrap up your component
function App() {
    return (
        <div>
            <section className="content-header">
                <h1>M<small>Optional description</small></h1>
                <ol className="breadcrumb">
                    <li><a href="#" className="text-capitalize"><i className="fa fa-dashboard"></i>Admin</a></li>
                    <li className="active">Institution Managers</li>
                </ol>
            </section>
            <br/>
            <section className="content container-fluid">
                <ManagersModule />
            </section>

        </div>
    )
}
ReactDOM.render(<App />,rootElement);
</script>