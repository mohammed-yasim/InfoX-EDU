<?php defined('INFOX') or die('No direct access allowed.');?>
<script type="text/babel">
    <?php include('common_react.php'); ?>
    class LinkGenerator extends React.Component {
    constructor(props) {
        super(props);
        this.state = { init_audience: [], init_read: [] }
        this.load_init = this.load_init.bind(this);
        this.load_read = this.load_read.bind(this);
        this.delete_link = this.delete_link.bind(this);
    }
    load_init = async () => {
        await axios.get('/infox/init').then((response) => {
            this.setState({
                init_audience: response.data.audience
            });
        })
    }
    load_read = async () => {
        await axios.get('/infox/read?tool=link').then((response) => {
            this.setState({
                init_read: response.data
            });
        })
    }
    delete_link =  (id) =>{
        axios.get(`/infox/delete?tool=delete_link&id=${id}`).then((response) => {
            this.load_read();
        })
    }
    componentDidMount() {
        this.load_init();
        this.load_read();
    }
    form_submit = (e) => {
        e.preventDefault();
        let data = $('#link-gerenarator-form').serializeObject();
        axios.post('/infox/create?tool=link', data).then((response) => {
            this.load_read();
            $('#link-gerenarator-form')[0].reset();
        })
    }
    render() {
        return (
            <div>
                <div className="row">
                    <div className="col-xs-12">
                        <div className="box">
                            <div className="box-header">
                                <h3 className="box-title">Link Published</h3>
                            </div>
                            <div className="box-body table-responsive no-padding">
                                <table className="table table-hover">
                                    <tbody><tr>
                                        <th>title</th>
                                        <th>Description</th>
                                        <th>action</th>
                                    </tr>
                                        {this.state.init_read.map((link) => {
                                            return (
                                                <tr>
                                                    <td>{link.u_title}<br/>{link.u_desc}<br/>
                                                    <a>{link.u_blob}</a></td>
                                                    <td>{new Date(link.pub_date).toString()}<br/>{new Date(link.exp_date).toString()}</td>
                                                    <td><button onClick={()=>{
                                                        this.delete_link(link.u_id);
                                                    }} className="btn btn-danger"><i className="fa fa-trash"></i></button></td>
                                                     </tr>
                                            )
                                        })}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="container">
                    <div className="box box-warning box-solid">
                        <div className="box-header with-border">
                            <h3 className="box-title">Link Generator</h3>
                        </div>
                        <div className="box-body">
                            <form id="link-gerenarator-form" onSubmit={this.form_submit}>
                                <div className="form-group">
                                    <label>Target Audience</label>
                                    <select className="form-control" name="infox" required>
                                        <option>Choose target</option>
                                        {this.state.init_audience.map(option => {
                                            return (<option value={option.value}>{option.name}</option>)
                                        })}
                                    </select>
                                </div>
                                <div className="form-group">
                                    <label>Title</label>
                                    <input className="form-control" type="text" name="u_title" required />
                                </div>
                                <div className="form-group">
                                    <label>Description</label>
                                    <input className="form-control" type="text" name="u_desc" required />
                                </div>
                                <div className="form-group">
                                    <label>Target Url</label>
                                    <input className="form-control" type="text" pattern="https?://.+"
                                        title="Include https://" name="u_blob" required />
                                </div>
                                <div className="form-group">
                                    <label>Publish on</label>
                                    <input className="form-control" type="datetime-local" name="pub_date" required />
                                </div>
                                <div className="form-group">
                                    <label>Visible up to</label>
                                    <input className="form-control" type="datetime-local" name="exp_date" required />
                                </div>
                                <div className="form-group">
                                    <button className="btn btn-md btn-warning">Insert</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}
class App extends React.Component {
    constructor(props) {
        super(props);
        this.state = {}
    }
    render() {
        return (
            <div>
                <section className="content-header">
                    <h1>Infox Tools<small>Link,Zoom,Room,Frame,Youtube</small></h1>
                    <ol className="breadcrumb">
                        <li><a href="#" className="text-capitalize"><i className="fa fa-dashboard"></i>Admin</a></li>
                        <li className="active">Cources/class/management</li>
                    </ol>
                </section>
                <br />
                <section className="content container-fluid">
                    <LinkGenerator />
                </section>
            </div>
        )
    }
}
ReactDOM.render(<App />, rootElement);
</script>