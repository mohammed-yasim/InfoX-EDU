<?php defined('INFOX') or die('No direct access allowed.');?>
<script type="text/babel">
    <?php include('common_react.php'); ?>
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
            </section>
        </div>
    )
}
ReactDOM.render(<App />,rootElement);
</script>