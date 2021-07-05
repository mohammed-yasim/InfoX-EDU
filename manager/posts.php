<?php defined('INFOX') or die('No direct access allowed.');?>
<script type="text/babel">
    <?php include('common_react.php'); ?>
    class NotificationManger extends React.Component{
        constructor(props){
            super(props);
            this.state = {
                notifications : []
            }
            this.load_data = this.load_data.bind(this);
            this.delete_data = this.delete_data.bind(this);
            this.add_data = this.add_data.bind(this);
        }
        load_data = () =>{
            axios.get('/notification').then((response)=>{
                if (response.data){
                this.setState({
                    notifications : response.data
                })
                }
            })
        }
        delete_data = (a,b) =>{
            axios.delete(`/notification?id=${a}&user=${b}`).then((response)=>{
                this.load_data();
            })
        }
        add_data = (e) =>
        {
            e.preventDefault();
            let data = $(e.target).serializeObject()
            axios.post(`/notification`,data).then((response)=>{
                this.load_data();
            })
        }
        componentDidMount(){
            this.load_data();
        }
        render() {
            return(
                <div className="row">
                <div className="col-lg-6">
                <div>
              <h4>Author : <?php echo($_SESSION['_name']);?>  <button className="btn btn-info btn-sm pull-right" onClick={()=>{
                    this.load_data();
                }}><i className="fa fa-refresh"></i></button></h4>
                <hr/>
            </div>
               
                {this.state.notifications.map( notification => {
                    return(
                <div className="attachment-block clearfix">
                {notification.media==='image'?<img className="attachment-img" src={notification.uri} alt="Attachment Image"/>:null}
                <div className="attachment-pushed">
                  <h4 className="attachment-heading"><a href="#">{notification.title}</a></h4>

                  <div className="attachment-text">
                  {notification.desc} <br/><a href="{notification.url}">{notification.url}</a>
                  </div>
                </div>
                <button className="btn btn-danger btn-sm pull-right" onClick={()=>{
                    if(window.confirm("Do you want to delete")){
                    this.delete_data(notification.id,notification.author);
                    }
                }}><i className="fa fa-trash"></i></button>
              </div>
                )})}
                </div>
                <div className="col-lg-6">
                <div className="box box-info">
            <div className="box-header with-border">
              <h3 className="box-title">Post New</h3>
            </div>
            <form onSubmit={this.add_data} className="form-horizontal">
              <div className="box-body">
                <div className="form-group">
                  <label  className="col-sm-2 control-label">Title</label>

                  <div className="col-sm-10">
                    <input type="text" className="form-control" placeholder="Title" required name="title"/>
                  </div>
                </div>
                <div className="form-group">
                  <label  className="col-sm-2 control-label">Desc</label>

                  <div className="col-sm-10">
                    <textarea type="text" className="form-control" placeholder="Description" required name="desc"/>
                  </div>
                </div>
                <div className="form-group">
                  <label  className="col-sm-2 control-label">Media Type</label>
                  <div className="col-sm-10">
                    <select className="form-control"  required name="media">
                        <option value="image" selected>Image Url</option>
                        <option value="image-none">No image</option>
                        </select>
                  </div>
                </div>
                <div className="form-group">
                  <label  className="col-sm-2 control-label">Media uri</label>
                  <div className="col-sm-10">
                    <input type="text" className="form-control" placeholder="https://" required name="uri"/>
                  </div>
                </div>
                <div className="form-group">
                  <label  className="col-sm-2 control-label">Url</label>

                  <div className="col-sm-10">
                    <input type="text" className="form-control" placeholder="url" defaultValue="https://nooneducare.in" required name="url"/>
                  </div>
                </div>
                <div className="form-group">
                  <label  className="col-sm-2 control-label">Expiry_date</label>
                  <div className="col-sm-10">
                    <input type="datetime-local" className="form-control"  placeholder="Expiry_date"  required name="date"/>
                  </div>
                </div>
              </div>
              <div className="box-footer">
                <button type="submit" className="btn btn-info pull-right">POST</button>
              </div>
            </form>
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
                <h1>M<small>Optional description</small></h1>
                <ol className="breadcrumb">
                    <li><a href="#" className="text-capitalize"><i className="fa fa-dashboard"></i>Admin</a></li>
                    <li className="active">Cources/class/management</li>
                </ol>
            </section>
            <br/>
            <section className="content container-fluid">
            <NotificationManger/>
            </section>
        </div>
    )
}
ReactDOM.render(<App />,rootElement);
</script>