<?php defined('INFOX') or die('No direct access allowed.');?>
<script type="text/babel">
    <?php include('common_react.php'); ?>
    class SubscriptionManager extends React.Component{
        constructor(props){
            super(props);
            this.state = {
                search_box : '',
                users : [],
            }
            this.search_box_handle = this.search_box_handle.bind(this);
            this.fetch_user = this.fetch_user.bind(this);
            this.subscription_action = this.subscription_action.bind(this);
        }
        search_box_handle = (e) =>{
            this.setState({
                search_box : e.target.value
            })
        }
        fetch_user = async (s) =>{
            await axios.get(`subscription?s=${s}`).then((response)=>
            {
                this.setState({
                    users : response.data
                })
            })
        }
        subscription_action = (action,uid) =>{
            let data = $(`#${uid}`).serializeObject();
            axios.post(`/subscription?action=${action}`,data).then((response)=>{
                alertify.success(response.data);
                this.fetch_user(this.state.search_box);
            })
        }
        render() {
            return(
                <div className="">
                    <div className="container">
                    <div class="input-group input-group-sm">
                        <input type="text" value={this.state.search_box} onChange={this.search_box_handle.bind(this)} class="form-control" placeholder="Admission Number"/>
                        <span class="input-group-btn">
                            <button type="button" onClick={ () =>{
                                this.setState({users:[]})
                                this.fetch_user(this.state.search_box);
                            }} class="btn btn-info btn-flat">Fetch!</button>
                        </span>
                    </div>
                    </div>
                    {
                        this.state.users.length > 0 ?
                        <div>{
                            this.state.users.map((user)=>{
                                return(
                                    <div class="container mt-10">
                                    <div class="col-md-8 box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">{user.u_name} -  {new Date(user.exp_date).toLocaleString()}</h3>
              <div class="pull-right">                 {user.active===1?<button className="btn btn-xs btn-danger" onClick={()=>{this.subscription_action("deactivate",user.u_id);}}>Deactivate</button>:<button className="btn btn-xs btn-success"onClick={()=>{this.subscription_action("activate",user.u_id);}}>Activate</button>}
</div>
            </div>
            <div class="box-body">
              <form class="row" id={user.u_id}>
              <input type="hidden" value={user.u_id} name="user"/>
                <div class="col-md-5">
                <div class="form-group">
                <label >Receipt/Passcode</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" maxLength="6" defaultValue={user.password} name="password"/>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-info btn-flat"onClick={()=>{this.subscription_action("renew",user.u_id);}}>Renew</button>
                        </span>
                    </div>
                </div>
                </div>
                <div class="col-md-7">
                <div class="form-group">
                <label >Extending Date</label>
                <div class="input-group input-group-sm">
                  <input type="datetime-local" class="form-control" placeholder=".col-xs-4" name="date"/>
                  <span class="input-group-btn">
                            <button type="button" class="btn btn-warning btn-flat"onClick={()=>{this.subscription_action("extend",user.u_id);}}>Extend</button>
                </span>
                  </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
                                    </div>
                                )
                            })
                        }</div> :null
                    }
                </div>
            )
        }
    }
    function App() {
    return (
        <div>
            <section className="content-header">
                <h1>Subscriptions<small></small></h1>
                <ol className="breadcrumb">
                    <li><a href="#" className="text-capitalize"><i className="fa fa-dashboard"></i>Manager</a></li>
                    <li className="active">subscription</li>
                </ol>
            </section>
            <br/>
            <section className="content container-fluid">
            <SubscriptionManager/>
            </section>
        </div>
    )
}
ReactDOM.render(<App />,rootElement);
</script>