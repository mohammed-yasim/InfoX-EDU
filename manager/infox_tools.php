<?php defined('INFOX') or die('No direct access allowed.');?>
<script type="text/babel">
    <?php include('common_react.php'); ?>
    class LinkGenerator extends React.Component{
        constructor(props){
            super(props);
            this.state = { init_audience:[] }
            this.load_init = this.load_init.bind(this);
        }
        load_init = () => {
            axios.get('/infox/init').then((response)=>{
                this.setState({
                    init_audience : response.data.audience
                });
            })
        }
        componentDidMount(){
            this.load_init();
        }
        form_submit = (e) => {
            e.preventDefault();
            let data = $('#link-gerenarator-form').serializeObject();
            axios.post('/infox/link-generator',data).then((response)=>{
                $('#link-gerenarator-form')[0].reset();
            })
        }
        render(){
            return(
                <div class="container">
                <div class="box box-warning box-solid">
                <div class="box-header with-border">
                <h3 class="box-title">Link Generator</h3>
                </div>
                <div class="box-body">
                <form id="link-gerenarator-form" onSubmit={this.form_submit}>
                <div class="form-group">
                <label>Target Audience</label>
                <select class="form-control" name="infox" required>
                <option>Choose target</option>
                {this.state.init_audience.map(option=>{
                    return(<option value={option.value}>{option.name}</option>)
                })}
                </select>
                </div>
                <div class="form-group">
                <label>Title</label>
                <input class="form-control" type="text" name="u_title" required/>
                </div>
                <div class="form-group">
                <label>Description</label>
                <input class="form-control" type="text" name="u_desc" required />
                </div>
                <div class="form-group">
                <label>Target Url</label>
                <input class="form-control" type="text"  pattern="https?://.+"
               title="Include http://" name="u_blob" required />
                </div>
                <div class="form-group">
                <label>Publish on</label>
                <input class="form-control" type="datetime-local" name="pub_date" required/>
                </div>
                <div class="form-group">
                <label>Visible up to</label>
                <input class="form-control" type="datetime-local" name="exp_date" required />
                </div>
                <div class="form-group">
                <button className="btn btn-md btn-warning">Insert</button>
</div>
                </form>
                </div>
                </div>
                </div>
            )
        }
    }
    class App extends React.Component {
        constructor(props){
            super(props);
            this.state = { }
        }

        render(){
    return (
        <div>
            <section className="content-header">
                <h1>Infox Tools<small>Link,Zoom,Room,Frame,Youtube</small></h1>
                <ol className="breadcrumb">
                    <li><a href="#" className="text-capitalize"><i className="fa fa-dashboard"></i>Admin</a></li>
                    <li className="active">Cources/class/management</li>
                </ol>
            </section>
            <br/>
            <section className="content container-fluid">
            <LinkGenerator/>
            </section>
        </div>
    )
        }
}
ReactDOM.render(<App />,rootElement);
</script>