<?php defined('INFOX') or die('No direct access allowed.');?>
<script type="text/babel">
    <?php include('common_react.php'); ?>
    class GoogleMeet extends React.Component{
        constructor(props){
            super(props);
            this.state = {

            }
        }
        load_data = () =>{

        }
        componentDidMount(){
            this.load_data();
        }
        render(){
            return(
                <div class="container">
                <div class="box box-warning box-solid">
                <div class="box-header with-border">
                <h3 class="box-title">Google Meet</h3>
                </div>
                <div class="box-body">
                    
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
                <h1>Infox Tools<small></small></h1>
                <ol className="breadcrumb">
                    <li><a href="#" className="text-capitalize"><i className="fa fa-dashboard"></i>Admin</a></li>
                    <li className="active">Cources/class/management</li>
                </ol>
            </section>
            <br/>
            <section className="content container-fluid">
            <GoogleMeet/>
            </section>
        </div>
    )
}
ReactDOM.render(<App />,rootElement);
</script>