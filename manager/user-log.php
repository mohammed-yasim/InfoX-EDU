<?php defined('INFOX') or die('No direct access allowed.');?>
<script type="text/babel">
    <?php include('common_react.php'); ?>
    class UserLogger extends React.Component{
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
            await axios.get(`userlog?s=${s}`).then((response)=>
            {
                this.setState({
                    users : response.data
                });
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
                                return(<div>
                                <ul>
                                {user.userlogs.map(log=>{
                                    return(
                                        <li>{log.narration}</li>
                                    )
                                })}
                                </ul>
                                </div>)
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
                <h1>M<small>Optional description</small></h1>
                <ol className="breadcrumb">
                    <li><a href="#" className="text-capitalize"><i className="fa fa-dashboard"></i>Admin</a></li>
                    <li className="active">Cources/class/management</li>
                </ol>
            </section>
            <br/>
            <section className="content container-fluid">
            <UserLogger/>
            </section>
        </div>
    )
}
ReactDOM.render(<App />,rootElement);
</script>