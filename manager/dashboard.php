<?php defined('INFOX') or die('No direct access allowed.');?>
<?php defined('INFOX') or die('No direct access allowed.');?>
<script type="text/babel">
    <?php include('common_react.php'); ?>
    class DashboardApp extends React.Component{
        constructor(props){
            super(props);
            this.state = {
                courses :0,
                employee :0,
                clients :0,
                users :20,
            }
        }
        render(){
        return(
            <div className="row">
    <div className="col-md-3 col-sm-6 col-xs-12">
        <div className="info-box">
            <span className="info-box-icon bg-aqua"><i className="fa fa-graduation-cap"></i></span>

            <div className="info-box-content">
                <span className="info-box-text">Courses</span>
                <span className="info-box-number">{this.state.courses}</span>
            </div>

        </div>

    </div>

    <div className="col-md-3 col-sm-6 col-xs-12">
        <div className="info-box">
            <span className="info-box-icon bg-red"><i className="fa fa-user-plus"></i></span>

            <div className="info-box-content">
                <span className="info-box-text">Employees</span>
                <span className="info-box-number">{this.state.employee}</span>
            </div>

        </div>

    </div>

    <div className="clearfix visible-sm-block"></div>

    <div className="col-md-3 col-sm-6 col-xs-12">
        <div className="info-box">
            <span className="info-box-icon bg-green"><i className="fa fa-user"></i></span>

            <div className="info-box-content">
                <span className="info-box-text">Clients</span>
                <span className="info-box-number">{this.state.clients}</span>
            </div>

        </div>

    </div>

    <div className="col-md-3 col-sm-6 col-xs-12">
        <div className="info-box">
            <span className="info-box-icon bg-yellow"><i className="fa fa-users"></i></span>

            <div className="info-box-content">
                <span className="info-box-text">Users</span>
                <span className="info-box-number">{this.state.users}</span>
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
                <h1>Dashboard<small></small></h1>
                <ol className="breadcrumb">
                    <li><a href="#" className="text-capitalize"><i className="fa fa-dashboard"></i></a>InfoX-EDU</li>
                    <li className="active">Dashboard</li>
                </ol>
            </section>
            <h2 class="text-center"><?php echo($_SESSION['_institution']);?></h2>
            <br/>
            <section className="content container-fluid">
            <DashboardApp/>
            </section>
        </div>
    )
}
ReactDOM.render(<App />,rootElement);
</script>
