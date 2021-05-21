<?php defined('INFOX') or die('No direct access allowed.');?>
<script type="text/babel">
    <?php include('common_react.php'); ?>
    class CourseManager extends React.Component{
        constructor(props){
            super(props);
            this.state = {
                courses_data : []
            }
            this.load_data = this.load_data.bind(this);
            this.add_new_course = this.add_new_course.bind(this);
            auto_subject:''
        }
        componentDidMount(){
            this.load_data();
        }
        load_data = () => {
            axios.get('/courses').then((response)=>{
                this.setState({
                    courses_data:response.data
                })
            })
        }
        onChangeHandler_auto_code(e){
    this.setState({
        auto_subject: e.target.value.toUpperCase().replace(/\s+/g, '-')
    })
  }
        add_new_course = (e) => {
            e.preventDefault();
            $('#courseAddModal').hide();
             var data = $('#add_new_course').serializeObject();
             axios.post('courses?action=new',data).then((response)=>{
                 const current_courses = this.state.courses_data;
                 current_courses.push(response.data)
                 this.setState({
                     courses_data:current_courses
                 })
                 $('#add_new_course')[0].reset();
                 $('#courseAddModal').show().modal('hide');
                 alertify.success('Data Added');
             }).catch((error)=>{
                $('#courseAddModal').show();
             })
        }
        render(){
            return(<div>
                <div className="row">
                {this.state.courses_data.map(course=>{
                        return(  <div className="col-md-4">
          <div className="box box-purple">
            <div className="box-header">
              <h3 className="box-title">{course.u_name}</h3>
              <div className="box-tools pull-right">
              {course.active===0?<button className="btn btn-xs btn-success mx-1">Active</button>:<button className="btn btn-xs btn-warning mx-1">Deactivate</button>}
            <button className="btn btn-xs btn-danger mx-1"><i className="fa fa-trash"></i></button>
              </div>
            </div>
            <div className="box-body">
            {course.u_code}
            <p>{course.u_desc}</p>
            {course.subjects.map(subject=>{
                return(<span class="label label-info mx-1">{subject.u_name}</span>)
            })}
            </div>
          </div>
        </div>)
                    })}
                </div>
                <button className="btn btn-sm btn-success mx-1" type="button"  data-toggle="modal" data-target="#courseAddModal">Add New</button>
                <button className="btn btn-sm btn-success mx-1" onClick={()=>{
                    this.load_data()
                }}>Refresh</button>
                <div className="modal fade" id="courseAddModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="courseAddModalLabel" aria-hidden="true">
        <div className="modal-dialog  modal-dialog-centered">
            <div className="modal-content">
                <div className="modal-header">
                    <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 className="modal-title" id="courseAddModalLabel">Add new Course!</h4>
                </div>
                <div className="modal-body">
                <form id="add_new_course" onSubmit={this.add_new_course}>
                <div className="form-group">
                <input className="form-control" type="text" name="name" required placeholder="Course Name"  onChange={this.onChangeHandler_auto_code.bind(this)} />
                </div>
                <div className="form-group">
                <input className="form-control" type="text" name="desc" required placeholder="Description"/>
                </div>
                <div className="form-group">
                <input className="form-control" type="text" name="code" value={this.state.auto_subject} required pattern="[A-Z0-9-]*" readonly placeholder="Course code <UPPERCASE>"/>
                </div>
                <div className="form-group">
                <button type="submit">Add</button>
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
                <h1>Courses Manager <small>create/manage courses</small></h1>
                <ol className="breadcrumb">
                    <li><a href="#" className="text-capitalize"><i className="fa fa-dashboard"></i>Manager</a></li>
                    <li className="active">Cources/class/management</li>
                </ol>
            </section>
            <br/>
            <section className="content container-fluid">
            <CourseManager/>
            </section>
        </div>
    )
}
ReactDOM.render(<App />,rootElement);
</script>